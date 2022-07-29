<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantStockLog;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $categories = Category::all()->pluck('id');
        // $faker = Faker::create('id_ID');
        
        // for($i = 1; $i <= 10; $i++) {
        //     $product = Product::create([
        //         'product_name'      => $faker->word,
        //         'description'       => $faker->text(100),
        //         'category_id'       => $categories[rand(0, count($categories) - 1)],
        //         'unit_id'           => 1,
        //         'default_photo'     => 'public/no-image.png',
        //         'added_by'          => 1,
        //         'last_edited_by'    => 1,
        //         'product_status'    => 1,
        //     ]);

        //     $bp = rand(1,10) * 5000;
        //     $rp = $bp + rand(1,3) * 5000;
        //     $gp = $rp + rand(1,3) * 5000;

        //     for($j = 1; $j <= 5; $j++) {
        //         $productVariant = ProductVariant::create([
        //             'product_variant_name'      => $faker->word,
        //             'product_id'                => $product->id,
        //             'color'                     => $faker->hexcolor,
        //             'stock'                     => rand(0,50),
        //             'base_price'                => $bp,
        //             'reseller_price'            => $rp,
        //             'general_price'             => $gp,
        //             'photo'                     => 'public/no-image.png',
        //             'weight'                    => 80,
        //             'added_by'                  => 1,
        //             'last_edited_by'            => 1,
        //             'product_variant_status'    => 1,
        //         ]);
    
        //         $productVariantStockLog = ProductVariantStockLog::create([
        //             'product_variant_id' => $productVariant->id,
        //             'qty_change' => $productVariant->stock,
        //             'qty_before' => 0,
        //             'qty_after' => $productVariant->stock,
        //             'date' => now(),
        //             'note' => "Entri awal varian produk",
        //             'handled_by' => 1,
        //         ]);
        //     }
        // }

        // Products
        $products = [
            [
                'product_name' => "Instan Freya",
                'category_id' => 2,
                'variants' => [
                    [
                        'name'  => "Denim",
                        'color' => "#97b8d1",
                    ],
                    [
                        'name'  => "Mustard",
                        'color' => "#ffdb58",
                    ],
                    [
                        'name'  => "Beige",
                        'color' => "#e1c699",
                    ],
                ]
            ],
            [
                'product_name' => "Crinkle Shawl",
                'category_id' => 2,
                'variants' => [
                    [
                        'name'  => "Chocomilk",
                        'color' => "#816451",
                    ],
                    [
                        'name'  => "Mustard",
                        'color' => "#ffdb58",
                    ],
                    [
                        'name'  => "Beige",
                        'color' => "#e1c699",
                    ],
                ]
            ],
            [
                'product_name' => "Madinah Square",
                'category_id' => 2,
                'variants' => [
                    [
                        'name'  => "Skyblue",
                        'color' => "#87ceeb",
                    ],
                    [
                        'name'  => "Salem",
                        'color' => "#fda89f",
                    ],
                ]
            ],
            [
                'product_name' => "Pashmina Diamond",
                'category_id' => 2,
                'variants' => [
                    [
                        'name'  => "Nude Pink",
                        'color' => "#f2dadf",
                    ],
                ]
            ],
            [
                'product_name' => "Bella Square",
                'category_id' => 2,
                'variants' => [
                    [
                        'name'  => "Army",
                        'color' => "#615e3e",
                    ],
                    [
                        'name'  => "Olive",
                        'color' => "#808000",
                    ],
                    [
                        'name'  => "Deep Grey",
                        'color' => "#686868",
                    ],
                ]
            ],
        ];

        foreach($products as $product) {
            $newProduct = Product::create([
                'product_name'      => $product['product_name'],
                'description'       => "Produk Hijab " . $product['product_name'],
                'category_id'       => 2,
                'unit_id'           => 1,
                'default_photo'     => 'public/products/' . strtolower(str_replace(' ', '-', $product['product_name'])) . '-default.jpg',
                'added_by'          => 1,
                'last_edited_by'    => 1,
                'product_status'    => 1,
            ]);

            $bp = rand(1,10) * 5000;
            $rp = $bp + rand(1,3) * 5000;
            $gp = $rp + rand(1,3) * 5000;

            foreach($product['variants'] as $variant) {
                $newProductVariant = ProductVariant::create([
                    'product_variant_name'      => $variant['name'],
                    'product_id'                => $newProduct->id,
                    'color'                     => $variant['color'],
                    'stock'                     => rand(1000,9999),
                    'base_price'                => $bp,
                    'reseller_price'            => $rp,
                    'general_price'             => $gp,
                    'photo'                     => 'public/products/' . strtolower(str_replace(' ', '-', $product['product_name'] . ' ' . $variant['name'])) . '.jpg',
                    'weight'                    => 80,
                    'added_by'                  => 1,
                    'last_edited_by'            => 1,
                    'product_variant_status'    => 1,
                ]);

                ProductVariantStockLog::create([
                    'product_variant_id' => $newProductVariant->id,
                    'qty_change' => $newProductVariant->stock,
                    'qty_before' => 0,
                    'qty_after' => $newProductVariant->stock,
                    'date' => now(),
                    'note' => "Entri awal varian produk",
                    'handled_by' => 1,
                ]);
            }
        }
    }
}
