<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\DeliveryAddress;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Menampilkan daftar pesanan
     */
    public function index(): View
    {
        /** @var User $user */
        $user = Auth::user();
        
        $orders = Order::with(['user', 'orderItems', 'payment', 'deliveryAddress'])
            ->when($user->role === 'customer', function ($query) use ($user) {
                return $query->where('user_id', $user->id);
            })
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Menampilkan form pembuatan pesanan
     */
    public function create(): View
    {
        $menuItems = MenuItem::all();
        return view('orders.create', compact('menuItems'));
    }

    /**
     * Menyimpan pesanan baru
     */
    public function store(StoreOrderRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            /** @var User $user */
            $user = Auth::user();

            // Validasi menu items dan hitung total
            $menuItems = MenuItem::whereIn('id', collect($request->items)->pluck('menu_item_id'))->get();
            if ($menuItems->count() !== count($request->items)) {
                throw new \Exception('Beberapa menu tidak valid atau tidak tersedia');
            }

            // Hitung total amount terlebih dahulu
            $totalAmount = collect($request->items)->sum(function ($item) use ($menuItems) {
                $menuItem = $menuItems->firstWhere('id', $item['menu_item_id']);
                return $menuItem->price * $item['quantity'];
            });

            // Buat pesanan dengan total amount
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => $user->id,
                'status' => 'pending',
                'notes' => $request->notes,
                'total_amount' => (float) $totalAmount
            ]);

            // Buat item pesanan
            foreach ($request->items as $item) {
                $menuItem = $menuItems->firstWhere('id', $item['menu_item_id']);
                $subtotal = $menuItem->price * $item['quantity'];
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $menuItem->id,
                    'quantity' => (int) $item['quantity'],
                    'price' => (float) $menuItem->price,
                    'subtotal' => (float) $subtotal,
                    'special_instructions' => $item['special_instructions'] ?? null,
                ]);
            }

            // Simpan informasi pengiriman
            DeliveryAddress::create([
                'order_id' => $order->id,
                'recipient_name' => $request->recipient_name,
                'recipient_phone' => $request->recipient_phone,
                'address' => $request->address,
                'postal_code' => $request->postal_code,
                'delivery_instructions' => $request->delivery_instructions,
            ]);

            // Simpan informasi pembayaran
            $payment = new Payment([
                'order_id' => $order->id,
                'payment_method' => $request->payment_method,
                'amount' => (float) $totalAmount,
                'status' => 'pending',
            ]);

            if ($request->hasFile('proof_of_payment')) {
                try {
                    $path = $request->file('proof_of_payment')->store('proof-of-payments', 'public');
                    $payment->proof_of_payment = $path;
                    $payment->status = 'pending';
                } catch (\Exception $e) {
                    Log::error('Error uploading proof of payment: ' . $e->getMessage());
                    throw new \Exception('Gagal mengupload bukti pembayaran');
                }
            } elseif (in_array($request->payment_method, ['transfer_bank', 'e_wallet'])) {
                throw new \Exception('Bukti pembayaran wajib diupload untuk pembayaran non-tunai');
            }

            $payment->save();

            DB::commit();

            return response()->json([
                'message' => 'Pesanan berhasil dibuat',
                'order' => $order->load(['orderItems.menuItem', 'payment', 'deliveryAddress']),
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Hapus file yang sudah diupload jika ada error
            if (isset($path)) {
                Storage::disk('public')->delete($path);
            }
            
            Log::error('Error creating order: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'Terjadi kesalahan saat membuat pesanan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menampilkan detail pesanan
     */
    public function show(Order $order): View
    {
        $this->authorize('view', $order);
        
        $order->load(['user', 'orderItems.menuItem', 'payment', 'deliveryAddress']);
        return view('orders.show', compact('order'));
    }

    /**
     * Menampilkan form edit pesanan
     */
    public function edit(Order $order): View
    {
        $this->authorize('update', $order);
        
        $order->load(['orderItems.menuItem', 'payment', 'deliveryAddress']);
        $menuItems = MenuItem::get();
        return view('orders.edit', compact('order', 'menuItems'));
    }

    /**
     * Mengupdate pesanan
     */
    public function update(UpdateOrderRequest $request, Order $order): JsonResponse
    {
        $this->authorize('update', $order);

        try {
            DB::beginTransaction();

            // Update pesanan
            $order->update([
                'status' => $request->status ?? $order->status,
                'notes' => $request->notes,
            ]);

            // Update item pesanan jika ada perubahan
            if ($request->has('items')) {
                // Hapus item yang lama
                $order->orderItems()->delete();

                // Buat item baru
                $totalAmount = 0;
                foreach ($request->items as $item) {
                    $menuItem = MenuItem::findOrFail($item['menu_item_id']);
                    $subtotal = $menuItem->price * $item['quantity'];
                    $totalAmount += $subtotal;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'menu_item_id' => $menuItem->id,
                        'quantity' => $item['quantity'],
                        'price' => $menuItem->price,
                        'subtotal' => $subtotal,
                        'special_instructions' => $item['special_instructions'] ?? null,
                    ]);
                }

                // Update total amount pesanan
                $order->update(['total_amount' => $totalAmount]);

                // Update jumlah pembayaran
                if ($order->payment) {
                    $order->payment->update(['amount' => $totalAmount]);
                }
            }

            // Update informasi pengiriman
            if ($order->deliveryAddress) {
                $order->deliveryAddress->update([
                    'recipient_name' => $request->recipient_name,
                    'recipient_phone' => $request->recipient_phone,
                    'address' => $request->address,
                    'postal_code' => $request->postal_code,
                    'delivery_instructions' => $request->delivery_instructions,
                ]);
            }

            // Update informasi pembayaran
            if ($request->has('payment_method') && $order->payment) {
                $paymentData = [
                    'payment_method' => $request->payment_method,
                ];

                if ($request->hasFile('proof_of_payment')) {
                    // Hapus bukti pembayaran lama jika ada
                    if ($order->payment->proof_of_payment) {
                        Storage::disk('public')->delete($order->payment->proof_of_payment);
                    }

                    $path = $request->file('proof_of_payment')->store('proof-of-payments', 'public');
                    $paymentData['proof_of_payment'] = $path;
                    $paymentData['status'] = 'pending';
                }

                $order->payment->update($paymentData);
            }

            DB::commit();

            return response()->json([
                'message' => 'Pesanan berhasil diperbarui',
                'order' => $order->load(['orderItems', 'payment', 'deliveryAddress']),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Terjadi kesalahan saat memperbarui pesanan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menghapus pesanan
     */
    public function destroy(Order $order): JsonResponse
    {
        $this->authorize('delete', $order);

        try {
            DB::beginTransaction();

            // Hapus bukti pembayaran jika ada
            if ($order->payment && $order->payment->proof_of_payment) {
                Storage::disk('public')->delete($order->payment->proof_of_payment);
            }

            // Hapus semua data terkait
            $order->orderItems()->delete();
            $order->payment()->delete();
            $order->deliveryAddress()->delete();
            $order->delete();

            DB::commit();

            return response()->json([
                'message' => 'Pesanan berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Terjadi kesalahan saat menghapus pesanan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mengubah status pesanan
     */
    public function updateStatus(Order $order, string $status): JsonResponse
    {
        $this->authorize('update', $order);

        $validStatuses = ['pending', 'processing', 'completed', 'cancelled'];

        if (!in_array($status, $validStatuses)) {
            return response()->json([
                'message' => 'Status pesanan tidak valid'
            ], 422);
        }

        try {
            $order->update(['status' => $status]);

            return response()->json([
                'message' => 'Status pesanan berhasil diperbarui',
                'order' => $order->fresh()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat memperbarui status pesanan',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 