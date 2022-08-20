<?php 

namespace App\Traits;

use App\Models\Order;
use Illuminate\Support\Facades\DB;

trait ProductSellingReport {

    private $product;
    private $start;
    private $end;

    /**
     * Inisialisasi trait
     * 
     * @param  string  $product
     * @param  string  $start
     * @param  string  $end
     */
    private function productSellingReportInit($product, $start, $end)
    {
        $this->product = $product;
        $this->start = $start;
        $this->end = $end;

        return $this;
    }

    /**
     * Fungsi untuk mengolah laporan penjualan produk.
     */
    private function generateProductSellingReport()
    {
        $table = [
            'product_selling' => [
                'data' => $this->getProductSelling()
            ],
        ];

        return [
            'table' => $table,
            'start' => $this->start,
            'end' => $this->end,
        ];
    }

    private function getProductSelling()
    {
        $orders = DB::table('order_details')->select([
                DB::raw('DATE(orders.date) as date'),
                'products.product_name',
                'product_variants.product_variant_name',
                DB::raw('SUM(order_details.quantity) as quantity'),
                DB::raw("SUM(order_details.quantity * order_details.price) as grand_total"),
            ])
            ->join('product_variants', 'order_details.product_variant_id', '=', 'product_variants.id')
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->whereBetween(DB::raw('DATE(orders.date)'), [$this->start, $this->end])
            ->where('product_variants.id', $this->product)
            ->where('orders.status', Order::DONE)
            ->groupBy(DB::raw('DATE(orders.date)'))
            ->groupBy('product_variants.id')
            ->orderBy('orders.date')
            ->orderBy('products.product_name')
            ->orderBy('product_variants.product_variant_name')
            ->get();
        
        return $orders;
    }
}

?>