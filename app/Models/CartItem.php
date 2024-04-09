<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class CartItem extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'uuid';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'quantity',
        'sub_total',
    ];

    public function product() {
        return $this->hasOne(Product::class, 'uuid', 'product_uuid');
    }
}
