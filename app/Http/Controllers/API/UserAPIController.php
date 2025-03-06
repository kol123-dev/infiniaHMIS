<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UpdateUserProfileRequest;
use App\Http\Resources\ProfileResource;
use App\Models\User;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

/**
 * Class UserAPIController
 */
class UserAPIController extends AppBaseController
{
    /** @var UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function editProfile(): JsonResponse
    {
        $user = Auth::user();
        $userData = new ProfileResource($user);

        return $this->sendResponse($userData, 'Profile Data Retrieved successfully.');
    }

    public function updateProfile(UpdateUserProfileRequest $request): JsonResponse
    {
        $input = $request->all();
        $updateUser = $this->userRepository->profileApiUpdate($input);
        $newData = new ProfileResource($updateUser);

        return $this->sendResponse($newData, 'Profile Updated successfully');
    }

    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $input = $request->all();
        try {
            $this->userRepository->changePassword($input);

            return $this->sendSuccess(__('messages.user.password').' '.__('messages.common.updated_successfully'));
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function getProfile(): JsonResponse
    {
        $user = User::where('id', getLoggedInUserId())->first();

        return $this->sendResponse($user->prepareData(), 'User profile get successfully');
    }
}
