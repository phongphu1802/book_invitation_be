<?php

namespace App\Models\QueryBuilders;

use App\Abstracts\AbstractQueryBuilder;
use App\Models\SearchQueryBuilders\SearchQueryBuilder;
use App\Models\OrderDetail;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\Concerns\SortsQuery;
use Spatie\QueryBuilder\QueryBuilder;

class OrderDetailQueryBuilder extends AbstractQueryBuilder
{
    /**
     * @return string
     */
    public static function baseQuery()
    {
        return OrderDetail::class;
    }

    /**
     * @return SortsQuery|QueryBuilder
     */
    public static function initialQuery()
    {
        $modelKeyName = (new OrderDetail())->getKeyName();

        return static::for(static::baseQuery())
            ->allowedFields([
                $modelKeyName,
                'total_amount',
                'OrderDetail_date',
            ])
            ->defaultSort('created_at')
            ->allowedSorts([
                $modelKeyName,
                'total_amount',
                'OrderDetail_date',
                'created_at',
                'updated_at'
            ])
            ->allowedFilters([
                $modelKeyName,
                AllowedFilter::exact('exact__' . $modelKeyName, $modelKeyName),
                'total_amount',
                AllowedFilter::exact('exact__total_amount', 'total_amount'),
                'OrderDetail_date',
                AllowedFilter::exact('exact__OrderDetail_date', 'OrderDetail_date'),
            ]);

    }

    /**
     * @return string
     */
    public static function fillAble()
    {
        return OrderDetail::class;
    }

    /**
     * @param $search
     * @param $searchBy
     * @return mixed
     */
    public static function searchQuery($search, $searchBy)
    {
        $initialQuery = static::initialQuery();
        $baseQuery = static::fillAble();

        return SearchQueryBuilder::search($baseQuery, $initialQuery, $search, $searchBy);
    }
}