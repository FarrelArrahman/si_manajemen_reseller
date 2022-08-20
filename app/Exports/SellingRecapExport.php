<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SellingRecapExport implements FromCollection, WithHeadings
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            "Tanggal Pemesanan", 
            "Kode Pesanan", 
            "Nama Reseller", 
            "Staf", 
            "Jumlah Penjualan",
            "Biaya Pengiriman",
            "Total"
        ];
    }
}
