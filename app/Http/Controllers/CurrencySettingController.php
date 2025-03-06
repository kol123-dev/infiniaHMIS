<?php

namespace App\Http\Controllers;

use App\Http\Requests\Createcurrency_settingRequest;
use App\Http\Requests\UpdateCurrencySettingRequest;
use App\Models\CurrencySetting;
use App\Models\Setting;
use App\Repositories\currency_settingRepository;
use Flash;
use Illuminate\Http\Request;

class CurrencySettingController extends AppBaseController
{
    /** @var currency_settingRepository */
    private $currencySettingRepository;

    public function __construct(currency_settingRepository $currencySettingRepo)
    {
        $this->currencySettingRepository = $currencySettingRepo;
    }

    public function index(Request $request)
    {
        $currencySettings = $this->currencySettingRepository->all();

        return view('currency_settings.index')->with('currencySettings', $currencySettings);
    }

    public function create()
    {
        return view('currency_settings.create');
    }

    public function store(Createcurrency_settingRequest $request)
    {
        $input = $request->all();

        $this->currencySettingRepository->create($input);

        return $this->sendSuccess(__('messages.currency.currency') . '' . __('messages.common.saved_successfully'));
    }

    public function show($id)
    {
        $currencySetting = $this->currencySettingRepository->find($id);

        if (empty($currencySetting)) {
            Flash::error(__('messages.currency.currency_setting_not_found'));

            return redirect(route('currencySettings.index'));
        }

        return view('currency_settings.show')->with('currencySetting', $currencySetting);
    }

    public function edit(CurrencySetting $currencySetting)
    {
        return $this->sendResponse($currencySetting, 'Currency retrieved successfully.');
    }

    public function update(CurrencySetting $currencySetting, UpdateCurrencySettingRequest $request)
    {
        $input = $request->all();

        $this->currencySettingRepository->update($input, $currencySetting->id);

        return $this->sendSuccess(__('messages.currency.currency') . '' . __('messages.common.updated_successfully'));
    }

    public function destroy(CurrencySetting $currencySetting)
    {
        $currencyCode = $currencySetting->currency_code;
        $currentCurrency = Setting::where('key', 'current_currency')->first();
        if ($currentCurrency && $currentCurrency->value === strtolower($currencyCode)) {
            return $this->sendError(__('messages.currency.currency') . ' ' . __('messages.currency.in_use_cannot_delete'));
        }
        $this->currencySettingRepository->delete($currencySetting->id);

        return $this->sendSuccess(__('messages.currency.currency') . '' . __('messages.common.deleted_successfully'));
    }
}
