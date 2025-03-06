<?php

namespace App\Http\Controllers;

use App\Exports\VisitorExport;
use App\Http\Requests\CreateVisitorRequest;
use App\Http\Requests\UpdateVisitorRequest;
use App\Models\Visitor;
use App\Repositories\VisitorRepository;
use Flash;
use Maatwebsite\Excel\Facades\Excel;

class VisitorController extends AppBaseController
{
    /**
     * @var  visitorRepository
     */
    private $visitorRepository;

    public function __construct(VisitorRepository $visitorRepo)
    {
        $this->visitorRepository = $visitorRepo;
    }

    public function index()
    {
        $purpose = Visitor::FILTER_PURPOSE;

        return view('visitors.index', compact('purpose'));
    }

    public function create()
    {
        $purpose = Visitor::PURPOSE;
        $isEdit = false;

        return view('visitors.create', compact('purpose', 'isEdit'));
    }

    public function store(CreateVisitorRequest $request)
    {
        $input = $request->all();
        $input = [
            'purpose' => $request->purpose ?? null,
            'name' => $request->name ?? null,
            'phone' => $request->phone ?? null,
            'id_card' => $request->id_card ?? null,
            'no_of_person' => $request->no_of_person ?? null,
            'date' => $request->date ?? null,
            'in_time' => $request->in_time ?? null,
            'out_time' => $request->out_time ?? null,
            'note' => $request->note ?? null,
            'prefix_code' => $request->prefix_code ?? null,
        ];
        $input['phone'] = preparePhoneNumber($input, 'phone');
        $this->visitorRepository->store($input);
        Flash::success(__('messages.visitors') . ' ' . __('messages.common.saved_successfully'));

        return redirect(route('visitors.index'));
    }

    public function edit(Visitor $visitor)
    {
        $purpose = Visitor::PURPOSE;
        $fileExt = pathinfo($visitor->document_url, PATHINFO_EXTENSION);
        $isEdit = true;

        return view('visitors.edit', compact('visitor', 'purpose', 'fileExt', 'isEdit'));
    }

    public function update(UpdateVisitorRequest $request, Visitor $visitor)
    {
        $input = $request->all();
        $input['phone'] = preparePhoneNumber($input, 'phone');
        $this->visitorRepository->updateVisitor($input, $visitor->id);
        Flash::success(__('messages.visitors') . ' ' . __('messages.common.updated_successfully'));

        return redirect(route('visitors.index'));
    }

    public function destroy(Visitor $visitor)
    {
        $this->visitorRepository->deleteDocument($visitor->id);

        return $this->sendSuccess(__('messages.visitors') . ' ' . __('messages.common.deleted_successfully'));
    }

    public function downloadMedia(Visitor $visitor)
    {
        [$file, $headers] = $this->visitorRepository->downloadMedia($visitor);

        return response($file, 200, $headers);
    }

    public function export()
    {
        $visitors = Visitor::all();
        if (!$visitors) {
            Flash::error(__('messages.common.no_data_available'));
            return redirect(route('visitors.index'));
        }
        return Excel::download(new VisitorExport, 'visitor-' . time() . '.xlsx');
    }
}
