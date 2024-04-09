<?php

namespace App\Abstracts;

use App\Models\AddOn;
use App\Models\Department;
use App\Models\Location;
use App\Models\App;
use App\Models\Role;
use App\Models\Team;
use App\Models\UserProfile;
use App\Models\UserTeam;
use Aws\Exception\CredentialsException;
use Aws\S3\Exception\S3Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AbstractRestAPIController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $service;

    protected $resourceCollectionClass;

    protected $resourceClass;

    protected $storeRequest;

    protected $editRequest;

    protected $indexRequest;

    protected $loginRequest;

    /**
     * Get the guard to be used during authentication.
     *
     * @return StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

    /**
     * Return Auth User
     *
     * @return Authenticatable|null
     */
    protected function user()
    {
        return Auth::user();
    }

    /**
     * @param bool $status
     * @param $message
     * @param array $data
     * @param int $httpStatus
     * @return JsonResponse
     */
    protected function sendJsonResponse($status = true, $message, $data = [], $httpStatus = Response::HTTP_OK)
    {
        $result = [
            'status' => $status,
            'message' => $message,
        ];

        if (!empty($data)) {
            $result = array_merge($result, $data);
        }

        return response()->json($result, $httpStatus);
    }

    /**
     * @param array $data
     * @return JsonResponse
     */
    protected function sendOkJsonResponse($data = [])
    {
        return $this->sendJsonResponse(true, __('messages.success'), $data);
    }

    /**
     * @param array $data
     * @return JsonResponse
     */
    protected function sendCreatedJsonResponse($data = [])
    {
        return $this->sendJsonResponse(true, __('messages.success'), $data, Response::HTTP_CREATED);
    }

    /**
     * @param array $data
     * @return JsonResponse
     */
    protected function sendUnAuthorizedJsonResponse($data = [])
    {
        return $this->sendJsonResponse(false, __('auth.failed'), $data, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @param array $data
     * @return JsonResponse
     */
    public function sendValidationFailedJsonResponse(array $data = [])
    {
        return $this->sendJsonResponse(false, __('messages.given_data_invalid'), $data, Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @param array $data
     * @return JsonResponse
     */
    public function sendInternalServerErrorJsonResponse(array $data = [])
    {
        return $this->sendJsonResponse(false, __('messages.internal_server_error'), $data, Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param array $data
     * @return JsonResponse
     */
    public function sendBadRequestJsonResponse(array $data = [])
    {
        return $this->sendJsonResponse(false, __('messages.bad_request'), $data, Response::HTTP_BAD_REQUEST);
    }

    protected function getPlatformByPermission($code)
    {
        $platformPackages = App::all();
        foreach ($platformPackages as $platformPackage) {
            $groupApi = $platformPackage->groupApis()->pluck('code')->toArray() ?? [];
            if (in_array($code, $groupApi)) {
                return ['plan' => 'platform_package_' . $platformPackage->uuid];
            }
        }
        $addOns = AddOn::all();
        foreach ($addOns as $addOn) {
            $groupApi = $addOn->groupApis()->pluck('code')->toArray() ?? [];
            if (in_array($code, $groupApi)) {
                return ['plan' => 'add_on_' . $addOn->uuid];
            }
        }

        return ['plan' => 'Does not have package/add-on for this feature. Comeback Later!!'];
    }

    /**
     * @param int $length
     * @return string
     */
    public function generateRandomString(int $length = 4): string
    {
        $permittedChars = '0123456789abcdefghijklmnopqrstuvwxyz';
        return substr(str_shuffle($permittedChars), 0, $length);
    }

    /**
     * @param $teamId
     * @return bool
     */
    public function checkTeamOwner($teamId)
    {
        if (Team::findOrFail($teamId)->owner_uuid != auth()->userId()) {

            return false;
        }

        return true;
    }

    public function checkDepartmentOwner($departmentUuid): bool
    {
        if (Department::findOrFail($departmentUuid)->user_uuid != auth()->userId()) {

            return false;
        }

        return true;
    }

    public function checkLocationOwner($locationUuid): bool
    {
        if (Location::findOrFail($locationUuid)->user_uuid != auth()->userId()) {

            return false;
        }

        return true;
    }

    function checkVietnamese($str)
    {
        $unicode = array(
            'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd' => 'đ',
            'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'i' => 'í|ì|ỉ|ĩ|ị',
            'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
            'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
            'D' => 'Đ',
            'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
            'I' => 'Í|Ì|Ỉ|Ĩ|Ị',
            'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
            'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
            'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
        );
        foreach ($unicode as $nonUnicode => $uni) {
            $str = preg_replace("/($uni)/i", $nonUnicode, $str);
        }

        return $str;
    }

    public function uploadFile($uploadType, $role, $uploadService)
    {
        try {
            $imageName = $role . '-' . Str::uuid() . '_' . time() . '.' . $uploadType->getClientOriginalExtension();
            //Bucket S3
            $configS3 = $uploadService->getStorageServiceByType('s3');
            //Check storage service exists or not
            $disk = $uploadService->storageBuild($configS3);
            //Upload
            $path = $disk->putFileAs('public/upload', $uploadType, $imageName);

            return [
                "slug" => $path,
                "absolute_slug" => $disk->url($path)
            ];
        } catch (S3Exception|\InvalidArgumentException|CredentialsException $exception) {
            return $this->sendValidationFailedJsonResponse();
        }
    }

    public function deleteFile($filename, $uploadService)
    {
        try {
            $configS3 = $uploadService->getStorageServiceByType('s3');
            $disk = $uploadService->storageBuild($configS3);
            $disk->delete($filename);

            return true;
        } catch (S3Exception|\InvalidArgumentException|CredentialsException $exception) {
            return $this->sendValidationFailedJsonResponse();
        }
    }

    public function getUserUuid() {
        $userTeam = UserTeam::where(['user_uuid' => auth()->userId(), 'app_id' => auth()->appId()])->first();

        if(($userTeam && !$userTeam['is_blocked'])) {
            $user_uuid = $userTeam->team->owner_uuid;
        } else {
            $user_uuid = auth()->userId();
        }

        return $user_uuid;
    }

    public function removeCache($userUuid)
    {
        Cache::forget('platform_permission_' . $userUuid);
        Cache::forget('add_on_permission_' . $userUuid);
        Cache::forget('team_leader_add_on_permission_' . $userUuid);
        Cache::forget('team_permission_' . $userUuid);
        Cache::forget('team_add_on_permission_' . $userUuid);
        Cache::forget('config');
    }

    public function removeTeamPermissionCache($userUuid)
    {
        Cache::forget('team_permission_' . $userUuid);
    }

    public function removeTeamLeaderPermissionCache($userUuid)
    {
        Cache::forget('team_leader_add_on_permission_' . $userUuid);
    }

    public function removeTeamAddOnPermissionCache($userUuid)
    {
        Cache::forget('team_add_on_permission_' . $userUuid);
    }

    public function checkExistBusiness()
    {
        if (!auth()->hasRole([Role::ROLE_ROOT, Role::ROLE_ADMIN])) {
            $user = $this->getUser();
            $businesses = $user->businessManagements;
            if (!$businesses->toArray()) {

                return false;
            }

            return true;
        }

        return true;
    }

    public function getBusiness()
    {
        $user = $this->getUser();
        $businesses = $user->businessManagements;
        if ($businesses->toArray()) {

            return $businesses->first();
        }

        return false;
    }

    public function getCstgeBusiness()
    {
        $businesses = auth('app_to_app')->user()->businessManagements;
        if ($businesses->toArray()) {

            return $businesses->first();
        }

        return false;
    }

    public function getUser() {
        return UserProfile::where(['user_uuid' => auth()->userId(), 'app_id' => auth()->appId()])->firstOrFail();
    }
}