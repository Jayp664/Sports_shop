<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'brand',
        'description',
        'price',
        'stock',
        'is_active'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
