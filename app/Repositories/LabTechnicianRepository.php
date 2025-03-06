<?php

namespace App\Repositories;

use App\Models\Address;
use App\Models\Department;
use App\Models\LabTechnician;
use App\Models\User;
use Exception;
use Hash;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class LabTechnicianRepository
 *
 * @version February 14, 2020, 5:19 am UTC
 */
class LabTechnicianRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'full_name',
        'email',
        'phone',
        'education',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return LabTechnician::class;
    }

    public function store($input, $mail = true)
    {
        $settings = App::make(SettingRepository::class)->getSyncList();
        try {
            $input['department_id'] = Department::whereName('Lab Technician')->first()->id;
            $input['password'] = Hash::make($input['password']);
            $input['dob'] = (! empty($input['dob'])) ? $input['dob'] : null;
            $input['language'] = $settings ['default_lang'];
            $input['phone'] = preparePhoneNumber($input, 'phone');
            $user = User::create($input);

            if ($mail) {
                $user->sendEmailVerificationNotification();
            }

            if (isset($input['image']) && ! empty($input['image'])) {
                $mediaId = storeProfileImage($user, $input['image']);
            }

            $labTechnician = LabTechnician::create(['user_id' => $user->id]);

            $ownerId = $labTechnician->id;
            $ownerType = LabTechnician::class;

            if (! empty($address = Address::prepareAddressArray($input))) {
                Address::create(array_merge($address, ['owner_id' => $ownerId, 'owner_type' => $ownerType]));
            }

            $user->update(['owner_id' => $ownerId, 'owner_type' => $ownerType]);
            $user->assignRole($input['department_id']);

            return true;
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function update($labTechnician, $input)
    {
        try {
            $input['status'] = isset($input['status']) ? 1 : 0;
            unset($input['password']);

            $user = User::find($labTechnician->user->id);

            if ($input['avatar_remove'] == 1 && isset($input['avatar_remove']) && ! empty($input['avatar_remove'])) {
                removeFile($user, User::COLLECTION_PROFILE_PICTURES);
            }

            if (isset($input['image']) && ! empty($input['image'])) {
                $mediaId = updateProfileImage($user, $input['image']);
            }

            $input['dob'] = (! empty($input['dob'])) ? $input['dob'] : null;
            $input['phone'] = preparePhoneNumber($input, 'phone');
            $labTechnician->user->update($input);
            $labTechnician->update($input);

            if (! empty($labTechnician->address)) {
                if (empty($address = Address::prepareAddressArray($input))) {
                    $labTechnician->address->delete();
                }
                $labTechnician->address->update($input);
            } else {
                if (! empty($address = Address::prepareAddressArray($input)) && empty($labTechnician->address)) {
                    $ownerId = $labTechnician->id;
                    $ownerType = LabTechnician::class;
                    Address::create(array_merge($address, ['owner_id' => $ownerId, 'owner_type' => $ownerType]));
                }
            }

            return true;
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
