<?php

namespace App\Repositories;

use App\Models\Address;
use App\Models\Department;
use App\Models\Nurse;
use App\Models\User;
use Exception;
use Hash;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class NurseRepository
 *
 * @version February 13, 2020, 11:18 am UTC
 */
class NurseRepository extends BaseRepository
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
        return Nurse::class;
    }

    public function store($input, $mail = true)
    {
        $settings = App::make(SettingRepository::class)->getSyncList();
        try {
            $input['department_id'] = Department::whereName('Nurse')->first()->id;
            $input['password'] = Hash::make($input['password']);
            $input['language'] =$settings['default_lang'];
            $input['phone'] = preparePhoneNumber($input, 'phone');
            $input['dob'] = (! empty($input['dob'])) ? $input['dob'] : null;
            $user = User::create($input);
            if ($mail) {
                $user->sendEmailVerificationNotification();
            }

            if (isset($input['image']) && ! empty($input['image'])) {
                $mediaId = storeProfileImage($user, $input['image']);
            }
            $nurse = Nurse::create(['user_id' => $user->id]);
            $ownerId = $nurse->id;
            $ownerType = Nurse::class;

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

    public function update($nurse, $input)
    {
        try {
            unset($input['password']);

            $user = User::find($nurse->user->id);
            if ($input['avatar_remove'] == 1 && isset($input['avatar_remove']) && ! empty($input['avatar_remove'])) {
                removeFile($user, User::COLLECTION_PROFILE_PICTURES);
            }
            if (isset($input['image']) && ! empty($input['image'])) {
                $mediaId = updateProfileImage($user, $input['image']);
            }

            $input['phone'] = preparePhoneNumber($input, 'phone');
            $input['dob'] = (! empty($input['dob'])) ? $input['dob'] : null;
            $nurse->user->update($input);
            $nurse->update($input);

            if (! empty($nurse->address)) {
                if (empty($address = Address::prepareAddressArray($input))) {
                    $nurse->address->delete();
                }
                $nurse->address->update($input);
            } else {
                if (! empty($address = Address::prepareAddressArray($input)) && empty($nurse->address)) {
                    $ownerId = $nurse->id;
                    $ownerType = Nurse::class;
                    Address::create(array_merge($address, ['owner_id' => $ownerId, 'owner_type' => $ownerType]));
                }
            }

            return true;
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
