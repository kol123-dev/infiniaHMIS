<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDiagnosisCategoryRequest;
use App\Http\Requests\UpdateDiagnosisCategoryRequest;
use App\Models\DiagnosisCategory;
use App\Models\PatientDiagnosisTest;
use App\Repositories\DiagnosisCategoryRepository;

class DiagnosisCategoryController extends AppBaseController
{
    /**
     * @var DiagnosisCategoryRepository
     */
    private $categoryRepository;

    public function __construct(DiagnosisCategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function index()
    {
        return view('diagnosis_categories.index');
    }

    public function store(CreateDiagnosisCategoryRequest $request)
    {
        $input = $request->all();
        $this->categoryRepository->create($input);

        return $this->sendSuccess(__('messages.diagnosis_category.diagnosis_category').' '.__('messages.common.saved_successfully'));
    }

    public function show(DiagnosisCategory $diagnosisCategory)
    {
        return view('diagnosis_categories.show', compact('diagnosisCategory'));
    }

    public function edit(DiagnosisCategory $diagnosisCategory)
    {
        return $this->sendResponse($diagnosisCategory, 'Diagnosis Category retrieved successfully.');
    }

    public function update(UpdateDiagnosisCategoryRequest $request, DiagnosisCategory $diagnosisCategory)
    {
        $input = $request->all();
        $this->categoryRepository->update($input, $diagnosisCategory->id);

        return $this->sendSuccess(__('messages.diagnosis_category.diagnosis_category').' '.__('messages.common.updated_successfully'));
    }

    public function destroy(DiagnosisCategory $diagnosisCategory)
    {
        $diagnosisCategoryModal = [
            PatientDiagnosisTest::class,
        ];

        $result = canDelete($diagnosisCategoryModal, 'category_id', $diagnosisCategory->id);

        if ($result) {
            return $this->sendError(__('messages.diagnosis_category.diagnosis_category').' '.__('messages.common.cant_be_deleted'));
        }

        $diagnosisCategory->delete();

        return $this->sendSuccess(__('messages.diagnosis_category.diagnosis_category').' '.__('messages.common.deleted_successfully'));
    }
}
