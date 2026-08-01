<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BorrowingResource extends JsonResource
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

            'borrowed_at' => $this->borrowed_at,

            'due_date' => $this->due_date,

            'returned_at' => $this->returned_at,

            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],

            'book' => [
                'id' => $this->book->id,
                'title' => $this->book->title,
            ],
        ];
    }
}
