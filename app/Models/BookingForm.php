<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class BookingForm extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'uuid';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'bride',
        'groom',
        'bride_family_address',
        'bride_father_name',
        'bride_mother_name',
        'groom_family_address',
        'groom_father_name',
        'groom_mother_name',
        'wedding_date',
        'party_date',
        'party_name_place',
        'party_address',
        'image_design'
    ];
}