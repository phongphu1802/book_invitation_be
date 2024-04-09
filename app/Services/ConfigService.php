<?php

namespace App\Services;

use App\Abstracts\AbstractService;
use App\Models\Config;
use App\Models\QueryBuilders\ConfigQueryBuilder;

class ConfigService extends AbstractService
{
    protected $modelClass = Config::class;
    protected $modelQueryBuilderClass = ConfigQueryBuilder::class;

}