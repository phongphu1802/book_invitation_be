<?php

namespace App\Abstracts;

use App\Enums\DashboardEnum;
use App\Http\Requests\DasboardStatisticRequest;
use GuzzleHttp\Client;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

abstract class AbstractService
{
    protected $modelClass;

    protected $modelQueryBuilderClass;

    /**
     * @var Application|mixed
     */
    protected $model;

    /**
     * AbstractService constructor.
     */
    public function __construct()
    {
        $this->model = app($this->modelClass);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findOrFailById($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * @param $resourceCollectionClass
     * @param $models
     * @return mixed
     */
    public function resourceCollectionToData($resourceCollectionClass, $models)
    {
        return app($resourceCollectionClass, ['resource' => $models])
            ->toResponse(app('Request'))
            ->getData(true);
    }

    /**
     * @param $resourceClass
     * @param $model
     * @return mixed
     */
    public function resourceToData($resourceClass, $model)
    {
        return app($resourceClass, ['resource' => $model])
            ->toResponse(app('Request'))
            ->getData(true);
    }

    /**
     * @param $perPage
     * @param $page
     * @param $columns
     * @param $pageName
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getCollectionWithPagination($perPage = 15, $page = 1, $columns = '*', $pageName = 'page')
    {
        $perPage = request()->get('per_page', $perPage);
        $page = request()->get('page', $page);
        $columns = request()->get('columns', $columns);
        $pageName = request()->get('page_name', $pageName);
        $search = request()->get('search', '');
        $searchBy = request()->get('search_by', '');


        return $this->modelQueryBuilderClass::searchQuery($search, $searchBy)
            ->paginate($perPage, $columns, $pageName, $page);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        $model = $this->findOrFailById($id);

        return $model->delete();
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create($data = [])
    {
        return $this->model->create($data);
    }

    /**
     * @param $model
     * @param array $data
     * @return mixed
     */
    public function update($model, $data = [])
    {
        return $model->update($data);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findOneById($id)
    {
        return $this->model->find($id);
    }

    /**
     * @param $where
     * @return Builder|Model|object|null
     */
    public function findOneWhere($where)
    {
        return $this->model->where($where)->first();
    }

    /**
     * @param $where
     * @return mixed
     */
    public function findOneWhereOrFail($where)
    {
        return $this->model->where($where)->firstOrFail();
    }

    /**
     * @param $where
     * @param array|null $select
     * @return mixed
     */
    public function findAllWhere($where = null, array $select = null, $distinct = false)
    {
        if ($distinct) {
            return $this->model->select($select ?? '*')->where($where)->distinct()->get();
        }

        return $this->model->select($select ?? '*')->where($where)->get();
    }

    public function firstOrCreate($where, $data)
    {
        return $this->model->firstOrCreate($where, $data);
    }

    /**
     * @param string|null $whereColumn
     * @param null $arrayValues
     * @param array|null $select
     * @return mixed
     */
    public function findAllWhereIn(string $whereColumn = null, $arrayValues = null, array $select = null)
    {
        return $this->model->select($select ?? '*')->whereIn($whereColumn, $arrayValues)->get();
    }

    public function collectionPagination($results, $perPage, $page = null)
    {
        $page = $page ?: (LengthAwarePaginator::resolveCurrentPage() ?: 1);

        $results = $results instanceof Collection ? $results : Collection::make($results);

        return new LengthAwarePaginator($results->forPage($page, $perPage)->values(), $results->count(), $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
            'pageName' => 'page',
        ]);
    }

    public function getIndexRequest($request)
    {
        return [
            'per_page' => $request->get('per_page', 15),
            'page' => $request->get('page', 1),
            'columns' => $request->get('columns', '*'),
            'page_name' => $request->get('page_name', 'page'),
            'search' => $request->get('search', ''),
            'search_by' => $request->get('search_by', ''),
        ];
    }

    /**
     * @param $request
     * @param $where
     * @return mixed
     */
    public function getCollectionWithPaginationByCondition($request, $where = null)
    {
        $indexRequest = $this->getIndexRequest($request);

        return $this->modelQueryBuilderClass::searchQuery($indexRequest['search'], $indexRequest['search_by'])
            ->where($where)
            ->paginate($indexRequest['per_page'], $indexRequest['columns'], $indexRequest['page_name'], $indexRequest['page']);
    }

    /**
     * @param $request
     * @param $where
     * @return mixed
     */
    public function getCollectionWithPaginationWhereNotIn($request, $where = null, $notIn = [])
    {
        $indexRequest = $this->getIndexRequest($request);

        return $this->modelQueryBuilderClass::searchQuery($indexRequest['search'], $indexRequest['search_by'])
            ->where($where)
            ->whereNotIn('uuid', $notIn)
            ->paginate($indexRequest['per_page'], $indexRequest['columns'], $indexRequest['page_name'], $indexRequest['page']);
    }

    /**
     * @param $request
     * @param $select
     * @return mixed
     */
    public function getPluckModel($request, $select = [])
    {
        $indexRequest = $this->getIndexRequest($request);

        return $this->modelQueryBuilderClass::searchQuery($indexRequest['search'], $indexRequest['search_by'])
            ->select($select)
            ->paginate($indexRequest['per_page'], $indexRequest['columns'], $indexRequest['page_name'], $indexRequest['page']);
    }

    public function getCollectionByUserIdAndAppIdWithPagination($request)
    {
        $indexRequest = $this->getIndexRequest($request);

        return $this->modelQueryBuilderClass::searchQuery($indexRequest['search'], $indexRequest['search_by'])
            ->where([
                ['user_uuid', auth()->userId()]
            ])
            ->paginate($indexRequest['per_page'], $indexRequest['columns'], $indexRequest['page_name'], $indexRequest['page']);
    }

    public function findOneWhereOrFailByUserUuidAndAppId($id)
    {
        return $this->model->where([
            ['user_uuid', auth()->userId()],
            ['uuid', $id],
        ])->firstOrFail();
    }

    public function destroyByUserIdAndAppId($id)
    {
        $model = $this->findOneWhereOrFailByUserUuidAndAppId($id);

        return $model->delete();
    }

    public function listRangeTime(DasboardStatisticRequest $request)
    {
        if ($request->type === DashboardEnum::DAY->value) {
            $array = $this->dateRange($request->from_date, $request->to_date, "+1 day", "Y-m-d");
            $object = [];
            for ($i = 0; $i < count($array); $i++) {
                array_push($object, (Object) [
                    'name' => $array[$i],
                    'value' => 0,
                ]);
            }
            return $object;
        }

        if ($request->type === DashboardEnum::MONTH->value) {
            $array = $this->dateRange($request->from_date, $request->to_date, "+1 month", "Y-m");
            $object = [];
            for ($i = 0; $i < count($array); $i++) {
                array_push($object, (Object) [
                    'name' => $array[$i],
                    'value' => 0,
                ]);
            }
            return $object;
        }

        if ($request->type === DashboardEnum::YEAR->value) {
            $array = $this->dateRange($request->from_date, $request->to_date, "+1 year", "Y");
            $object = [];
            for ($i = 0; $i < count($array); $i++) {
                array_push($object, (Object) [
                    'name' => $array[$i],
                    'value' => 0,
                ]);
            }
            return $object;
        }

        return $request;
    }

    /**
     * Creating date collection between two dates
     *
     * <code>
     * <?php
     * # Example 1
     * date_range("2014-01-01", "2014-01-20", "+1 day", "m-d-Y");
     *
     * # Example 2. you can use even time
     * date_range("01:00:00", "23:00:00", "+1 hour", "H:i:s");
     * </code>
     *
     * @param string since any date, time or datetime format
     * @param string until any date, time or datetime format
     * @param string step
     * @param string date of output format
     * @return array
     */
    function dateRange($first, $last, $step = '+1 day', $output_format = 'd-m-Y')
    {

        $dates = array();
        $current = strtotime($first);
        $last = strtotime($last);

        while ($current <= $last) {
            $dates[] = date($output_format, $current);
            $current = strtotime($step, $current);
        }

        return $dates;
    }

    /**
     * Merge two arrays of objects and check uniqueness by key and sort in ascending or descending order
     *
     * <code>
     * <?php
     * # Example 1 sort ascending
     * mergeUniqueSortProperty([], [], "key", "SORT_ASC");
     *
     * # Example 2. sort decrease
     * mergeUniqueSortProperty([], [], "key", "SORT_DESC");
     * </code>
     *
     * @param array merge first
     * @param array array merge second
     * @param string property unique
     * @param string type sort
     * @return array
     */
    function mergeUniqueSortProperty($arrayOne = [], $arrrayTrue = [], $property, $typeSort = 'SORT_ASC')
    {
        $array = array_merge($arrayOne, $arrrayTrue);

        $tempArray = array_unique(array_column($array, $property));

        $moreUniqueArray = array_values(array_intersect_key($array, $tempArray));

        #sort asc for day
        if ($typeSort === 'SORT_ASC')
            usort($moreUniqueArray, function ($a, $b) {
                if ($a->name == $b->name)
                    return 0;
                return (strtotime($a->name) > strtotime($b->name)) ? 1 : -1;
            });

        if ($typeSort === 'SORT_DESC')
            usort($moreUniqueArray, function ($a, $b) {
                if ($a->name == $b->name)
                    return 0;
                return (strtotime($a->name) < strtotime($b->name)) ? 1 : -1;
            });

        return $moreUniqueArray;
    }
}