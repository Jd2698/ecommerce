<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'price',
        'description',
        'stock',
        'category_id'
    ];

    protected $appends = ['format_description', 'format_name'];

    public function formatDescription(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                return Str::limit($attributes['description'], 70,  '...');
            },
            // set: fn ($value) => Str::upper($value)
        );
    }

    public function formatName(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                return Str::limit($attributes['name'], 30,  '...');
            },
            // set: fn ($value) => Str::upper($value)
        );
    }

    // Product::with('category')->get();
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    // relación polimórfica
    public function file()
    {
        return $this->morphOne(File::class, 'fileable');
    }
}
