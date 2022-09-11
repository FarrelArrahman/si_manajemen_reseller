<?php

namespace App\Http\Controllers;

use App\Models\ProductVariant;
use DataTables;
use Illuminate\Http\Request;

class ProductVariantStockLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_dt(Request $request, ProductVariant $productVariant)
    {        
        $data = $productVariant->productVariantStockLog;

        return DataTables::of($data)
            ->editColumn('date', function($row) {
                return $row->date->format('Y-m-d');
            })
            ->editColumn('handled_by', function($row) {
                return $row->handledBy->name;
            })
            ->addIndexColumn()
            ->make(true);
    }

}
