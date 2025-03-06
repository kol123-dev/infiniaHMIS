<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateIssuedItemRequest;
use App\Models\IssuedItem;
use App\Repositories\IssuedItemRepository;
use Flash;
use Illuminate\Http\Request;

class IssuedItemController extends AppBaseController
{
    /** @var IssuedItemRepository */
    private $issuedItemRepository;

    public function __construct(IssuedItemRepository $issuedItemRepo)
    {
        $this->issuedItemRepository = $issuedItemRepo;
    }

    public function index()
    {
        $data['statusArr'] = IssuedItem::STATUS_ARR;

        return view('issued_items.index')->with($data);
    }

    public function create()
    {
        $data = $this->issuedItemRepository->getAssociatedData();

        return view('issued_items.create', compact('data'));
    }

    public function store(CreateIssuedItemRequest $request)
    {
        $input = $request->all();
        $input['return_date'] = ! empty($input['return_date']) ? $input['return_date'] : null;
        $this->issuedItemRepository->store($input);
        Flash::success(__('messages.issued_item.issued_item').' '.__('messages.common.saved_successfully'));

        return redirect(route('issued.item.index'));
    }

    public function show(IssuedItem $issuedItem)
    {
        return view('issued_items.show', compact('issuedItem'));
    }

    public function destroy(IssuedItem $issuedItem)
    {
        $this->issuedItemRepository->destroyIssuedItemStock($issuedItem);

        return $this->sendSuccess(__('messages.issued_item.issued_item').' '.__('messages.common.deleted_successfully'));
    }

    public function returnIssuedItem(Request $request)
    {
        $this->issuedItemRepository->returnIssuedItem($request->id);

        return $this->sendSuccess('Item returned successfully.');
    }
}
