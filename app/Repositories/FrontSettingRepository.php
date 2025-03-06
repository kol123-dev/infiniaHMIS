<?php

namespace App\Repositories;

use App\Models\FrontSetting;
use Arr;

class FrontSettingRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'key',
        'value',
        'type',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return FrontSetting::class;
    }

    public function updateFrontSetting($input)
    {
        if (isset($input['about_us_image']) && ! empty($input['about_us_image'])) {

            $frontSetting = FrontSetting::where('key', '=', 'about_us_image')->first();
            $frontSetting->clearMediaCollection(FrontSetting::PATH);
            $frontSetting->addMedia($input['about_us_image'])->toMediaCollection(FrontSetting::PATH,
                config('app.media_disc'));
            $frontSetting = $frontSetting->refresh();
            $frontSetting->update(['value' => $frontSetting->logo_url]);
        }
        if (isset($input['home_page_image']) && ! empty($input['home_page_image'])) {

            $frontSetting = FrontSetting::where('key', '=', 'home_page_image')->first();
            $frontSetting->clearMediaCollection(FrontSetting::HOME_IMAGE_PATH);
            $frontSetting->addMedia($input['home_page_image'])->toMediaCollection(FrontSetting::HOME_IMAGE_PATH,
                config('app.media_disc'));
            $frontSetting = $frontSetting->refresh();
            $frontSetting->update(['value' => $frontSetting->logo_url]);
        }
        if (isset($input['home_page_certified_doctor_image']) && ! empty($input['home_page_certified_doctor_image'])) {

            $frontSetting = FrontSetting::where('key', '=', 'home_page_certified_doctor_image')->first();
            $frontSetting->clearMediaCollection(FrontSetting::HOME_IMAGE_PATH);
            $frontSetting->addMedia($input['home_page_certified_doctor_image'])->toMediaCollection(FrontSetting::HOME_IMAGE_PATH,
                config('app.media_disc'));
            $frontSetting = $frontSetting->refresh();
            $frontSetting->update(['value' => $frontSetting->logo_url]);
        }

        $frontSettingInputArray = Arr::only($input, [
            'about_us_title', 'about_us_mission', 'about_us_description',
            'home_page_experience', 'home_page_title', 'home_page_description', 'home_page_certified_doctor_text',
            'home_page_certified_doctor_title', 'home_page_certified_doctor_description', 'terms_conditions',
            'privacy_policy',
            'home_page_box_title', 'home_page_box_description', 'home_page_certified_box_title',
            'home_page_certified_box_description',
            'home_page_step_1_title', 'home_page_step_2_title', 'home_page_step_3_title', 'home_page_step_4_title',
            'home_page_step_1_description', 'home_page_step_2_description', 'home_page_step_3_description',
            'home_page_step_4_description',
            'appointment_title', 'appointment_description',
        ]);

        foreach ($frontSettingInputArray as $key => $value) {
            FrontSetting::where('key', '=', $key)->first()->update(['value' => $value]);
        }
    }
}
