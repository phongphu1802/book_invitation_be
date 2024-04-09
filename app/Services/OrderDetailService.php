<?php

namespace App\Services;

use App\Abstracts\AbstractService;
use App\Models\OrderDetail;
use App\Models\QueryBuilders\OrderDetailQueryBuilder;

class OrderDetailService extends AbstractService
{
    protected $modelClass = OrderDetail::class;
    protected $modelQueryBuilderClass = OrderDetailQueryBuilder::class;
}