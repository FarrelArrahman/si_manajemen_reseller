<?php

namespace App\View\Composers;

use App\Models\OrderPayment;
use Illuminate\View\View;

class OrderPaymentVerificationRequestComposer
{
    /**
     * @param View $view
     */
    public function compose(View $view)
    {
        if(auth()->user()->isAdmin()) {
            $pending_order_payment_count = OrderPayment::where('status', OrderPayment::PENDING)->count();
            $view->with('pending_order_payment_count', $pending_order_payment_count);
        }

    }
}