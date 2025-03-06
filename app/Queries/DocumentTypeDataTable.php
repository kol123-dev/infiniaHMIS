<?php

namespace App\Queries;

use App\Models\DocumentType;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class DocumentTypeDataTable
 */
class DocumentTypeDataTable
{
    public function get(): Builder
    {
        $query = DocumentType::query();

        return $query->select('document_types.*');
    }
}
