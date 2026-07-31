<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'isbn' => $this->isbn,
            'description' => $this->description,
            'published_year' => $this->published_year,
            'stock' => $this->stock,
            'cover' => $this->cover,
            'category' => [
                'id' => $this->category->id,
                'name' => $this->category->name,
            ],
            'author' => [
                'id' => $this->author->id,
                'name' => $this->author->name,
            ],
            'publisher' => [
                'id' => $this->publisher->id,
                'name' => $this->publisher->name,
            ],
        ];
    }
}
