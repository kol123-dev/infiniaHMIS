<?php

namespace App\Repositories;

use App\Mail\MarkdownMail;
use App\Models\Mail;
use Auth;
use Exception;
use Illuminate\Support\Facades\Mail as Email;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class DoctorRepository
 *
 * @version February 13, 2020, 8:55 am UTC
 */
class MailRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'to',
        'subject',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return Mail::class;
    }

    public function store($input)
    {
        try {
            $user = Auth::user();
            if (isset($input['attachments']) && ! empty($input['attachments'])) {

                $media = storeAttachments($user, $input['attachments']);
                $input['attachments'] = $media->getFullUrl();
            }
            $input['attachments'] = (isset($input['attachments'])) ? $input['attachments'] : null;

            $mail = Mail::create([
                'to' => $input['to'],
                'subject' => $input['subject'],
                'message' => $input['message'],
                'attachments' => $input['attachments'],
                'user_id' => $user->id,
            ]);

            Email::to($input['to'])
                ->send(new MarkdownMail('emails.email',
                    $mail->subject,
                    $input));
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return true;
    }
}
