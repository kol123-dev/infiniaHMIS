<?php

namespace App\Repositories;

use App\Models\Accountant;
use App\Models\AdvancedPayment;
use App\Models\Appointment;
use App\Models\BedAssign;
use App\Models\Bill;
use App\Models\BirthReport;
use App\Models\CaseHandler;
use App\Models\DeathReport;
use App\Models\Doctor;
use App\Models\DoctorDepartment;
use App\Models\EmployeePayroll;
use App\Models\InvestigationReport;
use App\Models\Invoice;
use App\Models\IpdPatientDepartment;
use App\Models\LabTechnician;
use App\Models\Nurse;
use App\Models\OperationReport;
use App\Models\Patient;
use App\Models\PatientAdmission;
use App\Models\PatientCase;
use App\Models\Pharmacist;
use App\Models\Prescription;
use App\Models\Receptionist;
use App\Models\Schedule;
use App\Models\Setting;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Exception;
use Hash;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class UserRepository
 *
 * @version January 11, 2020, 11:09 am UTC
 */
class UserRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'name',
        'email',
        'phone',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return User::class;
    }

    public function profileUpdate($input)
    {
        $user = $this->find(Auth::id());
        try {
            if (empty($input['image']) && $input['avatar_remove'] == 1 && isset($input['avatar_remove']) && ! empty($input['avatar_remove'])) {
                removeFile($user, User::COLLECTION_PROFILE_PICTURES);
            }
            if (isset($input['image']) && ! empty($input['image'])) {
                $mediaId = updateProfileImage($user, $input['image']);
            }

            $user->update($input);

            return true;
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function changePassword($input)
    {
        try {
            $user = Auth::user();
            if (! Hash::check($input['password_current'], $user->password)) {
                throw new UnprocessableEntityHttpException(__('messages.user.invalid_password'));
            }
            $input['password'] = Hash::make($input['password']);
            $user->update($input);

            return true;
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function store($input)
    {
            $settings = App::make(SettingRepository::class)->getSyncList();
            try {
            $input['phone'] = preparePhoneNumber($input, 'phone');
            $input['password'] = Hash::make($input['password']);
            $input['dob'] = (! empty($input['dob'])) ? $input['dob'] : null;
            $input['language'] = $settings['default_lang'];
            $user = User::create($input);

            if (isset($input['image']) && ! empty($input['image'])) {
                $fileExtension = getFileName('User', $input['image']);
                $user->addMedia($input['image'])->usingFileName($fileExtension)->toMediaCollection(User::COLLECTION_PROFILE_PICTURES,
                    config('app.media_disc'));
            }

            if ($input['department_id'] == 1) {
                $ownerId = null;
                $ownerType = null;
            } elseif ($input['department_id'] == 2) {
                $doctorDepartment = DoctorDepartment::first();
                $doctor = Doctor::create([
                    'user_id' => $user->id,
                    'doctor_department_id' => $doctorDepartment->id,
                    'specialist' => 'Bones',
                ]);
                $user->sendEmailVerificationNotification();
                $ownerId = $doctor->id;
                $ownerType = Doctor::class;
                $user->update(['language' => getSettingValue()['default_lang']->value]);
            } elseif ($input['department_id'] == 3) {
                $patient = Patient::create(['user_id' => $user->id,'patient_unique_id' => strtoupper(Patient::generateUniquePatientId())]);
                $user->sendEmailVerificationNotification();
                $ownerId = $patient->id;
                $ownerType = Patient::class;
            } elseif ($input['department_id'] == 4) {
                $nurse = Nurse::create(['user_id' => $user->id]);
                $user->sendEmailVerificationNotification();
                $ownerId = $nurse->id;
                $ownerType = Nurse::class;
            } elseif ($input['department_id'] == 5) {
                $receptionist = Receptionist::create(['user_id' => $user->id]);
                $user->sendEmailVerificationNotification();
                $ownerId = $receptionist->id;
                $ownerType = Receptionist::class;
            } elseif ($input['department_id'] == 6) {
                $pharmacist = Pharmacist::create(['user_id' => $user->id]);
                $user->sendEmailVerificationNotification();
                $ownerId = $pharmacist->id;
                $ownerType = Pharmacist::class;
            } elseif ($input['department_id'] == 7) {
                $accountant = Accountant::create(['user_id' => $user->id]);
                $user->sendEmailVerificationNotification();
                $ownerId = $accountant->id;
                $ownerType = Accountant::class;
            } elseif ($input['department_id'] == 8) {
                $caseManager = CaseHandler::create(['user_id' => $user->id]);
                $user->sendEmailVerificationNotification();
                $ownerId = $caseManager->id;
                $ownerType = CaseHandler::class;
            } elseif ($input['department_id'] == 9) {
                $labTechnician = LabTechnician::create(['user_id' => $user->id]);
                $user->sendEmailVerificationNotification();
                $ownerId = $labTechnician->id;
                $ownerType = LabTechnician::class;
            }

            $user->update(['owner_id' => $ownerId, 'owner_type' => $ownerType,]);

            $user->assignRole($input['department_id']);
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return true;
    }

    public function updateUser($input, $userId)
    {
        try {
            $user = $this->update($input, $userId);
            if (isset($input['image']) && ! empty($input['image'])) {
                $user->clearMediaCollection(User::COLLECTION_PROFILE_PICTURES);
                $fileExtension = getFileName('User', $input['image']);
                $user->addMedia($input['image'])->usingFileName($fileExtension)->toMediaCollection(User::COLLECTION_PROFILE_PICTURES,
                    config('app.media_disc'));
                $user->update(['updated_at' => Carbon::now()->timestamp]);
            }

            if ($input['avatar_remove'] == 1 && isset($input['avatar_remove']) && ! empty($input['avatar_remove'])) {
                removeFile($user, User::COLLECTION_PROFILE_PICTURES);
            }

            return true;
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function deleteUser($userId)
    {
        try {
            $user = $this->find($userId);

            if ($user->department_id == 2) {
                $doctorModels = [
                    PatientCase::class, PatientAdmission::class, Schedule::class, Appointment::class,
                    BirthReport::class,
                    DeathReport::class, InvestigationReport::class, OperationReport::class, Prescription::class,
                    IpdPatientDepartment::class,
                ];
                $result = canDelete($doctorModels, 'doctor_id', $user->owner_id);
                $empPayRollResult = canDeletePayroll(EmployeePayroll::class, 'owner_id', $user->owner_id, $user->owner_type);
                if ($result || $empPayRollResult) {
                    throw new BadRequestHttpException(
                        __('messages.case.doctor').' '.__('messages.common.cant_be_deleted'),
                        null,
                        \Illuminate\Http\Response::HTTP_BAD_REQUEST
                    );
                }
                Doctor::whereId($user->owner_id)->delete();
            } elseif ($user->department_id == 3) {
                $patientModels = [
                    BirthReport::class, DeathReport::class, InvestigationReport::class, OperationReport::class,
                    Appointment::class, BedAssign::class, PatientAdmission::class, PatientCase::class, Bill::class,
                    Invoice::class, AdvancedPayment::class, Prescription::class, IpdPatientDepartment::class,
                ];
                $result = canDelete($patientModels, 'patient_id', $user->owner_id);
                if ($result) {
                    throw new BadRequestHttpException(
                        __('messages.advanced_payment.patient').' '.__('messages.common.cant_be_deleted'),
                        null,
                        \Illuminate\Http\Response::HTTP_BAD_REQUEST
                    );
                }
                Patient::whereId($user->owner_id)->delete();
            } elseif ($user->department_id == 4) {
                $empPayRollResult = canDeletePayroll(EmployeePayroll::class, 'owner_id', $user->owner_id, $user->owner_type);
                if ($empPayRollResult) {
                    throw new BadRequestHttpException(
                        __('messages.nurses').' '.__('messages.common.cant_be_deleted'),
                        null,
                        \Illuminate\Http\Response::HTTP_BAD_REQUEST
                    );
                }
            } elseif ($user->department_id == 5) {
                $empPayRollResult = canDeletePayroll(EmployeePayroll::class, 'owner_id', $user->owner_id, $user->owner_type);
                if ($empPayRollResult) {
                    throw new BadRequestHttpException(
                        __('messages.receptionist.receptionist').' '.__('messages.common.cant_be_deleted'),
                        null,
                        \Illuminate\Http\Response::HTTP_BAD_REQUEST
                    );
                }
                Receptionist::whereId($user->owner_id)->delete();
            } elseif ($user->department_id == 6) {
                $empPayRollResult = canDeletePayroll(EmployeePayroll::class, 'owner_id', $user->owner_id, $user->owner_type);
                if ($empPayRollResult) {
                    throw new BadRequestHttpException(
                        __('messages.pharmacists').' '.__('messages.common.cant_be_deleted'),
                        null,
                        \Illuminate\Http\Response::HTTP_BAD_REQUEST
                    );
                }
                Pharmacist::whereId($user->owner_id)->delete();
            } elseif ($user->department_id == 7) {
                $empPayRollResult = canDeletePayroll(EmployeePayroll::class, 'owner_id', $user->owner_id, $user->owner_type);
                if ($empPayRollResult) {
                    throw new BadRequestHttpException(
                        __('messages.accountant.accountants').' '.__('messages.common.cant_be_deleted'),
                        null,
                        \Illuminate\Http\Response::HTTP_BAD_REQUEST
                    );
                }
                Accountant::whereId($user->owner_id)->delete();
            } elseif ($user->department_id == 8) {
                caseHandler::whereId($user->owner_id)->delete();
            } elseif ($user->department_id == 9) {
                $empPayRollResult = canDeletePayroll(EmployeePayroll::class, 'owner_id', $user->owner_id, $user->owner_type);
                if ($empPayRollResult) {
                    throw new BadRequestHttpException(
                        __('messages.lab_technicians').' '.__('messages.common.cant_be_deleted'),
                        null,
                        \Illuminate\Http\Response::HTTP_BAD_REQUEST
                    );
                }
                LabTechnician::whereId($user->owner_id)->delete();
            }

            $user->clearMediaCollection(User::COLLECTION_PROFILE_PICTURES);
            $this->delete($userId);
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function getUserData($user)
    {
        $data = User::with('roles')->find($user)->append(['gender_string', 'image_url']);

        return $data;
    }

    public function profileApiUpdate($input)
    {
        $user = $this->find(Auth::id());

        try {
            if (isset($input['image']) && ! empty($input['image'])) {
                $mediaId = updateProfileImage($user, $input['image']);
            }

            $user->update($input);

            return $user;
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
