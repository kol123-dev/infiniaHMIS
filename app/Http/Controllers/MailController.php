<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMailRequest;
use App\Repositories\MailRepository;
use Flash;

class MailController extends Controller
{
    /** @var MailRepository */
    private $mailRepository;

    public function __construct(MailRepository $mailRepo)
    {
        $this->mailRepository = $mailRepo;
    }

    public function index()
    {
        return view('mail.index');
    }

    public function store(CreateMailRequest $request)
    {
        $input = $request->all();
        $this->mailRepository->store($input);
        Flash::success(__('messages.mail').' '.__('messages.common.saved_successfully'));

        return redirect(route('mail'));
    }
}
