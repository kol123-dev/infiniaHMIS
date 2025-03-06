<?php

namespace App\Queries;

use App\Models\NoticeBoard;
use Illuminate\Database\Query\Builder;

/**
 * Class NoticeBoardDataTable
 */
class NoticeBoardDataTable
{
    public function get(): Builder
    {
        return NoticeBoard::select('notice_boards.*');
    }
}
