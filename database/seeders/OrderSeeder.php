<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $user = User::first();

        if (!$user) {
            $this->command->info('No users found. Please run UserSeeder first.');
            return;
        }

        $orders = [
            [
                'user_id' => $user->id,
                'order_number' => 'ORD-2026-001',
                'status' => 'pending',
                'payment_status' => 'pending',
                'total_amount' => 2500.00,
                'shipping_name' => 'John Doe',
                'shipping_phone' => '091234567890',
                'shipping_address' => '123 Main Street Pianton, Butuan City',
                'delivery_date' => '2026-05-01',
                'delivery_time' => 'morning',
                'delivery_notes' => 'Leave at door',
            ],
            [
                'user_id' => $user->id,
                'order_number' => 'ORD-2026-002',
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_receipt' => 'receipts/demo-receipt.jpg',
                'total_amount' => 1800.00,
                'shipping_name' => 'Jane Smith',
                'shipping_phone' => '091234567891',
                'shipping_address' => '456 Oak Avenue Bonbon, Butuan City',
                'delivery_date' => '2026-05-02',
                'delivery_time' => 'afternoon',
                'delivery_notes' => 'Call when arriving',
            ],
            [
                'user_id' => $user->id,
                'order_number' => 'ORD-2026-003',
                'status' => 'completed',
                'payment_status' => 'verified',
                'total_amount' => 3200.00,
                'shipping_name' => 'Bob Wilson',
                'shipping_phone' => '091234567892',
                'shipping_address' => '789 Pine Road Tiniwisan, Butuan City',
                'delivery_date' => '2026-04-28',
                'delivery_time' => 'morning',
                'delivery_notes' => '',
            ],
            [
                'user_id' => $user->id,
                'order_number' => 'ORD-2026-004',
                'status' => 'cancelled',
                'payment_status' => 'rejected',
                'total_amount' => 950.00,
                'shipping_name' => 'Alice Brown',
                'shipping_phone' => '091234567893',
                'shipping_address' => '321 Maple Lane Waraw, Butuan City',
                'delivery_date' => '2026-05-03',
                'delivery_time' => 'any',
                'delivery_notes' => 'Customer requested cancellation',
            ],
        ];

        foreach ($orders as $order) {
            Order::create($order);
        }

        $this->command->info('Created 4 test orders.');
    }
}