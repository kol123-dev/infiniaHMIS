<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateItemStockRequest;
use App\Http\Requests\UpdateItemStockRequest;
use App\Models\ItemStock;
use App\Repositories\ItemStockRepository;
use Flash;

class ItemStockController extends AppBaseController
{
    /** @var ItemStockRepository */
    private $itemStockRepository;

    public function __construct(ItemStockRepository $itemStockRepo)
    {
        $this->itemStockRepository = $itemStockRepo;
    }

    public function index()
    {
        return view('item_stocks.index');
    }

    public function create()
    {
        $itemCategories = $this->itemStockRepository->getItemCategories();
        natcasesort($itemCategories);

        return view('item_stocks.create', compact('itemCategories'));
    }

    public function store(CreateItemStockRequest $request)
    {
        $input = $request->all();
        $input['purchase_price'] = removeCommaFromNumbers($input['purchase_price']);
        $this->itemStockRepository->store($input);
        Flash::success(__('messages.item_stock.item_stock').' '.__('messages.common.saved_successfully'));

        return redirect(route('item.stock.index'));
    }

    public function show(ItemStock $itemStock)
    {
        return view('item_stocks.show', compact('itemStock'));
    }

    public function edit(ItemStock $itemStock)
    {
        $itemCategories = $this->itemStockRepository->getItemCategories();
        natcasesort($itemCategories);

        return view('item_stocks.edit', compact('itemCategories', 'itemStock'));
    }

    public function update(ItemStock $itemStock, UpdateItemStockRequest $request)
    {
        $input = $request->all();
        $input['purchase_price'] = removeCommaFromNumbers($input['purchase_price']);
        $this->itemStockRepository->update($itemStock, $input);
        Flash::success(__('messages.item_stock.item_stock').' '.__('messages.common.updated_successfully'));

        return redirect(route('item.stock.index'));
    }

    public function destroy(ItemStock $itemStock)
    {
        $this->itemStockRepository->destroyItemStock($itemStock);

        return $this->sendSuccess(__('messages.item_stock.item_stock').' '.__('messages.common.deleted_successfully'));
    }

    public function downloadMedia(ItemStock $itemStock)
    {
        [$file, $headers] = $this->itemStockRepository->downloadMedia($itemStock);

        return response($file, 200, $headers);
    }
}
