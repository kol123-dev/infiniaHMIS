<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserProfileRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Department;
use App\Models\DoctorDepartment;
use App\Models\Setting;
use App\Models\User;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use DB;
use Exception;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use URL;

class UserController extends AppBaseController
{
    /** @var UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepository = $userRepo;
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $input = $request->all();

        try {
            $user = $this->userRepository->changePassword($input);

            return $this->sendSuccess(__('messages.user.password').' '.__('messages.common.updated_successfully'));
        } catch (Exception $e) {
            return $this->sendError($e->getMessage(), 422);
        }
    }

    public function profileUpdate(UpdateUserProfileRequest $request)
    {
        $input = $request->all();

        try {
            $user = $this->userRepository->profileUpdate($input);

            return $this->sendResponse($user, __('messages.common.profile').' '.__('messages.common.updated_successfully'));
        } catch (Exception $e) {
            return $this->sendError($e->getMessage(), 422);
        }
    }

    public function editProfile()
    {
        $user = getLoggedInUser()->append('image_url');

        return $this->sendResponse($user, 'User retrieved successfully.');
    }

    public function updateLanguage(Request $request)
    {
        $language = $request->get('language');
        Session::put('languageName', $language);
        App::setLocale(session('languageName'));
        $user = $request->user();
        $user->update(['language' => $language]);

        return $this->sendSuccess(__('messages.profile.language').' '.__('messages.common.updated_successfully'));
    }

    public function index()
    {
        $roles = Department::orderBy('name')->pluck('name', 'id')->toArray();
        $status = User::STATUS_ARR;

        return view('users.index', compact('roles', 'status'));
    }

    public function create()
    {
        $isEdit = false;
        $role = Department::orderBy('name')->pluck('name', 'id')->toArray();
        $doctorDepartments = DoctorDepartment::pluck('title', 'id')->toArray();

        return view('users.create', compact('isEdit', 'role', 'doctorDepartments'));
    }

    public function store(CreateUserRequest $request)
    {
        try {
            DB::beginTransaction();
            $input = $request->all();
            $input['status'] = isset($input['status']) ? 1 : 0;
            $this->userRepository->store($input);
            Flash::success(__('messages.users').' '.__('messages.common.saved_successfully'));
            DB::commit();

            return redirect(route('users.index'));
        } catch (Exception $e) {
            DB::rollBack();

            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function show($user)
    {
        $userData = $this->userRepository->getUserData($user);

        return view('users.show', compact('userData'));
    }

    public function edit(User $user)
    {
        $role = Department::pluck('name', 'id')->all();
        $isEdit = true;

        return view('users.edit', compact('isEdit', 'user', 'role'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $input = $request->all();
        $input['status'] = isset($input['status']) ? 1 : 0;
        $input['dob'] = (! empty($input['dob'])) ? $input['dob'] : null;
        $input['phone'] = preparePhoneNumber($input, 'phone');
        $this->userRepository->updateUser($input, $user->id);
        Flash::success(__('messages.users').' '.__('messages.common.updated_successfully'));

        return redirect(route('users.index'));
    }

    public function destroy(User $user)
    {
        $this->userRepository->deleteUser($user->id);

        return $this->sendSuccess(__('messages.users').' '.__('messages.common.deleted_successfully'));
    }

    public function activeDeactiveStatus($id)
    {
        $user = User::find($id);
        $status = ! $user->status;
        $user->update(['status' => $status]);

        return $this->sendSuccess(__('messages.common.status_updated_successfully'));
    }

    public function showModal($user)
    {
        $userData = $this->userRepository->getUserData($user);

        return $this->sendResponse($userData, 'User Retrieved Successfully.');
    }

    public function isVerified($id)
    {
        $user = User::find($id);
        $emailVerified = $user->email_verified_at == null ? Carbon::now() : null;
        $user->update(['email_verified_at' => $emailVerified]);

        return $this->sendSuccess(__('messages.user.email_verified_successfully'));
    }

    public function changeThemeMode()
    {
        $user = User::find(getLoggedInUser()->id);

        if ($user->thememode == User::THEME_LIGHT_MODE) {
            $user['thememode'] = User::THEME_DARK_MODE;
        } else {
            $user['thememode'] = User::THEME_LIGHT_MODE;
        }

        $user->update();

        return redirect(URL::previous());
    }
}
