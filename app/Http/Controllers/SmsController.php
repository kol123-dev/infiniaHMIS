<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSmsRequest;
use App\Models\Sms;
use App\Repositories\SmsRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SmsController extends AppBaseController
{
    /**
     * @var SmsRepository
     */
    private $smsRepository;

    public function __construct(SmsRepository $smsRepository)
    {
        $this->smsRepository = $smsRepository;
    }

    public function index()
    {
        $roles = Sms::ROLE_TYPES;

        return view('sms.index', compact('roles'));
    }

    public function store(CreateSmsRequest $request)
    {
        $input = $request->all();
        $this->smsRepository->store($input);

        return $this->sendSuccess(__('messages.sms.sms').' '.__('messages.common.saved_successfully'));
    }

    public function show(Sms $sms)
    {
        if (getLoggedInUser()->hasRole('Admin')) {
            $sms = Sms::with('user.roles')->find($sms->id);

            return view('sms.show', compact('sms'));
        } else {
            if ($sms->send_by == getLoggedInUser()->id) {
                $sms = Sms::with('user.roles')->find($sms->id);

                return view('sms.show', compact('sms'));
            } else {
                return view('errors.404');
            }
        }
    }

    public function destroy(Sms $sms)
    {
        if (getLoggedInUser()->hasRole('Admin')) {
            $this->smsRepository->delete($sms->id);

            return $this->sendSuccess(__('messages.sms.sms').' '.__('messages.common.deleted_successfully'));
        } else {
            if ($sms->send_by == getLoggedInUser()->id) {
                $this->smsRepository->delete($sms->id);

                return $this->sendSuccess(__('messages.sms.sms').' '.__('messages.common.deleted_successfully'));
            } else {
                return $this->sendError(__('messages.sms.sms').' '.__('messages.common.not_found'));
            }
        }
    }

    public function getUsersList(Request $request)
    {
        if (empty($request->get('id'))) {
            return $this->sendError(__('messages.user.user_list').' '.__('messages.common.not_found'));
        }

        $usersData = Sms::CLASS_TYPES[$request->id]::with('user')
            ->whereHas('user', function (Builder $query) {
                $query->whereNotNull('phone');
            })
            ->get()->where('user.status', '=', 1)
            ->pluck('user.full_name', 'user.id');

        return $this->sendResponse($usersData, 'Retrieved successfully');
    }

    public function showModal(Sms $sms)
    {
        if (getLoggedInUser()->hasRole('Admin')) {
            $sms = Sms::with(['user.roles', 'sendBy'])->find($sms->id);

            return $this->sendResponse($sms, 'SMS Retrieved Successfully.');
        } else {
            if ($sms->send_by == getLoggedInUser()->id) {
                $sms = Sms::with(['user.roles', 'sendBy'])->find($sms->id);

                return $this->sendResponse($sms, 'SMS Retrieved Successfully.');
            } else {
                return $this->sendError(__('messages.sms.sms').' '.__('messages.common.not_found'));
            }
        }
    }
}
