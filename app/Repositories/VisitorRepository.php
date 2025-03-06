<?php

namespace App\Repositories;

use App\Models\Notification;
use App\Models\Receptionist;
use App\Models\Visitor;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class VisitorRepository
 *
 *
 * @version July 3, 2020, 9:12 am UTC
 */
class VisitorRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'purpose',
        'name',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return Visitor::class;
    }

    public function store($input)
    {
        try {
            $visitor = $this->create($input);

            if (! empty($input['attachment'])) {
                $fileExtension = getFileName('Visitor', $input['attachment']);
                $visitor->addMedia($input['attachment'])->usingFileName($fileExtension)->toMediaCollection(Visitor::PATH,
                    config('app.media_disc'));
            }

            $receptionists = Receptionist::pluck('user_id', 'id')->toArray();
            $userIds = [];

            foreach ($receptionists as $key => $userId) {
                $userIds[$userId] = Notification::NOTIFICATION_FOR[Notification::RECEPTIONIST];
            }
            
            $users = getAllNotificationUser($userIds);

            foreach ($users as $key => $notification) {
                addNotification([
                    Notification::NOTIFICATION_TYPE['Visitor'],
                    $key,
                    $notification,
                    'New visitor added.',
                ]);
            }

            return true;
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function updateVisitor($input, $visitorId)
    {
        try {
            $visitor = $this->update($input, $visitorId);

            if (! empty($input['attachment'])) {
                $visitor->clearMediaCollection(Visitor::PATH);
                $fileExtension = getFileName('Visitor', $input['attachment']);
                $visitor->addMedia($input['attachment'])->usingFileName($fileExtension)->toMediaCollection(Visitor::PATH,
                    config('app.media_disc'));
            }

            if ($input['avatar_remove'] == 1 && isset($input['avatar_remove']) && ! empty($input['avatar_remove'])) {
                removeFile($visitor, Visitor::PATH);
            }
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function deleteDocument($visitorId)
    {
        try {
            $visitor = $this->find($visitorId);
            $visitor->clearMediaCollection(Visitor::PATH);
            $this->delete($visitorId);
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function downloadMedia($visitor)
    {
        try {
            $documentMedia = $visitor->media[0];
            $documentPath = $documentMedia->getPath();
            if (config('app.media_disc') === 'public') {
                $documentPath = (Str::after($documentMedia->getUrl(), '/uploads'));
            }

            $file = Storage::disk(config('app.media_disc'))->get($documentPath);

            $headers = [
                'Content-Type' => $visitor->media[0]->mime_type,
                'Content-Description' => 'File Transfer',
                'Content-Disposition' => "attachment; filename={$visitor->media[0]->file_name}",
                'filename' => $visitor->media[0]->file_name,
            ];

            return [$file, $headers];
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
