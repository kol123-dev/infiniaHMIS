<?php

namespace App\Repositories;

use App\Models\NoticeBoard;
use App\Models\Notification;
use App\Models\User;
use Exception;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class NoticeBoardRepository
 *
 * @version February 18, 2020, 4:23 am UTC
 */
class NoticeBoardRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'title',
        'description',
        'start_date',
        'end_date',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return NoticeBoard::class;
    }

    public function createNotification()
    {
        try {
            $users = User::where('email', '!=', 'admin@hms.com')
                ->pluck('owner_type', 'id')
                ->toArray();

            foreach ($users as $key => $ownerType) {
                $userIds[$key] = Notification::NOTIFICATION_FOR[User::getOwnerType($ownerType)];
            }

            foreach ($userIds as $key => $notification) {
                addNotification([
                    Notification::NOTIFICATION_TYPE['NoticeBoard'],
                    $key,
                    $notification,
                    'New notice board added.',
                ]);
            }

            return true;
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
