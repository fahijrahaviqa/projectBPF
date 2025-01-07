<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\DeliveryAddress;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DriveThruController extends Controller
{
    public function index(): View
    {
        $orders = Order::with(['orderItems.menuItem', 'payment'])
            ->where('is_drive_thru', true)
            ->latest()
            ->paginate(10);

        return view('admin.drive-thru.index', compact('orders'));
    }

    public function create(): View
    {
        $menuItems = MenuItem::all();
        return view('admin.drive-thru.create', compact('menuItems'));
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.menu_item_id' => ['required', 'exists:menu_items,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:100'],
            'items.*.special_instructions' => ['nullable', 'string', 'max:255'],
            'payment_method' => ['required', 'in:cash,transfer_bank,e_wallet'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            DB::beginTransaction();

            // Validasi menu items dan hitung total
            $menuItems = MenuItem::whereIn('id', collect($request->items)->pluck('menu_item_id'))->get();
            if ($menuItems->count() !== count($request->items)) {
                throw new \Exception('Beberapa menu tidak valid atau tidak tersedia');
            }

            // Hitung total amount
            $totalAmount = collect($request->items)->sum(function ($item) use ($menuItems) {
                $menuItem = $menuItems->firstWhere('id', $item['menu_item_id']);
                return $menuItem->price * $item['quantity'];
            });

            // Buat pesanan
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => auth()->id(),
                'is_drive_thru' => true,
                'status' => 'pending',
                'notes' => $request->notes,
                'total_amount' => $totalAmount
            ]);

            // Buat item pesanan
            foreach ($request->items as $item) {
                $menuItem = $menuItems->firstWhere('id', $item['menu_item_id']);
                $subtotal = $menuItem->price * $item['quantity'];
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $menuItem->id,
                    'quantity' => $item['quantity'],
                    'price' => $menuItem->price,
                    'subtotal' => $subtotal,
                    'special_instructions' => $item['special_instructions'] ?? null,
                ]);
            }

            // Buat delivery address kosong (untuk konsistensi data)
            DeliveryAddress::create([
                'order_id' => $order->id
            ]);

            // Buat payment
            $payment = new Payment([
                'order_id' => $order->id,
                'payment_method' => $request->payment_method,
                'amount' => $totalAmount,
                'status' => $request->payment_method === 'cash' ? 'paid' : 'pending',
            ]);

            if ($request->payment_method === 'cash') {
                $payment->paid_at = now();
            }

            $payment->save();

            DB::commit();

            return response()->json([
                'message' => 'Pesanan drive thru berhasil dibuat',
                'order' => $order->load(['orderItems.menuItem', 'payment'])
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Terjadi kesalahan saat membuat pesanan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Order $order): View
    {
        if (!$order->is_drive_thru) {
            abort(404);
        }
        
        return view('admin.drive-thru.show', compact('order'));
    }

    public function updatePaymentStatus(Order $order): JsonResponse
    {
        abort_if(!$order->is_drive_thru, 404);

        try {
            DB::transaction(function () use ($order) {
                $order->payment->update([
                    'status' => 'paid',
                    'paid_at' => now()
                ]);

                $order->update(['status' => 'completed']);
            });

            return response()->json([
                'message' => 'Status pembayaran berhasil diperbarui',
                'order' => $order->fresh(['payment'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat memperbarui status pembayaran',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 