<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductSellingExport implements FromCollection, WithHeadings
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
            "Tanggal", 
            "Nama Barang", 
            "Jumlah Qty",
            "Total"
        ];
    }
}
