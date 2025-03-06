<?php

namespace App\Repositories;

use App\Mail\HospitalEnquiryMail;
use App\Models\Enquiry;
use Exception;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class EnquiryRepository
 *
 * @version February 13, 2020, 8:55 am UTC
 */
class EnquiryRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'full_name',
        'contact_no',
        'message',
        'viewed_by',
        'status',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return Enquiry::class;
    }

    public function store($input)
    {
        try {
            $enquiry = Enquiry::create($input);
            $input['purpose'] = $enquiry->getEnquiryTypeAttribute();
            Mail::to(getSettingValue()['hospital_email']['value'])
                ->send(new HospitalEnquiryMail('emails.hospital_enquiry', 'Enquiry Mail', $input));

        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return true;
    }
}
