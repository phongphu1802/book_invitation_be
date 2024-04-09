<?php

namespace App\Http\Controllers;

use App\Abstracts\AbstractRestAPIController;
use App\Http\Requests\IndexRequest;
use App\Http\Requests\CartCreateRequest;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Services\CartService;
use App\Services\ProductService;

class CartController extends AbstractRestAPIController
{
    protected $productService;
    public function __construct(CartService $service, ProductService $productService)
    {
        $this->service = $service;
        $this->productService = $productService;
        $this->resourceClass = CartResource::class;
        $this->indexRequest = IndexRequest::class;
    }

    public function index()
    {
        $cart_current = $this->service->findOneWhereOrFail(['user_uuid' => auth()->user()->getKey()]);
        return $this->sendOkJsonResponse(['data' => CartResource::make($cart_current)]);
    }

    public function store(CartCreateRequest $request)
    {
        $cart_current = $this->service->findOneWhereOrFail(['user_uuid' => auth()->user()->getKey()]);
        if (!$cart_current) {
            $cart_current = Cart::create(['user_uuid' => auth()->user()->getKey()]);
        }

        $cart_detail = $request->product;

        foreach ($cart_detail as $item) {
            $temp[$item['product_uuid']] = [
                'quantity' => $item['quantity'],
                'sub_total' => $this->productService->findOneById($item['product_uuid'])->price * $item['quantity'],
            ];
        }

        $cart_current->product()->sync($temp);

        return $this->sendOkJsonResponse(['data' => CartResource::make($cart_current)]);
    }

    public function destroyCartItem($id)
    {
        $cart_current = $this->service->findOneWhereOrFail(['user_uuid' => auth()->user()->getKey()]);

        $cart_current->product()->detach($id);

        return $this->sendOkJsonResponse(['data' => CartResource::make($cart_current)]);
    }

    public function destroyCart($id)
    {
        $cart_current = $this->service->findOrFailById($id);

        if ($cart_current->product)
            $cart_current->product()->detach([]);

        $this->service->destroy($id);

        return $this->sendOkJsonResponse();
    }
}