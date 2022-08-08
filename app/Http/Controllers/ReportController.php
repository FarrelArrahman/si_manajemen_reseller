<?php

namespace App\Http\Controllers;

use App\Exports\SellingRecapExport;
use Illuminate\Http\Request;
use App\Traits\GeneralSellingReport;
use App\Traits\SellingRecapReport;
use App\Traits\ProductSellingReport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    use GeneralSellingReport, SellingRecapReport, ProductSellingReport;

    /**
     * Display general report.
     *
     * @return \Illuminate\Http\Response
     */
    public function general()
    {
        return view('report.generalreport');
    }

    /**
     * API for general selling report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function generalSellingReport(Request $request)
    {
        return response()->json([
            'success' => true,
            'type' => 'general_selling_report',
            'message' => 'Laporan total penjualan',
            'data' => $this->sellingReportInit($request->interval, $request->start, $request->end)->generateSellingReport(),
            // 'data' => $request->all(),
            'statusCode' => 200
        ], 200);
    }

    /**
     * Display selling recap report.
     *
     * @return \Illuminate\Http\Response
     */
    public function sellingRecap()
    {
        return view('report.sellingRecap');
    }

    /**
     * API for selling recap report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sellingRecapReport(Request $request)
    {
        return response()->json([
            'success' => true,
            'type' => 'selling_recap_report',
            'message' => 'Laporan rekap penjualan',
            'data' => $this->sellingRecapReportInit($request->start, $request->end)->generateSellingRecapReport(),
            // 'data' => $request->all(),
            'statusCode' => 200
        ], 200);
    }

    /**
     * Export selling recap report as spreadsheet.
     *
     * @return \Illuminate\Http\Response
     */
    public function sellingRecapExcel(Request $request)
    {
        if($request->start == "" || $request->end == "") {
            return abort(404);
        }

        $title = "SellingRecap_" . $request->start . "_" . $request->end;
        $data = $this->sellingRecapReportInit($request->start, $request->end)->getSellingRecap();
        return Excel::download(new SellingRecapExport($data), $title);
    }

    /**
     * Display product selling report.
     *
     * @return \Illuminate\Http\Response
     */
    public function productSelling()
    {
        return view('report.productSelling');
    }

    /**
     * API for product selling report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function productSellingReport(Request $request)
    {
        return response()->json([
            'success' => true,
            'type' => 'product_selling_report',
            'message' => 'Laporan penjualan produk',
            'data' => $this->productSellingReportInit($request->product, $request->start, $request->end)->generateProductSellingReport(),
            // 'data' => $request->all(),
            'statusCode' => 200
        ], 200);
    }
}
