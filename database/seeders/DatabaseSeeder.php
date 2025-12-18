<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Image;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create sample products
        $products = [
            [
                'name' => 'Remera negra clásica',
                'brand' => 'Marca Prueba A',
                'description' => 'Una remera negra clásica de algodón.',
                'price' => 15000.99,
                'category' => 'remeras',
                'gender' => 'unisex',
                'discount_percentage' => 10,
            ],
            [
                'name' => 'Pantalón vaquero azul',
                'brand' => 'Marca Prueba B',
                'description' => 'Pantalón vaquero azul de corte recto.',
                'price' => 35000.50,
                'category' => 'pantalones',
                'gender' => 'hombre',
                'discount_percentage' => 0,
            ],
            [
                'name' => 'Zapatillas deportivas',
                'brand' => 'Marca Prueba C',
                'description' => 'Zapatillas deportivas cómodas para correr.',
                'price' => 50000.00,
                'category' => 'calzados',
                'gender' => 'mujer',
                'discount_percentage' => 0,
            ],
            [
                'name' => 'Buzo con capucha',
                'brand' => 'Marca Prueba D',
                'description' => 'Buzo con capucha de felpa suave.',
                'price' => 25000.75,
                'category' => 'buzos',
                'gender' => 'unisex',
                'discount_percentage' => 0,
            ]
        ];

        for ($i = 0; $i < count($products); $i++) {
            $product = Product::create($products[$i]);

            if($product->category === 'calzados' ){
                $sizes = ['38', '39', '40', '41', '42'];
                foreach ($sizes as $size) {
                    Stock::create([
                        'product_id' => $product->product_id,
                        'stock_quantity' => rand(10, 100),
                        'size' => $size,
                    ]);
                }
            }else{
                // Create sample stocks for each product
                $sizes = ['S', 'M', 'L', 'XL'];
                foreach ($sizes as $size) {
                    Stock::create([
                        'product_id' => $product->product_id,
                        'stock_quantity' => rand(10, 100),
                        'size' => $size,
                    ]);
                }
            }
        }

        Image::create([
            'product_id' => 1,
            'image_url' => 'https://i.ibb.co/xtWQ4pqq/remera-negra-bgless-front.png',
            'is_main' => true,
        ]);

        Image::create([
            'product_id' => 1,
            'image_url' => 'https://i.ibb.co/gFvwwLJD/remera-negra-bgless-back.png',
            'is_main' => false,
        ]);

        Image::create([
            'product_id' => 2,
            'image_url' => 'https://i.ibb.co/gLgtZJzX/blue-jeans-bgless-front.png',
            'is_main' => true,
        ]);

        Image::create([
            'product_id' => 2,
            'image_url' => 'https://i.ibb.co/0pCDSmnK/blue-jeans-bgless-back.png',
            'is_main' => false,
        ]);
    }
}
