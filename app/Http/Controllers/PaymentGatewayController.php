<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Laracasts\Flash\Flash;
use Redirect;

class PaymentGatewayController extends AppBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $credentials = Setting::pluck('value','key');

        return view('payment_gateway.index', compact('credentials'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $inputArr = Arr::except($input, ['_token']);

        $defaults = [
            'stripe_enable' => 0,
            'razorpay_enable' => 0,
            'flutterwave_enable' => 0,
            'phone_pe_enable' => 0,
            'paystack_enable' => 0,
            'paypal_enable' => 0,
        ];

        $inputArr += $defaults;

        foreach ($inputArr as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        Flash::success(__('messages.settings').' '.__('messages.common.updated_successfully'));

        return redirect(route('payment-gateways.index'));

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
