<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ListingSeeder extends Seeder
{
    public function run(): void
    {
        $seller   = User::where('role', 'seller')->first();
        $category = Category::whereNotNull('parent_id')->first();

        if (! $seller || ! $category) return;

        $listings = [
            [
                'title'         => 'iPhone 13 Pro Max 256GB Mulus',
                'description'   => 'Jual iPhone 13 Pro Max 256GB warna Graphite. Kondisi mulus 98%, no minus. Lengkap dus, charger original. Beli di iBox 2022.',
                'price'         => 11500000,
                'is_negotiable' => true,
                'condition'     => 'used',
                'stock'         => null,
                'status'        => 'active',
                'address'       => 'Jl. Merdeka No. 12, dekat kampus Unsulbar, Majene',
            ],
            [
                'title'         => 'Laptop ASUS VivoBook 14 Core i5 Gen 11',
                'description'   => 'Laptop ASUS VivoBook 14 A415EA. Processor Core i5-1135G7, RAM 8GB, SSD 512GB. Masih bergaransi resmi sampai 2025.',
                'price'         => 7200000,
                'is_negotiable' => false,
                'condition'     => 'used',
                'stock'         => 1,
                'status'        => 'active',
                'address'       => 'Majene, Sulawesi Barat',
            ],
            [
                'title'         => 'Headphone Sony WH-1000XM4 ANC',
                'description'   => 'Sony WH-1000XM4 noise cancelling. Kondisi sangat baik, jarang dipakai. Lengkap dengan case dan kabel.',
                'price'         => 2800000,
                'is_negotiable' => true,
                'condition'     => 'used',
                'stock'         => null,
                'status'        => 'active',
                'address'       => 'Majene, Sulawesi Barat',
            ],
        ];

        foreach ($listings as $data) {
            Listing::create(array_merge($data, [
                'user_id'     => $seller->id,
                'category_id' => $category->id,
                'slug'        => Str::slug($data['title']) . '-' . Str::random(6),
                'view_count'  => rand(5, 120),
            ]));
        }
    }
}
