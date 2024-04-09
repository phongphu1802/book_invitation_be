<?php

namespace App\Services;

use App\Abstracts\AbstractService;
use App\Models\BookingForm;
use App\Models\QueryBuilders\BookingFormQueryBuilder;

class BookingFormService extends AbstractService
{
    protected $modelClass = BookingForm::class;
    protected $modelQueryBuilderClass = BookingFormQueryBuilder::class;
}