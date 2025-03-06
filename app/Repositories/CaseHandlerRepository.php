<?php

namespace App\Repositories;

use App\Models\Address;
use App\Models\CaseHandler;
use App\Models\Department;
use App\Models\User;
use Exception;
use Hash;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class CaseHandlerRepository
 *
 * @version February 28, 2020, 3:14 am UTC
 */
class CaseHandlerRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'user_id',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return CaseHandler::class;
    }

    public function store($input, $mail = true)
    {
        try {
            $input['department_id'] = Department::whereName('Case Manager')->first()->id;
            $input['password'] = Hash::make($input['password']);

            $input['phone'] = preparePhoneNumber($input, 'phone');
            $input['dob'] = (! empty($input['dob'])) ? $input['dob'] : null;
            $user = User::create($input);

            if ($mail) {
                $user->sendEmailVerificationNotification();
            }

            if (isset($input['image']) && ! empty($input['image'])) {
                $mediaId = storeProfileImage($user, $input['image']);
            }

            $caseHandler = CaseHandler::create(['user_id' => $user->id]);
            $ownerId = $caseHandler->id;
            $ownerType = CaseHandler::class;

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

    public function update($caseHandler, $input)
    {
        try {
            unset($input['password']);

            $user = User::find($caseHandler->user->id);

            if ($input['avatar_remove'] == 1 && isset($input['avatar_remove']) && ! empty($input['avatar_remove'])) {
                removeFile($user, User::COLLECTION_PROFILE_PICTURES);
            }

            if (isset($input['image']) && ! empty($input['image'])) {
                $mediaId = updateProfileImage($user, $input['image']);
            }

            $input['phone'] = preparePhoneNumber($input, 'phone');
            $input['dob'] = (! empty($input['dob'])) ? $input['dob'] : null;
            $caseHandler->user->update($input);
            $caseHandler->update($input);

            if (! empty($caseHandler->address)) {
                if (empty($address = Address::prepareAddressArray($input))) {
                    $caseHandler->address->delete();
                }
                $caseHandler->address->update($input);
            } else {
                if (! empty($address = Address::prepareAddressArray($input)) && empty($caseHandler->address)) {
                    $ownerId = $caseHandler->id;
                    $ownerType = CaseHandler::class;
                    Address::create(array_merge($address, ['owner_id' => $ownerId, 'owner_type' => $ownerType]));
                }
            }

            return true;
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
