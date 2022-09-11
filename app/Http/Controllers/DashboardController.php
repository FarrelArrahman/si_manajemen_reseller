<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use App\Models\Order;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Ambil nomor whatsapp dan ubah ke dalam bentuk link whatsapp
        $customerServiceWhatsapp = substr_replace(Configuration::configName('customer_service_phone_number'), "62", 0, 1);
        // Ambil data jumlah reseller, varian produk, pesanan dan staff untuk keperluan statistik
        $stats = (object) [
            'reseller' => User::where('role', 'reseller')->count(),
            'product_variant' => ProductVariant::all()->count(),
            'order' => Order::all()->count(),
            'staff' => User::where('role', 'staff')->count(),
        ];

        return view('dashboard.index', compact('customerServiceWhatsapp', 'stats'));
    }
}
