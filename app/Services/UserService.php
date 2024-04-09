<?php

namespace App\Services;

use App\Abstracts\AbstractService;
use App\Models\QueryBuilders\UserQueryBuilder;
use App\Models\User;

class UserService extends AbstractService
{
    protected $modelClass = User::class;
    protected $modelQueryBuilderClass = UserQueryBuilder::class;

}