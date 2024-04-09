<?php

namespace App\Services;

use App\Abstracts\AbstractService;
use App\Models\Category;
use App\Models\QueryBuilders\CategoryQueryBuilder;

class CategoryService extends AbstractService
{
    protected $modelClass = Category::class;
    protected $modelQueryBuilderClass = CategoryQueryBuilder::class;

}