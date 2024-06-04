<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Cart extends Model
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'uuid';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_uuid',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'uuid');
    }

    public function cart_items()
    {
        return $this->hasMany(CartItem::class, 'cart_uuid', 'uuid');
    }

    public function product()
    {
        return $this->belongsToMany(Product::class, 'cart_items', 'cart_uuid', 'product_uuid');
    }


}