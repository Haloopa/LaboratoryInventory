<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        echo "Seeding users...\n";
        // Bikin Akun Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@stationery.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
        
        echo "Admin user created: admin@stationery.com / admin123 (role: admin)\n";

        // Akun User sementara
        User::create([
            'name' => 'Regular User',
            'email' => 'user@stationery.com',
            'password' => Hash::make('user123'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);
        
        echo "Regular user created: user@stationery.com / user123 (role: user)\n";

        // Dummy Kategori
        $categories = [
            ['name' => 'Pensil', 'description' => 'Berbagai jenis pensil'],
            ['name' => 'Pulpen', 'description' => 'Pulpen tinta hitam dan warna'],
            ['name' => 'Buku', 'description' => 'Buku tulis dan gambar'],
            ['name' => 'Penggaris', 'description' => 'Penggaris berbagai ukuran'],
            ['name' => 'Penghapus', 'description' => 'Penghapus pensil dan tinta'],
            ['name' => 'Spidol', 'description' => 'Spidol warna dan permanen'],
            ['name' => 'Stabilo', 'description' => 'Stabilo berbagai warna'],
            ['name' => 'Kertas', 'description' => 'Kertas HVS dan warna'],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
            ]);
        }

        // Dummy Produk
        $products = [
            [
                'name' => 'Pensil 2B Faber-Castell',
                'description' => 'Pensil 2B berkualitas dengan ketebalan yang pas untuk menulis dan menggambar.',
                'price' => 5000,
                'stock' => 100,
                'category_id' => 1,
            ],
            [
                'name' => 'Pulpen Pilot Balliner',
                'description' => 'Pulpen dengan tinta halus dan cepat kering, nyaman digunakan sehari-hari.',
                'price' => 8000,
                'stock' => 75,
                'category_id' => 2,
            ],
            [
                'name' => 'Buku Tulis Sinar Dunia 38 Lembar',
                'description' => 'Buku tulis berkualitas dengan kertas tidak tembus tinta, cocok untuk pelajar.',
                'price' => 12000,
                'stock' => 50,
                'category_id' => 3,
            ],
            [
                'name' => 'Penggaris Plastik 30cm',
                'description' => 'Penggaris transparan dengan skala centimeter dan inchi yang jelas.',
                'price' => 6000,
                'stock' => 80,
                'category_id' => 4,
            ],
            [
                'name' => 'Penghapus Faber-Castell',
                'description' => 'Penghapus lembut yang tidak merusak kertas, efektif menghapus pensil.',
                'price' => 4000,
                'stock' => 120,
                'category_id' => 5,
            ],
            [
                'name' => 'Spidol Whiteboard Snowman',
                'description' => 'Spidol whiteboard dengan tinta warna-warni, mudah dihapus dan tidak meninggalkan noda.',
                'price' => 10000,
                'stock' => 60,
                'category_id' => 6,
            ],
            [
                'name' => 'Stabilo Boss Pastel 5 Warna',
                'description' => 'Set stabilo dengan warna pastel yang lembut, cocok untuk marking dan dekorasi.',
                'price' => 35000,
                'stock' => 40,
                'category_id' => 7,
            ],
            [
                'name' => 'Kertas HVS A4 80gr',
                'description' => 'Rim kertas HVS ukuran A4 dengan ketebalan 80 gram, cocok untuk print dokumen.',
                'price' => 45000,
                'stock' => 30,
                'category_id' => 8,
            ],
        ];

        foreach ($products as $product) {
            Product::create([
                'name' => $product['name'],
                'slug' => Str::slug($product['name']),
                'description' => $product['description'],
                'price' => $product['price'],
                'stock' => $product['stock'],
                'category_id' => $product['category_id'],
                'is_active' => true,
            ]);
        }
    }
}