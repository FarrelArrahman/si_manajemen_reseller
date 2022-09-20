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
        $pending_order_count = 0;
        
        if(auth()->user()->isAdmin() || auth()->user()->isStaff()) {
            $pending_order_count = Order::where('status', Order::PENDING)->count();
        } else {
            $pending_order_count = auth()->user()->reseller->orders->where('status', Order::REJECTED)->count();
        }
        
        $view->with('pending_order_count', $pending_order_count);
    }
}