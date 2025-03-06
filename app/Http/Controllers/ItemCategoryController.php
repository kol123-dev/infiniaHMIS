<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateItemCategoryRequest;
use App\Http\Requests\UpdateItemCategoryRequest;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Repositories\ItemCategoryRepository;
use Illuminate\Http\Request;


class ItemCategoryController extends AppBaseController
{
    /** @var ItemCategoryRepository */
    private $itemCategoryRepository;

    public function __construct(ItemCategoryRepository $itemCategoryRepo)
    {
        $this->itemCategoryRepository = $itemCategoryRepo;
    }

    public function index()
    {
        return view('item_categories.index');
    }

    public function store(CreateItemCategoryRequest $request)
    {
        $input = $request->all();
        $this->itemCategoryRepository->create($input);

        return $this->sendSuccess(__('messages.item_category.item_category').' '.__('messages.common.saved_successfully'));
    }

    public function edit(ItemCategory $itemCategory)
    {
        return $this->sendResponse($itemCategory, 'Item Category retrieved successfully.');
    }

    public function update(ItemCategory $itemCategory, UpdateItemCategoryRequest $request)
    {
        $input = $request->all();
        $this->itemCategoryRepository->update($input, $itemCategory->id);

        return $this->sendSuccess(__('messages.item_category.item_category').' '.__('messages.common.updated_successfully'));
    }

    public function destroy(ItemCategory $itemCategory)
    {
        $itemCategoryModel = [Item::class];
        $result = canDelete($itemCategoryModel, 'item_category_id', $itemCategory->id);
        if ($result) {
            return $this->sendError(__('messages.item_category.item_category').' '.__('messages.common.cant_be_deleted'));
        }
        $this->itemCategoryRepository->delete($itemCategory->id);

        return $this->sendSuccess(__('messages.item_category.item_category').' '.__('messages.common.deleted_successfully'));
    }

    public function getItemsList(Request $request)
    {
        if (empty($request->get('id'))) {
            return $this->sendError(__('messages.item.item').' '.__('messages.common.not_found'));
        }

        $itemsData = Item::get()->where('item_category_id', $request->get('id'))->pluck('name', 'id');

        return $this->sendResponse($itemsData, 'Retrieved successfully');
    }
}
