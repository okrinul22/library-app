<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'isbn',
        'published_year',
        'genre',
        'description',
        'price',
        'stock',
        'cover_image',
        'is_free',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'published_year' => 'integer',
            'stock' => 'integer',
            'is_free' => 'boolean',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($book) {
            if ($book->is_free && (!$book->price || $book->price > 0)) {
                $book->price = 0;
            }
        });
    }

    public function contents(): HasMany
    {
        return $this->hasMany(Content::class);
    }

    public function content()
    {
        return $this->hasOne(Content::class);
    }

    public function firstChapter()
    {
        return $this->hasOne(Content::class)->where('status', 'published')->orderBy('chapter', 'asc');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
