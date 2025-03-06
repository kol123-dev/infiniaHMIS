<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'razorpay-payment-success',
        'ipd-razorpay-payment-success',
        'phonepe-payment-success',
        'bill-phonepe-payment-success',
        'medicine-purchase-stripe-success',
        'medicine-purchase-razorpay-success',
        'purchase-medicine-phonepe-payment-success',
        'medicine-bill-razorpay-success',
        'medicine-bill-phonepe-payment-success',
        'web-razorpay-payment-success',
        'appointment-razorpay-payment-success',
        'purchase-medicine-flutterwave-payemnt-success',
        'appointment-phonepe-payment-success',
    ];
}
