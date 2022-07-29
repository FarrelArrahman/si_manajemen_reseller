<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use App\Models\Order;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $configuration = Configuration::first();
        $customerServiceWhatsapp = substr_replace($configuration->customer_service_phone_number, "62", 0, 1);
        $stats = (object) [
            'reseller' => User::where('role', 'reseller')->count(),
            'product_variant' => ProductVariant::all()->count(),
            'order' => Order::all()->count(),
            'staff' => User::where('role', 'staff')->count(),
        ];

        return view('dashboard.index', compact('customerServiceWhatsapp', 'stats'));
    }
}
