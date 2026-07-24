<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $subtotal = fake()->numberBetween(50000, 300000);
        $tax = ($subtotal * 10) / 100;
        $serviceCharge = 2000;
        $total = $subtotal + $tax + $serviceCharge;

        return [
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'order_number' => 'ORD-' . strtoupper(Str::random(8)),
            'type' => fake()->randomElement(['dine_in', 'take_away']),
            'table_number' => fake()->optional()->numberBetween(1, 20),
            'customer_name' => fake()->name(),
            'customer_phone' => fake()->phoneNumber(),
            'notes' => fake()->optional()->sentence(),
            'subtotal' => $subtotal,
            'tax' => $tax,
            'service_charge' => $serviceCharge,
            'discount' => 0,
            'total' => $total,
            'status' => fake()->randomElement(['pending', 'processing', 'cooking', 'ready', 'completed']),
            'completed_at' => fake()->optional()->dateTime(),
        ];
    }
}
