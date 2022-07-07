<?php

namespace App\View\Composers;

use App\Models\Reseller;
use Illuminate\View\View;

class VerificationRequestComposer
{
    /**
     * @param View $view
     */
    public function compose(View $view)
    {
        $pending_reseller_count = Reseller::where('reseller_status', Reseller::PENDING)->count();

        $view->with('pending_reseller_count', $pending_reseller_count);
    }
}