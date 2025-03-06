<?php

namespace App\Http\Controllers;

use App\Models\FrontSetting;
use App\Repositories\FrontSettingRepository;
use Flash;
use Illuminate\Http\Request;

class FrontSettingController extends AppBaseController
{
    /** @var FrontSettingRepository */
    private $frontSettingRepository;

    public function __construct(FrontSettingRepository $frontSettingRepository)
    {
        $this->frontSettingRepository = $frontSettingRepository;
    }

    public function index(Request $request)
    {
        $frontSettings = FrontSetting::pluck('value', 'key')->toArray();
        $sectionName = $request->section === null ? 'cms' : $request->section;

        return view("front_settings.$sectionName", compact('frontSettings', 'sectionName'));
    }

    public function update(Request $request)
    {
        $this->frontSettingRepository->updateFrontSetting($request->all());

        Flash::success(__('messages.front_settings').' '.__('messages.common.saved_successfully'));

        return redirect(route('front.settings.index'));
    }
}
