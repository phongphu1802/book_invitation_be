<?php

namespace App\Services;

use App\Abstracts\AbstractService;
use App\Enums\DashboardEnum;
use App\Http\Requests\DasboardStatisticRequest;
use App\Http\Requests\UserStatisticRequest;
use App\Models\Order;
use App\Models\QueryBuilders\OrderQueryBuilder;

class OrderService extends AbstractService
{
    protected $modelClass = Order::class;
    protected $modelQueryBuilderClass = OrderQueryBuilder::class;

    public function userStatistics(UserStatisticRequest $request)
    {
        $users = Order::query()
            ->select('user_uuid', Order::raw('SUM(total_amount) as total_price'))
            ->where('created_at', ">", $request->from_date)
            ->where('created_at', "<", $request->to_date)
            ->groupBy('user_uuid')
            ->orderByDesc('total_price')
            ->take(10)
            ->get();

        return $users;
    }

    public function profitStatistics(DasboardStatisticRequest $request)
    {
        $timeFromTo = $this->listRangeTime($request);

        $query = "";

        if ($request->type === DashboardEnum::DAY->value) {
            $query = "DATE_FORMAT(order_date, '%Y-%m-%d') as name";
        }

        if ($request->type === DashboardEnum::MONTH->value) {
            $query = "DATE_FORMAT(order_date, '%Y-%m') as name";
        }

        if ($request->type === DashboardEnum::YEAR->value) {
            $query = "DATE_FORMAT(order_date, '%Y') as name";
        }

        $selectTotalAmountForDates = Order::query()
            ->select(Order::raw($query), Order::raw("SUM(total_amount) as value"))
            ->where('order_date', ">", $request->from_date)
            ->where('order_date', "<", $request->to_date)
            ->groupBy('name')
            ->orderByRaw('name')
            ->get();

        $result = [];

        foreach ($selectTotalAmountForDates as $totalAmountForDate) {
            array_push($result, (object) [
                'name' => $totalAmountForDate->name,
                'value' => intval($totalAmountForDate->value),
            ]);
        }

        #Merge array and check unique sort
        $resultUnique = $this->mergeUniqueSortProperty($result, $timeFromTo, 'name');

        return $resultUnique;
    }
}