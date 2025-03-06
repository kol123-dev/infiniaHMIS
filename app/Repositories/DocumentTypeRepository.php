<?php

namespace App\Repositories;

use App\Models\DocumentType;

/**
 * Class DocumentTypeRepository
 *
 * @version February 18, 2020, 4:24 am UTC
 */
class DocumentTypeRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'name',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return DocumentType::class;
    }
}
