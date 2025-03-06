<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Models\IssuedItem;
use App\Models\Item;
use App\Models\ItemStock;
use App\Repositories\ItemRepository;
use Flash;
use Illuminate\Http\Request;

class ItemController extends AppBaseController
{
    /** @var ItemRepository */
    private $itemRepository;

    public function __construct(ItemRepository $itemRepo)
    {
        $this->itemRepository = $itemRepo;
    }

    public function index()
    {
        return view('items.index');
    }

    public function create()
    {
        $itemCategories = $this->itemRepository->getItemCategories();

        return view('items.create', compact('itemCategories'));
    }

    public function store(CreateItemRequest $request)
    {
        $input = $request->all();
        $input['description'] = ! empty($request->description) ? $request->description : null;
        $this->itemRepository->create($input);
        Flash::success(__('messages.item.item').' '.__('messages.common.saved_successfully'));

        return redirect(route('items.index'));
    }

    public function show(Item $item)
    {
        return view('items.show', compact('item'));
    }

    public function edit(Item $item)
    {
        $itemCategories = $this->itemRepository->getItemCategories();

        return view('items.edit', compact('item', 'itemCategories'));
    }

    public function update(Item $item, UpdateItemRequest $request)
    {
        $input = $request->all();
        $input['description'] = ! empty($request->description) ? $request->description : null;
        $this->itemRepository->update($input, $item->id);
        Flash::success(__('messages.item.item').' '.__('messages.common.updated_successfully'));

        return redirect(route('items.index'));
    }

    public function destroy(Item $item)
    {
        $itemModel = [
            ItemStock::class, IssuedItem::class,
        ];
        $result = canDelete($itemModel, 'item_id', $item->id);
        if ($result) {
            return $this->sendError(__('messages.item.item').' '.__('messages.common.cant_be_deleted'));
        }
        $item->delete();

        return $this->sendSuccess(__('messages.item.item').' '.__('messages.common.deleted_successfully'));
    }

    public function getAvailableQuantity(Request $request)
    {
        $data = Item::whereId($request->id)->first();

        return $data->available_quantity;
    }
}
