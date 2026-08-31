<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Elektronik', 'icon' => 'ti-device-laptop', 'children' => [
                'Handphone & Tablet',
                'Laptop & Komputer',
                'Audio & Headphone',
                'Kamera & Aksesoris',
            ]],
            ['name' => 'Kendaraan', 'icon' => 'ti-car', 'children' => [
                'Mobil',
                'Motor',
                'Aksesoris Kendaraan',
            ]],
            ['name' => 'Properti', 'icon' => 'ti-home', 'children' => [
                'Rumah',
                'Kos & Kontrakan',
                'Tanah',
            ]],
            ['name' => 'Fashion', 'icon' => 'ti-shirt', 'children' => [
                'Pakaian Pria',
                'Pakaian Wanita',
                'Sepatu & Sandal',
                'Tas & Dompet',
            ]],
            ['name' => 'Rumah Tangga', 'icon' => 'ti-tools-kitchen-2', 'children' => [
                'Perabotan',
                'Peralatan Dapur',
                'Dekorasi',
            ]],
            ['name' => 'Hobi & Olahraga', 'icon' => 'ti-ball-football', 'children' => [
                'Alat Olahraga',
                'Koleksi & Hobi',
                'Buku & Majalah',
            ]],
            ['name' => 'Lainnya', 'icon' => 'ti-dots', 'children' => []],
        ];

        foreach ($categories as $i => $cat) {
            $parent = Category::create([
                'parent_id'  => null,
                'name'       => $cat['name'],
                'slug'       => Str::slug($cat['name']),
                'icon'       => $cat['icon'],
                'sort_order' => $i + 1,
            ]);

            foreach ($cat['children'] as $j => $child) {
                Category::create([
                    'parent_id'  => $parent->id,
                    'name'       => $child,
                    'slug'       => Str::slug($child),
                    'icon'       => null,
                    'sort_order' => $j + 1,
                ]);
            }
        }
    }
}
