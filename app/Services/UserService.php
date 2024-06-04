<?php

namespace App\Services;

use App\Abstracts\AbstractService;
use App\Enums\DashboardEnum;
use App\Http\Requests\DasboardStatisticRequest;
use App\Models\QueryBuilders\UserQueryBuilder;
use App\Models\User;

class UserService extends AbstractService
{
    protected $modelClass = User::class;
    protected $modelQueryBuilderClass = UserQueryBuilder::class;

    public function userRegisterStatistics(DasboardStatisticRequest $request)
    {
        $timeFromTo = $this->listRangeTime($request);

        $query = "";

        if ($request->type === DashboardEnum::DAY->value) {
            $query = "DATE_FORMAT(created_at, '%Y-%m-%d') as name_column";
        }

        if ($request->type === DashboardEnum::MONTH->value) {
            $query = "DATE_FORMAT(created_at, '%Y-%m') as name_column";
        }

        if ($request->type === DashboardEnum::YEAR->value) {
            $query = "DATE_FORMAT(created_at, '%Y') as name_column";
        }

        $selectCountUserRegisters = User::query()
            ->select(User::raw($query), User::raw("COUNT(uuid) as value"))
            ->where('created_at', ">", $request->from_date)
            ->where('created_at', "<", $request->to_date)
            ->groupBy('name_column')
            ->orderByRaw('name_column')
            ->get();

        $result = [];

        foreach ($selectCountUserRegisters as $selectCountUserRegister) {
            array_push($result, (object) [
                'name' => $selectCountUserRegister->name_column,
                'value' => intval($selectCountUserRegister->value),
            ]);
        }

        #Check unique array
        $resultUnique = $this->mergeUniqueSortProperty($result, $timeFromTo, 'name');

        return $resultUnique;
    }
}