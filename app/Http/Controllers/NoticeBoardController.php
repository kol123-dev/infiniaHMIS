<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateNoticeBoardRequest;
use App\Http\Requests\UpdateNoticeBoardRequest;
use App\Models\NoticeBoard;
use App\Repositories\NoticeBoardRepository;

class NoticeBoardController extends AppBaseController
{
    /** @var NoticeBoardRepository */
    private $noticeBoardRepository;

    public function __construct(NoticeBoardRepository $noticeBoardRepo)
    {
        $this->noticeBoardRepository = $noticeBoardRepo;
    }

    public function index()
    {
        return view('notice_boards.index');
    }

    public function store(CreateNoticeBoardRequest $request)
    {
        $input = $request->all();
        $this->noticeBoardRepository->create($input);
        $this->noticeBoardRepository->createNotification();

        return $this->sendSuccess(__('messages.notice_boards').' '.__('messages.common.saved_successfully'));
    }

    public function show($id)
    {
        $noticeBoard = NoticeBoard::find($id);

        return $this->sendResponse($noticeBoard, 'Notice Board retrieved successfully.');
    }

    public function edit(NoticeBoard $noticeBoard)
    {
        return $this->sendResponse($noticeBoard, 'Notice Board retrieved successfully.');
    }

    public function update(NoticeBoard $noticeBoard, UpdateNoticeBoardRequest $request)
    {
        $this->noticeBoardRepository->update($request->all(), $noticeBoard->id);

        return $this->sendSuccess(__('messages.notice_boards').' '.__('messages.common.updated_successfully'));
    }

    public function destroy(NoticeBoard $noticeBoard)
    {
        $noticeBoard->delete();

        return $this->sendSuccess(__('messages.notice_boards').' '.__('messages.common.deleted_successfully'));
    }
}
