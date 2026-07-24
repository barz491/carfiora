<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Voucher;
use App\Models\Promo;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Users
        $admin = User::create([
            'name' => 'Admin Carfiora',
            'email' => 'admin@carfiora.local',
            'phone' => '081234567890',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $cashier = User::create([
            'name' => 'Cashier Carfiora',
            'email' => 'cashier@carfiora.local',
            'phone' => '081234567891',
            'password' => bcrypt('password123'),
            'role' => 'cashier',
            'email_verified_at' => now(),
        ]);

        $customer = User::create([
            'name' => 'Customer Carfiora',
            'email' => 'customer@carfiora.local',
            'phone' => '081234567892',
            'password' => bcrypt('password123'),
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);

        User::factory(10)->create();

        // Create Categories
        $categories = [
            ['name' => 'Coffee', 'icon' => 'fas fa-coffee'],
            ['name' => 'Tea', 'icon' => 'fas fa-leaf'],
            ['name' => 'Dessert', 'icon' => 'fas fa-birthday-cake'],
            ['name' => 'Snack', 'icon' => 'fas fa-burger'],
            ['name' => 'Juice', 'icon' => 'fas fa-glass-whiskey'],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => str()->slug($category['name']),
                'description' => "This is {$category['name']} category",
                'icon' => $category['icon'],
                'sort_order' => Category::count() + 1,
                'is_active' => true,
            ]);
        }

        // Create Products
        $coffeeProducts = [
            ['name' => 'Espresso', 'price' => 25000],
            ['name' => 'Americano', 'price' => 30000],
            ['name' => 'Cappuccino', 'price' => 35000],
            ['name' => 'Latte', 'price' => 38000],
            ['name' => 'Macchiato', 'price' => 35000],
        ];

        $teaProducts = [
            ['name' => 'Green Tea', 'price' => 20000],
            ['name' => 'Black Tea', 'price' => 20000],
            ['name' => 'Matcha Latte', 'price' => 32000],
            ['name' => 'Jasmine Tea', 'price' => 22000],
        ];

        $dessertProducts = [
            ['name' => 'Chocolate Cake', 'price' => 45000],
            ['name' => 'Cheesecake', 'price' => 50000],
            ['name' => 'Tiramisu', 'price' => 48000],
            ['name' => 'Brownies', 'price' => 35000],
        ];

        $coffeeCategory = Category::where('name', 'Coffee')->first();
        foreach ($coffeeProducts as $product) {
            Product::create([
                'category_id' => $coffeeCategory->id,
                'name' => $product['name'],
                'slug' => str()->slug($product['name']),
                'description' => "Delicious {$product['name']} made from premium beans",
                'price' => $product['price'],
                'stock' => 50,
                'is_bestseller' => rand(0, 1),
                'is_new' => rand(0, 1),
                'is_active' => true,
                'rating' => rand(3, 5),
                'review_count' => rand(5, 50),
            ]);
        }

        $teaCategory = Category::where('name', 'Tea')->first();
        foreach ($teaProducts as $product) {
            Product::create([
                'category_id' => $teaCategory->id,
                'name' => $product['name'],
                'slug' => str()->slug($product['name']),
                'description' => "Premium {$product['name']} selection",
                'price' => $product['price'],
                'stock' => 40,
                'is_bestseller' => rand(0, 1),
                'is_new' => rand(0, 1),
                'is_active' => true,
                'rating' => rand(3, 5),
                'review_count' => rand(5, 30),
            ]);
        }

        $dessertCategory = Category::where('name', 'Dessert')->first();
        foreach ($dessertProducts as $product) {
            Product::create([
                'category_id' => $dessertCategory->id,
                'name' => $product['name'],
                'slug' => str()->slug($product['name']),
                'description' => "Freshly made {$product['name']}",
                'price' => $product['price'],
                'stock' => 30,
                'is_bestseller' => rand(0, 1),
                'is_new' => rand(0, 1),
                'is_active' => true,
                'rating' => rand(3, 5),
                'review_count' => rand(5, 40),
            ]);
        }

        // Create Vouchers
        Voucher::create([
            'code' => 'WELCOME10',
            'description' => 'Welcome discount 10%',
            'type' => 'percentage',
            'value' => 10,
            'min_purchase' => 50000,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addMonths(1)->toDateString(),
            'usage_limit' => 100,
            'is_active' => true,
        ]);

        Voucher::create([
            'code' => 'LUNCH50',
            'description' => 'Lunch special Rp 50.000 off',
            'type' => 'fixed',
            'value' => 50000,
            'min_purchase' => 150000,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addMonths(2)->toDateString(),
            'usage_limit' => 50,
            'is_active' => true,
        ]);

        // Create Promos
        Promo::create([
            'title' => 'Summer Coffee Sale',
            'description' => 'Get 20% off on all coffee drinks',
            'type' => 'general',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addMonths(1)->toDateString(),
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Promo::create([
            'title' => 'Dessert Special',
            'description' => 'Buy 2 desserts, get 15% off',
            'type' => 'general',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addMonths(1)->toDateString(),
            'is_active' => true,
            'sort_order' => 2,
        ]);
    }
}
