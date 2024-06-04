<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Product extends Model
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'uuid';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'detail_images',
        'category_uuid'
    ];

    public function cart()
    {
        return $this->belongsToMany(Product::class, 'cart_items', 'product_uuid', 'cart_uuid');
    }

    public function category()
    {
        return $this->hasOne(Category::class, 'uuid', 'category_uuid');
    }
}