<?php

namespace App\View\Composers;

use App\Models\Order;
use Illuminate\View\View;

class OrderVerificationRequestComposer
{
    /**
     * @param View $view
     */
    public function compose(View $view)
    {
        if(auth()->user()->isAdmin() || auth()->user()->isStaff()) {
            $pending_order_count = Order::where('status', Order::PENDING)->count();
            $view->with('pending_order_count', $pending_order_count);
        }

    }
}