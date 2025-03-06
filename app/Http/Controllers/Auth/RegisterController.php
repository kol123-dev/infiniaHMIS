<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Patient;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator($data)
    {
        App::setLocale(checkLanguageSession());
        return Validator::make($data, [
            'first_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email:filter', 'max:255', 'unique:users'],
            'phone' => ['required'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'password.min' => __('messages.user.validate_min_character_password'),
        ]);
    }

    protected function create($data)
    {
        $data['password'] = Hash::make($data['password']);
        $data['department_id'] = Department::whereName('Patient')->first()->id;
        $data['status'] = 1;
        $data['language'] = checkLanguageSession();
        App::setLocale(checkLanguageSession());

        $user = User::create($data);
        $patient = Patient::create(['user_id' => $user->id]);

        $user->update(['owner_id' => $patient->id, 'owner_type' => Patient::class]);
        $user->assignRole($data['department_id']);

        return $user;
    }
}
