<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'Novel',
            'description' => 'Buku cerita fiksi',
        ]);

        Category::create([
            'name' => 'Teknologi',
            'description' => 'Buku teknologi dan komputer',
        ]);

        Category::create([
            'name' => 'Komik',
            'description' => 'Buku Bergambar',
        ]);

        Category::create([
            'name' => 'Sejarah',
            'description' => 'Buku Sejarah',
        ]);
    }
}
