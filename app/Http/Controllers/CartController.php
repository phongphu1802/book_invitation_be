<?php

namespace App\Http\Controllers;

use App\Abstracts\AbstractRestAPIController;
use App\Http\Requests\IndexRequest;
use App\Http\Requests\CartCreateRequest;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\CartItem;
use App\Services\CartItemService;
use App\Services\CartService;
use App\Services\ProductService;

class CartController extends AbstractRestAPIController
{
    protected $productService, $cartItemService;
    public function __construct(CartService $service, ProductService $productService, CartItemService $cartItemService)
    {
        $this->service = $service;
        $this->resourceClass = CartResource::class;
        $this->productService = $productService;
        $this->cartItemService = $cartItemService;
        $this->indexRequest = IndexRequest::class;
    }

    public function index()
    {
        $cartCurrent = $this->service->findOneWhere(['user_uuid' => auth()->user()->getKey()]);
        if (!$cartCurrent) {
            $cartCurrent = $this->service->create(['user_uuid' => auth()->user()->getKey()]);
        }
        return $this->sendOkJsonResponse($this->service->resourceToData($this->resourceClass, $cartCurrent));
    }

    public function store(CartCreateRequest $request)
    {
        $cartCurrent = $this->service->findOneWhere(['user_uuid' => auth()->user()->getKey()]);
        if (!$cartCurrent) {
            $cartCurrent = $this->service->create(['user_uuid' => auth()->user()->getKey()]);
        }

        //Find product in cart
        $cartItemsCurrent = $this->cartItemService->findOneWhere([['product_uuid', $request->product_uuid], ['cart_uuid', $cartCurrent->getKey()]]);

        if ($cartItemsCurrent) {
            //quantityUpdate
            $quantityUpdate = $cartItemsCurrent->quantity + $request->quantity;

            //subTotalUpdate
            $subTotalUpdate = $this->productService->findOneById($request->product_uuid)->price * $quantityUpdate;

            $this->cartItemService->update($cartItemsCurrent, ['quantity' => $quantityUpdate, 'sub_total' => $subTotalUpdate]);

            return $this->sendOkJsonResponse($this->service->resourceToData($this->resourceClass, $cartCurrent));
        }

        $temp[$request->product_uuid] = [
            'quantity' => $request->quantity,
            'sub_total' => $this->productService->findOneById($request->product_uuid)->price * $request->quantity,
        ];

        $cartCurrent->product()->attach($temp);

        return $this->sendOkJsonResponse($this->service->resourceToData($this->resourceClass, $cartCurrent));
    }

    public function destroyCartItem($id)
    {
        $cartCurrent = $this->service->findOneWhereOrFail(['user_uuid' => auth()->user()->getKey()]);

        $cartCurrent->product()->detach($id);

        return $this->sendOkJsonResponse($this->service->resourceToData($this->resourceClass, $cartCurrent));
    }

    public function destroyCart($id)
    {
        $cartCurrent = $this->service->findOrFailById($id);

        if ($cartCurrent->product)
            $cartCurrent->product()->detach([]);

        $this->service->destroy($id);

        return $this->sendOkJsonResponse();
    }
}