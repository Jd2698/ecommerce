<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
