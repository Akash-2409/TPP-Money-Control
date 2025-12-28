<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\DailyProduction;
use Illuminate\Support\Facades\Hash;

class DemoSeedCommand extends Command
{
    protected $signature = 'demo:seed';
    protected $description = 'Seed demo data for portfolio hosting';

    public function handle()
    {
        $this->info('Seeding demo data...');

        // 1️⃣ Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@demo.com'],
            [
                'name' => 'Demo Admin',
                'username' => 'Demo_Admin',
                'password' => Hash::make('password'),
            ]
        );

        // // 2️⃣ Categories
        // $categories = [
        //     'IT Grid',
        //     'Auto Grid',
        //     'PP Box Strap',
        // ];

        // foreach ($categories as $cat) {
        //     Category::firstOrCreate(['name' => $cat]);
        // }

        // 3️⃣ Products
        $products = [
            ['category' => 'IT Grid', 'name' => 'IT-3mm', 'unit' => 'pcs'],
            ['category' => 'Auto Grid', 'name' => 'Auto-12mm', 'unit' => 'kg'],
            ['category' => 'PP Box Strap', 'name' => 'PP-9mm', 'unit' => 'kg'],
        ];

        foreach ($products as $p) {
            $product = Product::firstOrCreate([
                'name' => $p['name'],
            ], [
                'main_product' => 'Packaging Grid',
                'category' => $p['category'],
                'unit' => $p['unit'],
            ]);

            // 4️⃣ Inventory
            Inventory::firstOrCreate([
                'product_id' => $product->id,
            ], [
                'opening_stock' => rand(50, 200),
                'current_stock' => rand(200, 500),
            ]);
        }

        // 5️⃣ Production (last 7 days)
        $allProducts = Product::all();

        foreach (range(0, 6) as $i) {
            foreach ($allProducts->random(2) as $prod) {
                DailyProduction::create([
                    'product_id' => $prod->id,
                    'production_qty' => rand(10, 100),
                    'date' => now()->subDays($i)->toDateString(),
                    'note' => 'Demo production entry',
                    'user_id' => $admin->id,
                ]);
            }
        }

        $this->info('✅ Demo data seeded successfully!');
    }
}
