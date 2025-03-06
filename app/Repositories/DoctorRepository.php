<?php

namespace App\Repositories;

use App\Models\Address;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\Schedule;
use App\Models\User;
use Arr;
use Exception;
use Hash;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class DoctorRepository
 *
 * @version February 13, 2020, 8:55 am UTC
 */
class DoctorRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'user_id',
        'specialist',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return Doctor::class;
    }

    public function store($input, $mail = true)
    {
        $settings = App::make(SettingRepository::class)->getSyncList();
        try {
            $input['phone'] = preparePhoneNumber($input, 'phone');
            $input['department_id'] = Department::whereName('Doctor')->first()->id;
            $input['password'] = Hash::make($input['password']);
            $input['language'] = $settings['default_lang'];
            $input['dob'] = (!empty($input['dob'])) ? $input['dob'] : null;
            $user = User::create(Arr::except($input, ['specialist', 'doctor_department_id']));

            if ($mail) {
                $user->sendEmailVerificationNotification();
            }

            if (isset($input['image']) && !empty($input['image'])) {
                $mediaId = storeProfileImage($user, $input['image']);
            }

            $doctor = Doctor::create([
                'user_id' => $user->id,
                'doctor_department_id' => $input['doctor_department_id'],
                'specialist' => $input['specialist'],
                'description' => $input['description'],
                'appointment_charge' => removeCommaFromNumbers($input['appointment_charge']) ?? 0,
            ]);

            $schedule = Schedule::create([
                'doctor_id' => $doctor->id,
                'per_patient_time' => '01:00:00',
            ]);

            Session::put('doctor_id', $doctor->id);
            Session::put('schedule_id', $schedule->id);
            $ownerId = $doctor->id;
            $ownerType = Doctor::class;

            if (!empty($address = Address::prepareAddressArray($input))) {
                Address::create(array_merge($address, ['owner_id' => $ownerId, 'owner_type' => $ownerType]));
            }

            $user->update(['owner_id' => $ownerId, 'owner_type' => $ownerType]);
            $user->assignRole($input['department_id']);
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return true;
    }

    public function update($doctor, $input)
    {
        try {
            unset($input['password']);

            $user = User::find($doctor->doctorUser->id);

            if ($input['avatar_remove'] == 1 && isset($input['avatar_remove']) && !empty($input['avatar_remove'])) {
                removeFile($user, User::COLLECTION_PROFILE_PICTURES);
            }

            if (isset($input['image']) && !empty($input['image'])) {
                $mediaId = updateProfileImage($user, $input['image']);
            }

            $input['phone'] = preparePhoneNumber($input, 'phone');
            $input['dob'] = (!empty($input['dob'])) ? $input['dob'] : null;
            $input['appointment_charge'] = removeCommaFromNumbers($input['appointment_charge']) ?? 0;
            $doctor->doctorUser->update($input);
            $doctor->update($input);

            if (!empty($doctor->address)) {
                if (empty($address = Address::prepareAddressArray($input))) {
                    $doctor->address->delete();
                }
                $doctor->address->update($input);
            } else {
                if (!empty($address = Address::prepareAddressArray($input)) && empty($doctor->address)) {
                    $ownerId = $doctor->id;
                    $ownerType = Doctor::class;
                    Address::create(array_merge($address, ['owner_id' => $ownerId, 'owner_type' => $ownerType]));
                }
            }

            return true;
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function getDoctors()
    {
        $doctors = Doctor::with('doctorUser')->get()->where('doctorUser.status', '=', 1)->pluck(
            'doctorUser.full_name',
            'id'
        )->sort();

        return $doctors;
    }

    public function getDoctorAssociatedData($doctorId)
    {
        $data['doctorData'] = Doctor::with([
            'cases.patient.patientUser', 'patients.patientUser', 'schedules', 'payrolls', 'doctorUser',
            'address', 'appointments.doctor.doctorUser', 'appointments.patient.patientUser', 'appointments.department',
        ])->find($doctorId);
        if (!$data['doctorData']) {
            return false;
        }
        $data['appointments'] = $data['doctorData']->appointments;

        return $data;
    }
}
