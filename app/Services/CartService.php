<?php

namespace App\Services;

use App\Abstracts\AbstractService;
use App\Models\Cart;

class CartService extends AbstractService
{
    protected $modelClass = Cart::class;

}