<?php 

namespace App\Traits;

use App\Models\Order;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

trait GeneralSellingReport {

    private $series = [];
    private $type = "datetime";
    private $interval;
    private $categories;
    private $start;
    private $end;

    /**
     * Inisialisasi trait
     * 
     * @param  string  $interval
     * @param  string  $start
     * @param  string  $end
     */
    private function sellingReportInit($interval, $start, $end)
    {   
        $this->interval = $interval;
        $this->start = $start;
        $this->end = $end;

        return $this;
    }

    /**
     * Fungsi untuk mengolah semua laporan penjualan.
     */
    private function generateSellingReport()
    {
        if( ! $this->valid()) {
            return $this->result();
        }

        $this->setDate();

        $chart = [
            'selling_price' => [[
                'name' => "Penjualan (Harga)",
                'data' => $this->generateSellingByPrice(),
            ]],
            'selling_quantity' => [[
                'name' => "Penjualan (Qty)",
                'data' => $this->generateSellingByQuantity()
            ]],
        ];

        $table = [
            'top_selling_price' => [
                'format' => "money",
                'data' => $this->generateTopSellingByPrice()
            ],
            'top_selling_quantity' => [
                'format' => null,
                'data' => $this->generateTopSellingByQuantity()
            ],
        ];

        return [
            'type' => $this->type,
            'categories' => $this->categories,
            'chart' => $chart,
            'table' => $table,
        ];
    }

    /**
     * Fungsi untuk mengolah laporan penjualan (harga).
     */
    private function generateSellingByPrice()
    {
        return $this->setSeries($this->getOrders("total_price"));
    }

    /**
     * Fungsi untuk mengolah laporan penjualan (qty).
     */
    private function generateSellingByQuantity()
    {
        return $this->setSeries($this->getOrders("quantity"));
    }

    /**
     * Fungsi untuk mengolah laporan penjualan 10 produk terbanyak (harga).
     */
    private function generateTopSellingByPrice()
    {
        return $this->getTopSelling("total_price");
    }

    /**
     * Fungsi untuk mengolah laporan penjualan 10 produk terbanyak (qty).
     */
    private function generateTopSellingByQuantity()
    {
        return $this->getTopSelling("quantity");
    }

    private function valid()
    {
        if($this->interval == "" || $this->start == "" || $this->end == "") {
            return false;
        }

        return true;
    }

    private function setDate()
    {
        if($this->interval == "month") {
            $explodeStart = explode('-', $this->start);
            $explodeEnd = explode('-', $this->end);

            $this->start = Carbon::createFromDate($explodeStart[0], $explodeStart[1], 1)->startOfMonth()->format('Y-m-d');
            $this->end = Carbon::createFromDate($explodeEnd[0], $explodeEnd[1], 1)->endOfMonth()->format('Y-m-d');
            $this->type = "category";
        } else if($this->interval == "year") {
            $this->start = Carbon::createFromDate($this->start, 1, 1)->format('Y-m-d');
            $this->end = Carbon::createFromDate($this->end, 12, 31)->format('Y-m-d');
            $this->type = "category";
        }
    }

    private function getOrders($sumBy)
    {
        $orders = [];
        $selectRaw = $groupByRaw = "DATE(date)";
        
        if($this->interval == "month") {
            $selectRaw = "DATE_FORMAT(date, '%Y-%m')";
            $groupByRaw = "DATE_FORMAT(date, '%Y-%m')";
        } else if($this->interval == "year") {
            $selectRaw = $groupByRaw = "YEAR(date)";
        }

        $orders = DB::table('orders');
        
        if($sumBy == "quantity") {
            $orders = $orders->join('order_details', 'orders.id', '=', 'order_details.order_id');
        }

        $orders = $orders->select([
                DB::raw($selectRaw . " as date"),
                DB::raw("SUM($sumBy) as total"),
            ])
            ->where('status', Order::DONE)
            ->whereBetween('date', [$this->start, $this->end])
            ->groupBy(DB::raw($groupByRaw))
            ->get()
            ->toArray();
        
        return $orders;
    }

    private function getTopSelling($sumBy)
    {
        $selectRaw = "SUM(quantity)";

        if($sumBy == "total_price") {
            $selectRaw = "SUM(quantity * order_details.price)";
        }

        $orders = DB::table('orders')
            ->select([
                'product_name',
                'product_variant_name',
                DB::raw($selectRaw . " as total")
            ])
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->join('product_variants', 'order_details.product_variant_id', '=', 'product_variants.id')
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->where('orders.status', Order::DONE)
            ->whereBetween('orders.date', [$this->start, $this->end])
            ->groupBy('product_variant_id')
            ->orderBy('total', 'DESC')
            ->take(10)
            ->get()
            ->toArray();
        
        return $orders;
    }

    private function setSeries($orders)
    {
        $series = [];
        $format = "Y-m-d";
        $step = "1 day";
        
        if($this->interval == "month") {
            $format = "Y-m";
            $step = "1 month";
        } else if($this->interval == "year") {
            $format = "Y";
            $step = "1 year";
        }
        
        $period = CarbonPeriod::create($this->start, $step, $this->end);

        $this->categories = $this->interval != "date" 
            ? $this->setCategories($period, $format) 
            : null;

        foreach($period as $item) {
            $date = $item->format($format);
            $key = array_search($date, array_column($orders, 'date'));

            $series[] = [
                'x' => $date,
                'y' => $key !== false ? $orders[$key]->total : 0
            ];
        }

        return $series;
    }

    private function setCategories($period, $format)
    {
        $categories = [];
        foreach($period as $item) {
            $categories[] = $item->format($format);
        }

        return $categories;
    }
}

?>