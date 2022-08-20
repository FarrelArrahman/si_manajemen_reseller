<?php

namespace App\Http\Controllers;

use App\Events\ResellerEvent;
use App\Events\AdminEvent;
use App\Jobs\SendEmailJob;
use App\Models\Cart;
use App\Models\Configuration;
use App\Models\Courier;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderPayment;
use App\Models\OrderShipping;
use App\Models\ProductVariant;
use App\Traits\Rajaongkir;
use DataTables;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use Rajaongkir;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        return view('order.index');
    }

    /**
     * Display a listing of the resource for DataTables.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_dt(Request $request)
    {
        $data = [];
        
        if(auth()->user()->isAdmin() || auth()->user()->isStaff()) {
            $data = Order::select('*')->latest();
        } else {
            $data = Order::where('ordered_by', auth()->user()->reseller->id)->latest();
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row) {
                $actionBtn = '<button data-order-id="' . $row->id . '" data-bs-toggle="modal" data-bs-target="#order_detail_modal" class="btn btn-link p-0 text-info me-1 ms-1 showorderdetail-button"><i class="fa fa-search fa-sm"></i></button>';
                if($row->isPending()) {
                    $actionBtn .= '<button data-order-id="' . $row->id . '" class="btn btn-link p-0 text-danger me-1 ms-1 deleteorder-button"><i class="fa fa-trash fa-sm"></i></button>';
                }
                return $actionBtn;
            })
            ->addColumn('reseller', function($row) {
                return $row->reseller->shop_name;
            })
            ->editColumn('status', function($row) {
                return $row->statusBadge();
            })
            ->editColumn('date', function($row) {
                return $row->date->format('Y-m-d');
            })
            ->editColumn('total_price', function($row) {
                return "Rp. " . number_format($row->total_price, 0, '', '.');
            })
            ->filter(function ($instance) use ($request) {                
                if($request->get('status') != null) {
                    $instance->where('status', $request->get('status'));
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

                    $instance->orWhereHas('reseller', function($q) use ($search){
                        $q->where('shop_name', 'LIKE', "%$search%");
                    });
                }
            })
            ->rawColumns(['action','status'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $totalWeight = 0;
        $courier = Courier::all();
        $configuration = new Configuration();
        $cart = Cart::where([
            'reseller_id' => auth()->user()->reseller->id,
            'status' => Cart::ACTIVE
        ])
        ->latest()
        ->first();

        if($cart) {
            foreach($cart->cartDetail as $item) {
                $totalWeight += $item->quantity * $item->productVariant->weight;
            }
    
            if($totalWeight < 1000) $totalWeight = 1000;
        }

        return view('order.create', compact('cart', 'configuration', 'courier', 'totalWeight'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'courier' => 'required|string',
            'service' => 'required|string',
        ]);

        // dd($request->all());
        $totalWeight = 1000;
        $totalPrice = 0;

        $cart = Cart::where([
            'reseller_id' => auth()->user()->reseller->id,
            'status' => Cart::ACTIVE
        ])
        ->latest()
        ->first();

        if($cart->cartDetail->where('quantity_less_or_equal_than_stock', false)->count() > 0) {
            return redirect()->back()->withInput()->withErrors(['insufficient_stock' => 'Terdapat stok produk yang tidak mencukupi jumlah pesanan. Silakan cek kembali pada menu Produk.']);
        }

        $orderCount = Order::whereDate('created_at', today())->count();

        $order = Order::create([
            'code' => date('Ymd') . sprintf('%04d', $orderCount + 1),
            'ordered_by' => auth()->user()->reseller->id,
            'handled_by' => null,
            'notes' => $request->notes,
            'discount' => 0,
            'total_price' => 0,
            'date' => now(),
            'status' => Order::PENDING,
            'admin_notes' => null,
        ]);

        $orderDetails = [];
        // $productVariants = [];
        foreach($cart->cartDetail as $item) {
            $totalWeight += $item->quantity * $item->productVariant->weight;
            $totalPrice += $item->quantity * $item->productVariant->reseller_price;

            // $productVariants[] = [
            //     'id' => $item->productVariant->id,
            //     'stock' => ['-', $item->quantity]
            // ];

            $orderDetails[] = [
                'order_id' => $order->id,
                'product_variant_id' => $item->product_variant_id,
                'quantity' => $item->quantity,
                'price' => $item->productVariant->reseller_price,
            ];
        }

        batch()->insert(
            new OrderDetail, 
            ['order_id', 'product_variant_id', 'quantity', 'price'],
            $orderDetails,
            500
        );

        // batch()->update(
        //     new ProductVariant, 
        //     $productVariants,
        //     'id'
        // );

        if($cart->cartDetail) $cart->cartDetail()->delete();
        if($cart) $cart->delete();

        $order->total_price = $totalPrice;
        $order->saveQuietly();

        $serviceDetail = $this->serviceDetailAPI(
            Configuration::configName('city'), 
            auth()->user()->reseller->city,
            $totalWeight,
            $request->courier,
            $request->service
        );
        
        $courier = Courier::where('code', $request->courier)->first();
        
        $orderShipping = OrderShipping::create([
            'order_id' => $order->id,
            'address' => auth()->user()->reseller->shop_address,
            'province' => auth()->user()->reseller->province,
            'city' => auth()->user()->reseller->city,
            'postal_code' => auth()->user()->reseller->postal_code,
            'courier_id' => $courier->id,
            'service' => $serviceDetail->service,
            'total_weight' => $totalWeight,
            'total_price' => $serviceDetail->cost[0]->value
        ]);

        $orderPayment = OrderPayment::create([
            'order_id' => $order->id,
            'amount' => $order->total_price + $orderShipping->total_price,
            'date' => now(),
            'payment_status' => OrderPayment::NOT_YET,
            'proof_of_payment' => null,
            'approved_by' => null,
            'admin_notes' => null,
        ]);

        $message = 'Pesanan #' . $order->code . ' telah diajukan. Silakan verifikasi pada menu Pesanan.';

        $data = [
            'id' => $order->reseller->user->id,
            'success' => true,
            'action' => "update_pending_order_count",
            'message' => $message
        ];

        // Notify Admin via Website Notification
        AdminEvent::dispatch($data);

        // Notify Admin via Email
        dispatch(new SendEmailJob([
            'email' => Configuration::configName('email'),
            'subject' => "Pengajuan Pesanan [#" . $order->code . "]",
            'message' => $message,
            'button' => 'Ke Menu Pesanan',
            'url' => route('order.index')
        ]));

        return redirect()->route('order.index')->with(['success' => "Berhasil mengajukan pesanan. Harap tunggu konfirmasi dari Admin."]);
    }

    /**
     * Display the detail of the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function detail($order)
    {
        $order = Order::with(['orderDetail.productVariant.product', 'reseller', 'orderShipping.courier'])->find($order);
        $order->status_badge = auth()->user()->isAdmin() || auth()->user()->isStaff() 
            ? $order->verificationStatus()
            : $order->statusBadge();
        $order->date_formatted = $order->date->isoFormat('dddd, DD MMMM Y hh:mm:ss');

        if($order) {
            return response()->json([
                'success' => true,
                'type' => 'detail_order',
                'message' => 'Data order',
                'data' => $order,
                'statusCode' => 200
            ], 200);
        }
        
        return response()->json([
            'success' => false,
            'type' => 'detail_order',
            'message' => 'Data order tidak ditemukan!',
            'data' => [],
            'statusCode' => 404
        ], 404);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Verify order.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function verify(Request $request, Order $order)
    {
        $message = "";
        $action = "show_verified_order";
        $success = true;
        
        $order->status = $request->status;
        $order->handled_by = auth()->user()->id;

        if($request->status == Order::APPROVED) {
            $message = "Pesanan Anda: #" . $order->code . " telah berhasil terverifikasi oleh Admin. Segera lanjutkan ke proses pembayaran pada menu Pembayaran.";
        } else if($request->status == Order::REJECTED) {
            foreach($order->orderDetail as $item) {
                $item->productVariant->update([
                    'stock' => $item->productVariant->stock + $item->quantity
                ]);
            }

            $success = false;
            $action = "show_unverified_order";
            $order->admin_notes = $request->admin_notes;
            $message = "Pesanan Anda: #" . $order->code . " ditolak. Silakan cek kembali detail pesanan pada menu pesanan.";
        }

        $data = [
            'id' => $order->reseller->user->id,
            'success' => $success,
            'action' => $action,
            'message' => $message
        ];
            
        if($order->save()) {
            // Notify Reseller via Website Notification
            ResellerEvent::dispatch($data);

            // Notify Reseller via Email
            dispatch(new SendEmailJob([
                'email' => $order->reseller->user->email,
                'subject' => $request->status == Order::APPROVED ? "Pesanan Diterima" : "Pesanan Ditolak",
                'message' => $message,
                'button' => $request->status == Order::APPROVED ? "Ke Halaman Pembayaran" : "Ke Halaman Pesanan",
                'url' => $request->status == Order::APPROVED ? route('order_payment.index') : route('order.index')
            ]));

            return response()->json([
                'success' => true,
                'type' => 'verify_order_status',
                'message' => 'Berhasil melakukan verifikasi pesanan.',
                'data' => $request->all(),
                'statusCode' => 200
            ], 200);
        }

        return response()->json([
            'success' => false,
            'type' => 'verify_order_status',
            'message' => 'Gagal melakukan verifikasi pesanan, silahkan coba lagi.',
            'data' => [],
            'statusCode' => 422
        ], 422);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        if($order->orderShipping) $order->orderShipping->delete();
        if($order->orderPayment) $order->orderPayment->delete();
        if($order->orderDetail->count() > 0) $order->orderDetail()->delete();

        if($order->delete()) {
            return response()->json([
                'success' => true,
                'type' => 'delete_order',
                'message' => 'Pesanan berhasil dihapus.',
                'data' => [],
                'statusCode' => 200
            ], 200);
        }

        return response()->json([
            'success' => false,
            'type' => 'delete_order',
            'message' => 'Gagal menghapus pesanan, silahkan coba lagi.',
            'data' => [],
            'statusCode' => 404
        ], 404);
    }

    /**
     * Return the pending order count.
     *
     * @return \Illuminate\Http\Response
     */
    public function pending()
    {
        $count = Order::where('status', Order::PENDING)->count();

        return response()->json([
            'success' => true,
            'type' => 'pending_order_count',
            'message' => 'Jumlah pesanan pending',
            'data' => [
                'count' => $count,
            ],
            'statusCode' => 200
        ], 200);
    }
}
