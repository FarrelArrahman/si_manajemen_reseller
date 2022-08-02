<?php

namespace App\Http\Controllers;

use App\Events\ResellerEvent;
use App\Events\AdminEvent;
use App\Models\Configuration;
use App\Models\Order;
use App\Models\OrderPayment;
use DataTables;
use Illuminate\Http\Request;
use Storage;

class OrderPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $configuration = Configuration::first();
        
        return view('order_payment.index', compact('configuration'));
    }

    /**
     * Display a listing of the resource for DataTables.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_dt(Request $request)
    {
        $data = [];
        
        if(auth()->user()->isAdmin()) {
            $data = Order::select('*')->whereNotIn('status', [Order::REJECTED, Order::CANCELED])->latest();
        } else {
            $data = Order::select('*')->whereNotIn('status', [Order::REJECTED, Order::CANCELED])->where('ordered_by', auth()->user()->reseller->id)->latest();
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row) {
                $actionBtn = '<button data-reseller-account="' . $row->reseller->bank_name . ' ' . $row->reseller->account_number . ' (A.N '. $row->reseller->account_holder_name . ')" data-total-price="Rp. ' . number_format($row->orderPayment->amount, 0, '', '.') . '" data-payment-status="' . $row->orderPayment->payment_status . '" data-proof-of-payment="' . ($row->orderPayment->proof_of_payment ? Storage::url($row->orderPayment->proof_of_payment) : "") . '" data-order-code="' . $row->code . '" data-order-id="' . $row->id . '" data-bs-toggle="modal" data-bs-target="#order_payment_modal" class="btn btn-link p-0 text-info me-1 ms-1 showpaymentdetail-button ' . (auth()->user()->isAdmin() && $row->orderPayment->proof_of_payment == null ? 'text-muted disabled' : '') . '"><i class="fa fa-search fa-sm"></i></button>';
                return $actionBtn;
            })
            ->addColumn('proof_of_payment', function($row) {
                return $row->orderPayment->proofOfPayment();
            })
            ->editColumn('payment_status', function($row) {
                return $row->orderPayment->statusBadge();
            })
            ->editColumn('date', function($row) {
                return $row->date->format('Y-m-d');
            })
            ->editColumn('total_price', function($row) {
                return number_format($row->total_price, 0, '', '.');
            })
            ->filter(function ($instance) use ($request) {                
                if($request->get('status') != null) {
                    $status = $request->get('status');
                    $instance->whereHas('orderPayment', function($orderPayment) use ($status) {
                        $orderPayment->where('payment_status', $status);
                    });
                }

                if($request->get('begin_date') != null) {
                    $instance->whereDate('date', '>=', $request->get('begin_date'));
                }

                if($request->get('end_date') != null) {
                    $instance->whereDate('date', '<=', $request->get('end_date'));
                }
                
                if( ! empty($request->get('search'))) {
                    $search = $request->get('search');

                    $instance->where(function($w) use ($search){
                        $w->orWhere('code', 'LIKE', "%$search%");
                        $w->orWhere('total_price', 'LIKE', "%$search%");
                        $w->orWhere('date', 'LIKE', "%$search%");
                    });
                }
            })
            ->rawColumns(['action','status','order_type','proof_of_payment','payment_status'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OrderPayment  $orderPayment
     * @return \Illuminate\Http\Response
     */
    public function show(OrderPayment $orderPayment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OrderPayment  $orderPayment
     * @return \Illuminate\Http\Response
     */
    public function edit(OrderPayment $orderPayment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OrderPayment  $orderPayment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OrderPayment $orderPayment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OrderPayment  $orderPayment
     * @return \Illuminate\Http\Response
     */
    public function destroy(OrderPayment $orderPayment)
    {
        //
    }

    /**
     * Upload proof of payment.
     *
     * @param  \App\Models\Order
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request, Order $order)
    {
        $file = $request->file('file');

        if($file) {
            $upload = $order->orderPayment->update([
                'date' => now(),
                'payment_status' => OrderPayment::PENDING,
                'proof_of_payment' => $file->store('public/proof_of_payment'),
            ]);

            if($upload) {
                return response()->json([
                    'success' => true,
                    'type' => 'upload_payment',
                    'message' => 'Berhasil meng-upload bukti pembayaran.',
                    'data' => $order,
                    'statusCode' => 200
                ], 200);

                $data = [
                    'id' => $order->reseller->user->id,
                    'success' => true,
                    'action' => "update_pending_payment_count",
                    'message' => 'Pembayaran pesanan #"' . $order->code . '" telah diajukan. Silakan verifikasi pada menu Pembayaran.'
                ];
            
                AdminEvent::dispatch($data);
            }

            return response()->json([
                'success' => false,
                'type' => 'upload_payment',
                'message' => 'Gagal meng-upload bukti pembayaran. Silahkan coba lagi.',
                'data' => $order,
                'statusCode' => 422
            ], 422);
        } else {
            return response()->json([
                'success' => false,
                'type' => 'upload_payment_no_file',
                'message' => 'Harap pilih file yang akan di-upload.',
                'data' => $order,
                'statusCode' => 422
            ], 422);
        }
    }

    /**
     * Verify payment.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function verify(Request $request, Order $order)
    {
        $action = "show_verified_payment";
        $success = true;
        
        $order->orderPayment->payment_status = $request->status;

        if($request->status == OrderPayment::APPROVED) {
            // Catat perubahan stok pada varian produk jika diterima
            foreach($order->orderDetail as $item) {
                ProductVariantStockLog::create([
                    'product_variant_id' => $item->productVariant->id,
                    'qty_change' => 0 - $item->quantity,
                    'qty_before' => $item->productVariant->stock + $item->quantity,
                    'qty_after' => $item->productVariant->stock - $item->quantity,
                    'date' => now(),
                    'note' => "Penjualan produk kepada Reseller",
                    'handled_by' => auth()->user()->id,
                ]);
            }

            $order->orderPayment->approved_by = auth()->user()->id;
            $order->status = Order::DONE;
            $message = "Pembayaran pesanan #" . $order->code . " telah berhasil terverifikasi oleh Admin.";
        } else if($request->status == Order::REJECTED) {
            $success = false;
            $message = "Pembayaran pesanan #" . $order->code . " ditolak. Silahkan cek kembali pembayaran Anda.";
        }

        $data = [
            'id' => $order->reseller->user->id,
            'success' => $success,
            'action' => $action,
            'message' => $message
        ];
    
        ResellerEvent::dispatch($data);
        
        if($order->orderPayment->save() && $order->save()) {
            return response()->json([
                'success' => true,
                'type' => 'verify_payment_status',
                'message' => 'Berhasil melakukan verifikasi pembayaran.',
                'data' => $request->all(),
                'statusCode' => 200
            ], 200);
        }

        return response()->json([
            'success' => false,
            'type' => 'verify_payment_status',
            'message' => 'Gagal melakukan verifikasi pembayaran, silahkan coba lagi.',
            'data' => [],
            'statusCode' => 422
        ], 422);
    }

    /**
     * Return the pending order count.
     *
     * @return \Illuminate\Http\Response
     */
    public function pending()
    {
        $count = Order::whereHas('orderPayment', function($orderPayment) {
            $orderPayment->where('payment_status', OrderPayment::PENDING);
        })->count();

        return response()->json([
            'success' => true,
            'type' => 'pending_payment_count',
            'message' => 'Jumlah pembayaran pending',
            'data' => [
                'count' => $count,
            ],
            'statusCode' => 200
        ], 200);
    }
}
