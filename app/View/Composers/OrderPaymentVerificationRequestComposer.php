<?php

namespace App\View\Composers;

use App\Models\Order;
use App\Models\OrderPayment;
use Illuminate\View\View;

class OrderPaymentVerificationRequestComposer
{
    /**
     * @param View $view
     */
    public function compose(View $view)
    {
        $pending_order_payment_count = 0;

        if(auth()->user()->isAdmin() || auth()->user()->isStaff()) {
            $pending_order_payment_count = OrderPayment::where('payment_status', OrderPayment::PENDING)->count();
        } else {
            $pending_order_payment_count = Order::where('ordered_by', auth()->user()->reseller->id)->where('status', Order::APPROVED)->whereHas('orderPayment', function($orderPayment) {
                $orderPayment->whereIn('payment_status', [OrderPayment::NOT_YET, OrderPayment::REJECTED]);
            })->count();
        }
        
        $view->with('pending_order_payment_count', $pending_order_payment_count);
    }
}