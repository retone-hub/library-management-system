<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Author extends Model
{
    use HasFactory, SoftDeletes; //hasfactory create data dummy example like 1k data

    protected $fillable = [
        'name',
        'nationality',
        'biography',
        'birth_date',
    ];

    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }
}
