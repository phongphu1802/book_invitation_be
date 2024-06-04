<?php

namespace App\Services;

use App\Abstracts\AbstractService;
use App\Http\Requests\ProductStatisticsRequest;
use App\Models\OrderDetail;
use App\Models\QueryBuilders\OrderDetailQueryBuilder;

class OrderDetailService extends AbstractService
{
    protected $modelClass = OrderDetail::class;
    protected $modelQueryBuilderClass = OrderDetailQueryBuilder::class;

    public function productStatistics(ProductStatisticsRequest $request)
    {

        $products = OrderDetail::query()
            ->select('product_uuid', OrderDetail::raw('SUM(quantity) as total_product'))
            ->where('created_at', ">", $request->from_date)
            ->where('created_at', "<", $request->to_date)
            ->groupBy('product_uuid')
            ->orderByDesc('total_product')
            ->take(10)
            ->get();

        return $products;
    }
}