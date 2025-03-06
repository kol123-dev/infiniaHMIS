<?php

namespace App\Repositories;

use App\Models\CallLog;

/**
 * Class CallLogRepository
 *
 * @version July 3, 2020, 9:12 am UTC
 */
class CallLogRepository extends BaseRepository
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
        return CallLog::class;
    }
}
