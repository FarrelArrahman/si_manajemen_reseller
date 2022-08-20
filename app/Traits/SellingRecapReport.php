<?php 

namespace App\Traits;

use App\Models\Order;
use Illuminate\Support\Facades\DB;

trait SellingRecapReport {

    private $start;
    private $end;

    /**
     * Inisialisasi trait
     * 
     * @param  string  $start
     * @param  string  $end
     */
    private function sellingRecapReportInit($start, $end)
    {
        $this->start = $start;
        $this->end = $end;

        return $this;
    }

    /**
     * Fungsi untuk mengolah laporan rekap penjualan.
     */
    private function generateSellingRecapReport()
    {
        $table = [
            'selling_recap' => [
                'data' => $this->getSellingRecap()
            ],
        ];

        return [
            'table' => $table,
            'start' => $this->start,
            'end' => $this->end,
        ];
    }

    private function getSellingRecap()
    {
        $orders = DB::table('orders')->select([
                'orders.date',
                'orders.code',
                'resellers.shop_name as reseller',
                'users.name as staff',
                'orders.total_price as selling_price',
                'order_shippings.total_price as shipping_price',
                DB::raw("(orders.total_price + order_shippings.total_price) as grand_total"),
            ])
            ->join('resellers', 'orders.ordered_by', '=', 'resellers.id')
            ->join('users', 'orders.handled_by', '=', 'users.id')
            ->join('order_shippings', 'orders.id', '=', 'order_shippings.order_id')
            ->where('orders.status', Order::DONE)
            ->whereBetween(DB::raw('DATE(orders.date)'), [$this->start, $this->end])
            ->groupBy('orders.code')
            ->orderBy('orders.date')
            ->get();
        
        return $orders;
    }
}

?>