<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\AppBaseController;
use App\Models\NoticeBoard;
use App\Queries\NoticeBoardDataTable;
use DataTables;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NoticeBoardController extends AppBaseController
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return Datatables::of((new NoticeBoardDataTable())->get())->make(true);
        }

        return view('employees.notice_boards.index');
    }

    public function show($id)
    {
        $noticeBoard = NoticeBoard::find($id);

        //        return view('employees.notice_boards.show')->with('noticeBoard', $noticeBoard);
        return $this->sendResponse($noticeBoard, 'Notice Board retaived successfully.');
    }
}
