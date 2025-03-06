<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Models\Department;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegistrationController extends AppBaseController
{
    public function register(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email:filter', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $data = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'gender' => $request->gender == 'male' ? 0 : 1,
            'password' => Hash::make($request['password']),
            'department_id' => Department::whereName('Patient')->first()->id,
            'status' => 1,
        ];

        $user = User::create($data);
        $user->sendEmailVerificationNotification();
        $patient = Patient::create(['user_id' => $user->id]);

        $user->update(['owner_id' => $patient->id, 'owner_type' => Patient::class]);
        $user->assignRole($data['department_id']);

        return $this->sendSuccess(__('messages.common.patient_registered_successfully'));
    }
}
