<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEnquiryRequest;
use App\Models\Enquiry;
use App\Repositories\EnquiryRepository;
use Exception;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class EnquiryController extends AppBaseController
{
    /** @var EnquiryRepository */
    private $enquiryRepository;

    public function __construct(EnquiryRepository $enqRepo)
    {
        $this->enquiryRepository = $enqRepo;
    }

    public function index()
    {
        $data['statusArr'] = Enquiry::STATUS_ARR;

        return view('enquiries.index', $data);
    }

    public function store(CreateEnquiryRequest $request)
    {
        try {
            $input = $request->all();
            $input['contact_no'] = preparePhoneNumber($input, 'contact_no');
            $this->enquiryRepository->store($input);

            return $this->sendSuccess(__('messages.enquiry.enquiry_send_successfully'));
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function show(Enquiry $enquiry)
    {
        if ($enquiry->status == 0) {
            $enquiry->update(['viewed_by' => getLoggedInUserId()]);
            $enquiry->update(['status' => 1]);
        }

        return view('enquiries.show', compact('enquiry'));
    }

    public function activeDeactiveStatus($id)
    {
        $enquiry = Enquiry::find($id);
        $status = ! $enquiry->status;
        $viewedStatus = ($status == 1) ? getLoggedInUserId() : null;
        $enquiry->update(['viewed_by' => $viewedStatus]);
        $enquiry->update(['status' => $status]);

        return $this->sendSuccess(__('messages.common.status_updated_successfully'));
    }

    public function contactUs()
    {
        return view('web.home.contact_us');
    }
}
