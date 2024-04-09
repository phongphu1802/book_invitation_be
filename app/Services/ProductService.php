<?php

namespace App\Services;

use App\Abstracts\AbstractService;
use App\Models\Product;
use App\Models\QueryBuilders\ProductQueryBuilder;

class ProductService extends AbstractService
{
    protected $modelClass = Product::class;
    protected $modelQueryBuilderClass = ProductQueryBuilder::class;
}