<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Optional: create a category if none exists
        $category = Category::firstOrCreate(
            ['name' => 'General'],
            ['name' => 'General']
        );

        $products = [
            ['name' => 'Blue Ball Pen',        'cost' => 10,   'sell' => 20,   'stock' => 100],
            ['name' => 'Red Ball Pen',         'cost' => 10,   'sell' => 20,   'stock' => 100],
            ['name' => 'Black Ball Pen',       'cost' => 10,   'sell' => 20,   'stock' => 100],
            ['name' => 'A4 Notebook 100pg',    'cost' => 80,   'sell' => 120,  'stock' => 50],
            ['name' => 'A5 Notebook 60pg',     'cost' => 40,   'sell' => 70,   'stock' => 60],
            ['name' => 'HB Pencil',            'cost' => 5,    'sell' => 10,   'stock' => 200],
            ['name' => '2B Pencil',            'cost' => 7,    'sell' => 15,   'stock' => 150],
            ['name' => 'Eraser (small)',       'cost' => 5,    'sell' => 10,   'stock' => 100],
            ['name' => 'Eraser (large)',       'cost' => 8,    'sell' => 15,   'stock' => 80],
            ['name' => 'Sharpener',            'cost' => 10,   'sell' => 20,   'stock' => 70],
            ['name' => 'Ruler 12 inch',        'cost' => 15,   'sell' => 30,   'stock' => 90],
            ['name' => 'Ruler 6 inch',         'cost' => 10,   'sell' => 20,   'stock' => 90],
            ['name' => 'Geometry Box',         'cost' => 150,  'sell' => 250,  'stock' => 40],
            ['name' => 'Marker (Black)',       'cost' => 25,   'sell' => 50,   'stock' => 60],
            ['name' => 'Marker (Blue)',        'cost' => 25,   'sell' => 50,   'stock' => 60],
            ['name' => 'Highlighter (Yellow)', 'cost' => 30,   'sell' => 60,   'stock' => 50],
            ['name' => 'Highlighter (Green)',  'cost' => 30,   'sell' => 60,   'stock' => 50],
            ['name' => 'Stapler Small',        'cost' => 100,  'sell' => 180,  'stock' => 30],
            ['name' => 'Stapler Large',        'cost' => 200,  'sell' => 350,  'stock' => 20],
            ['name' => 'Staples Box',          'cost' => 20,   'sell' => 40,   'stock' => 100],
            ['name' => 'Paper Clips (100pc)',  'cost' => 30,   'sell' => 60,   'stock' => 80],
            ['name' => 'Glue Stick',           'cost' => 25,   'sell' => 50,   'stock' => 70],
            ['name' => 'Glue Bottle 100ml',    'cost' => 60,   'sell' => 100,  'stock' => 40],
            ['name' => 'Scissors Small',       'cost' => 50,   'sell' => 90,   'stock' => 50],
            ['name' => 'Scissors Large',       'cost' => 90,   'sell' => 160,  'stock' => 30],
            ['name' => 'Cello Tape',           'cost' => 20,   'sell' => 40,   'stock' => 100],
            ['name' => 'File Folder',          'cost' => 40,   'sell' => 80,   'stock' => 60],
            ['name' => 'Register 200pg',       'cost' => 120,  'sell' => 200,  'stock' => 40],
            ['name' => 'Sticky Notes',         'cost' => 30,   'sell' => 60,   'stock' => 80],
            ['name' => 'Correction Pen',       'cost' => 40,   'sell' => 80,   'stock' => 50],
        ];

        foreach ($products as $i => $p) {
            Product::updateOrCreate(
                ['sku' => 'PRD-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT)],
                [
                    'name'            => $p['name'],
                    'category_id'     => $category->id,
                    'unit'            => 'pcs',
                    'barcode'         => '20000000' . str_pad($i + 1, 5, '0', STR_PAD_LEFT),
                    'description'     => 'Sample product: ' . $p['name'],
                    'cost_price'      => $p['cost'],
                    'selling_price'   => $p['sell'],
                    'wholesale_price' => round($p['sell'] * 0.9, 2),
                    'stock'           => $p['stock'],
                    'minimum_stock'   => 10,
                    'tax'             => 0,
                    'discount'        => 0,
                    'status'          => 'active',
                ]
            );
        }

        $this->command->info('✅ 30 sample products seeded successfully.');
    }
}