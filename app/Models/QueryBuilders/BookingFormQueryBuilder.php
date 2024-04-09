<?php

namespace App\Models\QueryBuilders;

use App\Abstracts\AbstractQueryBuilder;
use App\Models\SearchQueryBuilders\SearchQueryBuilder;
use App\Models\BookingForm;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\Concerns\SortsQuery;
use Spatie\QueryBuilder\QueryBuilder;

class BookingFormQueryBuilder extends AbstractQueryBuilder
{
    /**
     * @return string
     */
    public static function baseQuery()
    {
        return BookingForm::class;
    }

    /**
     * @return SortsQuery|QueryBuilder
     */
    public static function initialQuery()
    {
        $modelKeyName = (new BookingForm())->getKeyName();

        return static::for(static::baseQuery())
            ->allowedFields([
                $modelKeyName,
                'bride',
                'groom',
                'bride_family_address',
                'bride_father_name',
                'bride_mother_name',
                'groom_family_address',
                'groom_father_name',
                'groom_mother_name',
                'wedding_date',
                'party_date',
                'party_name_place',
                'party_address',
                'image_design'
            ])
            ->defaultSort('created_at')
            ->allowedSorts([
                $modelKeyName,
                'bride',
                'groom',
                'bride_family_address',
                'bride_father_name',
                'bride_mother_name',
                'groom_family_address',
                'groom_father_name',
                'groom_mother_name',
                'wedding_date',
                'party_date',
                'party_name_place',
                'party_address',
                'image_design',
                'created_at',
                'updated_at'
            ])
            ->allowedFilters([
                $modelKeyName,
                AllowedFilter::exact('exact__' . $modelKeyName, $modelKeyName),
                'bride',
                AllowedFilter::exact('exact__bride', 'bride'),
                'groom',
                AllowedFilter::exact('exact__groom', 'groom'),
                'bride_family_address',
                AllowedFilter::exact('exact__bride_family_address', 'bride_family_address'),
                'bride_father_name',
                AllowedFilter::exact('exact__bride_father_name', 'bride_father_name'),
                'bride_mother_name',
                AllowedFilter::exact('exact__bride_mother_name', 'bride_mother_name'),
                'groom_family_address',
                AllowedFilter::exact('exact__groom_family_address', 'groom_family_address'),
                'groom_father_name',
                AllowedFilter::exact('exact__groom_father_name', 'groom_father_name'),
                'groom_mother_name',
                AllowedFilter::exact('exact__groom_mother_name', 'groom_mother_name'),
                'party_name_place',
                AllowedFilter::exact('exact__party_name_place', 'party_name_place'),
                'party_address',
                AllowedFilter::exact('exact__party_address', 'party_address'),
            ]);

    }

    /**
     * @return string
     */
    public static function fillAble()
    {
        return BookingForm::class;
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