<?php

namespace App\Http\Controllers;

use App\Exports\PostalExport;
use App\Http\Requests\PostalRequest;
use App\Models\Postal;
use App\Repositories\PostalRepository;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use Flash;

class PostalController extends AppBaseController
{
    /**
     * @var postalRepository
     */
    private $postalRepository;

    public function __construct(PostalRepository $postalRepository)
    {
        $this->postalRepository = $postalRepository;
    }

    public function index()
    {
        if (Route::current()->getName() == 'receives.index') {
            return view('postals.receives.index');
        }
        if (Route::current()->getName() == 'dispatches.index') {
            return view('postals.dispatches.index');
        }
    }

    public function store(PostalRequest $request)
    {
        $input = $request->all();

        $this->postalRepository->store($input);

        if (Route::current()->getName() == 'receives.store') {
            return $this->sendSuccess(__('messages.postal_receive') . ' ' . __('messages.common.saved_successfully'));
        }

        if (Route::current()->getName() == 'dispatches.store') {
            return $this->sendSuccess(__('messages.postal_dispatch') . ' ' . __('messages.common.saved_successfully'));
        }
    }

    public function edit(Postal $postal)
    {
        if (Route::current()->getName() == 'receives.edit') {
            return $this->sendResponse($postal, 'Postal Receive retrieved successfully.');
        }

        if (Route::current()->getName() == 'dispatches.edit') {
            return $this->sendResponse($postal, 'Postal Dispatch retrieved successfully.');
        }
    }

    public function update(PostalRequest $request, Postal $postal)
    {
        $this->postalRepository->updatePostal($request->all(), $postal->id);

        if (Route::current()->getName() == 'receives.update') {
            return $this->sendSuccess(__('messages.postal_receive') . ' ' . __('messages.common.updated_successfully'));
        }

        if (Route::current()->getName() == 'dispatches.update') {
            return $this->sendSuccess(__('messages.postal_dispatch') . ' ' . __('messages.common.updated_successfully'));
        }
    }

    public function destroy(Postal $postal)
    {
        $this->postalRepository->deleteDocument($postal->id);

        return $this->sendSuccess(__('messages.postal.postal') . ' ' . __('messages.common.deleted_successfully'));
    }

    public function downloadMedia(Postal $postal)
    {
        [$file, $headers] = $this->postalRepository->downloadMedia($postal);

        return response($file, 200, $headers);
    }

    public function export()
    {

        if (Route::current()->getName() == 'receives.excel') {
            $postalRecevices = Postal::where('type', '=', Postal::POSTAL_RECEIVE)->get();
            if (!$postalRecevices) {
                Flash::error(__('messages.common.no_data_available'));
                return redirect(route('receives.index'));
            }
            return Excel::download(new PostalExport, 'receive-' . time() . '.xlsx');
        }

        if (Route::current()->getName() == 'dispatches.excel') {
            $postalDispatchs = Postal::where('type', '=', Postal::POSTAL_DISPATCH)->get();
            if (!$postalDispatchs) {
                Flash::error(__('messages.common.no_data_available'));
                return redirect(route('dispatches.index'));
            }
            return Excel::download(new PostalExport, 'dispatch-' . time() . '.xlsx');
        }
    }
}
