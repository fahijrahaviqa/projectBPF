<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\User;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = User::where('role', 'customer')->get();
        $menuItems = MenuItem::all();

        foreach ($customers as $customer) {
            DB::beginTransaction();
            
            try {
                // Create Order
                $order = Order::create([
                    'order_number' => Order::generateOrderNumber(),
                    'user_id' => $customer->id,
                    'status' => 'completed',
                    'total_amount' => 0,
                    'notes' => 'Pesanan contoh untuk ' . $customer->name
                ]);

                $totalAmount = 0;

                // Create Order Items
                foreach ($menuItems->random(3) as $menuItem) {
                    $quantity = rand(1, 3);
                    $price = $menuItem->price;
                    $subtotal = $price * $quantity;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'menu_item_id' => $menuItem->id,
                        'quantity' => $quantity,
                        'price' => $price,
                        'subtotal' => $subtotal,
                        'special_instructions' => 'Instruksi khusus untuk ' . $menuItem->name
                    ]);

                    $totalAmount += $subtotal;
                }

                // Update Order Total
                $order->update(['total_amount' => $totalAmount]);

                // Create Payment
                Payment::create([
                    'order_id' => $order->id,
                    'payment_number' => Payment::generatePaymentNumber(),
                    'payment_method' => 'cash',
                    'status' => 'paid',
                    'amount' => $totalAmount,
                    'paid_at' => now()
                ]);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        }
    }
} 