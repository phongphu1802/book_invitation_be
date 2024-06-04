<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Order extends Model
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'uuid';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'total_amount',
        'order_date',
        'user_uuid',
    ];

    public function order_details()
    {
        return $this->hasMany(OrderDetail::class, 'order_uuid', 'uuid');
    }

    public function order_user()
    {
        return $this->hasOne(User::class, "uuid", "user_uuid");
    }
}