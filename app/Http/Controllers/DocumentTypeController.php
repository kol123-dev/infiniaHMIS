<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDocumentTypeRequest;
use App\Http\Requests\UpdateDocumentTypeRequest;
use App\Models\Document;
use App\Models\DocumentType;
use App\Repositories\DocumentTypeRepository;

class DocumentTypeController extends AppBaseController
{
    /** @var DocumentTypeRepository */
    private $documentTypeRepository;

    public function __construct(DocumentTypeRepository $documentTypeRepo)
    {
        $this->documentTypeRepository = $documentTypeRepo;
    }

    public function index()
    {
        return view('document_types.index');
    }

    public function store(CreateDocumentTypeRequest $request)
    {
        $input = $request->all();

        $this->documentTypeRepository->create($input);

        return $this->sendSuccess(__('messages.document.document_type').' '.__('messages.common.saved_successfully'));
    }

    public function show(DocumentType $documentType)
    {
        $documents = $documentType->documents;

        if (! getLoggedInUser()->hasRole('Admin')) {
            $documents = Document::whereUploadedBy(getLoggedInUser()->id)->whereDocumentTypeId($documentType->id)->get();
        }

        return view('document_types.show', compact('documentType', 'documents'));
    }

    public function edit(DocumentType $documentType)
    {
        return $this->sendResponse($documentType, 'Document Type retrieved successfully.');
    }

    public function update(DocumentType $documentType, UpdateDocumentTypeRequest $request)
    {
        $this->documentTypeRepository->update($request->all(), $documentType->id);

        return $this->sendSuccess(__('messages.document.document_type').' '.__('messages.common.updated_successfully'));
    }

    public function destroy(DocumentType $documentType)
    {
        $documentTypeModel = [
            Document::class,
        ];

        $result = canDelete($documentTypeModel, 'document_type_id', $documentType->id);

        if ($result) {
            return $this->sendError(__('messages.document.document_type').' '.__('messages.common.cant_be_deleted'));
        }

        $this->documentTypeRepository->delete($documentType->id);

        return $this->sendSuccess(__('messages.document.document_type').' '.__('messages.common.deleted_successfully'));
    }
}
