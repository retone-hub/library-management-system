<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Author;
use App\Models\Publisher;
use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => Category::inRandomOrder()->value('id'),
            'author_id' => Author::inRandomOrder()->value('id'),
            'publisher_id' => Publisher::inRandomOrder()->value('id'),
            'title' => fake()->words(3, true),
            'isbn' => fake()->isbn13(),
            'description' => fake()->paragraph(3),
            'published_year' => fake()->numberBetween(2000, now()->year),
            'stock' => fake()->numberBetween(1, 20),
            'cover' => null,
        ];
    }
}
