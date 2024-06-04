<?php

namespace App\Http\Controllers;

use App\Abstracts\AbstractRestAPIController;
use App\Http\Controllers\Traits\DestroyTrait;
use App\Http\Controllers\Traits\EditTrait;
use App\Http\Controllers\Traits\IndexTrait;
use App\Http\Controllers\Traits\ShowTrait;
use App\Http\Requests\IndexRequest;
use App\Http\Requests\OrderCreateRequest;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderResourceCollection;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Services\OrderService;
use App\Services\ProductService;
use Carbon\Carbon;

class OrderController extends AbstractRestAPIController
{
    protected $productService;
    use IndexTrait, ShowTrait, DestroyTrait, EditTrait;
    public function __construct(OrderService $service, ProductService $productService)
    {
        $this->service = $service;
        $this->productService = $productService;
        $this->resourceCollectionClass = OrderResourceCollection::class;
        $this->resourceClass = OrderResource::class;
        $this->indexRequest = IndexRequest::class;
    }

    public function store(OrderCreateRequest $service)
    {
        $order = Order::create([
            'order_date' => new Carbon($service->order_date),
            'total_amount' => $service->total_amount,
            'user_uuid' => auth()->user()->getKey(),
        ]);

        $order_detail = $service->product;

        foreach ($order_detail as $item) {
            OrderDetail::create([
                'order_uuid' => $order->getKey(),
                'product_uuid' => $item['product_uuid'],
                'quantity' => $item['quantity'],
                'sub_total' => $this->productService->findOneById($item['product_uuid'])->price * $item['quantity'],
            ]);
        }

        ;
        return $this->sendOkJsonResponse(['data' => OrderResource::make($order)]);
    }
}