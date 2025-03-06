<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Models\Medicine;
use App\Repositories\CategoryRepository;

class CategoryController extends AppBaseController
{
    /** @var CategoryRepository */
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepo)
    {
        $this->categoryRepository = $categoryRepo;
    }

    public function index()
    {
        $data['statusArr'] = Category::STATUS_ARR;

        return view('categories.index', $data);
    }

    public function store(CreateCategoryRequest $request)
    {
        $input = $request->all();
        $input['is_active'] = ! isset($input['is_active']) ? false : true;
        $this->categoryRepository->create($input);

        return $this->sendSuccess(__('messages.medicine.medicine_category').' '.__('messages.common.saved_successfully'));
    }

    public function show(Category $category)
    {
        $medicines = $category->medicines;

        return view('categories.show', compact('medicines', 'category'));
    }

    public function edit(Category $category)
    {
        return $this->sendResponse($category, 'Medicine category retrieved successfully.');
    }

    public function update(Category $category, UpdateCategoryRequest $request)
    {
        $input = $request->all();
        $input['is_active'] = ! isset($input['is_active']) ? false : true;
        $this->categoryRepository->update($input, $category->id);

        return $this->sendSuccess(__('messages.medicine.medicine_category').' '.__('messages.common.updated_successfully'));
    }

    public function destroy(Category $category)
    {
        $medicineCategoryModel = [
            Medicine::class,
        ];

        $result = canDelete($medicineCategoryModel, 'category_id', $category->id);

        if ($result) {
            return $this->sendError(__('messages.medicine.medicine_category').' '.__('messages.common.cant_be_deleted'));
        }

        $this->categoryRepository->delete($category->id);

        return $this->sendSuccess(__('messages.medicine.medicine_category').' '.__('messages.common.deleted_successfully'));
    }

    public function activeDeActiveCategory($id)
    {
        $category = Category::find($id);
        $category->is_active = ! $category->is_active;
        $category->save();

        return $this->sendSuccess(__('messages.common.status_updated_successfully'));
    }
}
