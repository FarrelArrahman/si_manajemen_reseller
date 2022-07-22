<?php

namespace App\Http\Controllers;

use App\Events\ResellerEvent;
use App\Events\AdminEvent;
use App\Models\Cart;
use App\Models\Configuration;
use App\Models\Courier;
use App\Models\ExternalOrderLink;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderPayment;
use App\Models\OrderShipping;
use App\Models\OrderType;
use App\Traits\Rajaongkir;
use App\Traits\OwnerConfiguration;
use DataTables;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use Rajaongkir, OwnerConfiguration;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orderType = OrderType::all();
        
        return view('order.index', compact('orderType'));
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
            $data = Order::select('*');
        } else {
            $data = Order::where('ordered_by', auth()->user()->reseller->id)->latest();
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row) {
                $actionBtn = '<button data-order-id="' . $row->id . '" data-bs-toggle="modal" data-bs-target="#order_detail_modal" class="btn btn-link p-0 text-info me-1 ms-1 showorderdetail-button"><i class="fa fa-search fa-sm"></i></button>';
                $actionBtn .= '<button data-order-id="' . $row->id . '" class="btn btn-link p-0 text-danger me-1 ms-1 deleteorder-button"><i class="fa fa-trash fa-sm"></i></button>';
                return $actionBtn;
            })
            ->addColumn('order_type', function($row) {
                return $row->orderType->statusBadge();
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
                
                if($request->get('order_type_id') != null) {
                    $instance->where('order_type_id', $request->get('order_type_id'));
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
            ->rawColumns(['action','status','order_type'])
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
        $orderType = OrderType::all();
        $courier = Courier::all();
        $cart = Cart::where([
            'reseller_id' => auth()->user()->reseller->id,
            'status' => Cart::ACTIVE
        ])
        ->latest()
        ->first();
        
        foreach($cart->cartDetail as $item) {
            $totalWeight += $item->quantity * $item->productVariant->weight;
        }

        if($totalWeight < 1000) $totalWeight = 1000;

        $configuration = Configuration::first();

        return view('order.create', compact('orderType', 'cart', 'configuration', 'courier', 'totalWeight'));
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
        $orderType = OrderType::find($request->order_type);
        $totalWeight = 1000;
        $totalPrice = 0;

        $cart = Cart::where([
            'reseller_id' => auth()->user()->reseller->id,
            'status' => Cart::ACTIVE
        ])
        ->latest()
        ->first();
        
        foreach($cart->cartDetail as $item) {
            if($item->quantity > $item->productVariant->stock) {
                return redirect()->back()->withInput()->withErrors(['insufficient_stock' => 'Terdapat stok produk yang tidak mencukupi jumlah pesanan. Silakan cek kembali pada menu Produk.']);
            } 
        }

        $orderCount = Order::whereDate('created_at', today())->count();

        $order = Order::create([
            'code' => $orderType->code . date('Ymd') . sprintf('%04d', $orderCount + 1),
            'ordered_by' => auth()->user()->reseller->id,
            'handled_by' => null,
            'notes' => $request->notes,
            'discount' => 0,
            'total_price' => 0,
            'address' => auth()->user()->reseller->shop_address,
            'province' => auth()->user()->reseller->province,
            'city' => auth()->user()->reseller->city,
            'postal_code' => auth()->user()->reseller->postal_code,
            'order_type_id' => $orderType->id,
            'date' => now(),
            'status' => Order::PENDING,
            'rejection_reason' => null,
        ]);

        foreach($cart->cartDetail as $item) {
            $totalWeight += $item->quantity * $item->productVariant->weight;
            $totalPrice += $item->quantity * $item->productVariant->reseller_price;

            $item->productVariant->update([
                'stock' => $item->productVariant->stock - $item->quantity
            ]);

            $orderDetail = OrderDetail::create([
                'order_id' => $order->id,
                'product_variant_id' => $item->product_variant_id,
                'quantity' => $item->quantity,
                'price' => $item->productVariant->reseller_price,
                'discount' => 0,
            ]);

            $item->delete();
        }

        $order->total_price = $totalPrice;
        $order->saveQuietly();

        $cart->delete();

        if($orderType->isExpedition()) {
            $serviceDetail = $this->serviceDetailAPI(
                $this->configuration()->city, 
                auth()->user()->reseller->city,
                $totalWeight,
                $request->courier,
                $request->service
            );

            $courier = Courier::where('code', $request->courier)->first();

            $orderShipping = OrderShipping::create([
                'courier_id' => $courier->id,
                'order_id' => $order->id,
                'service' => $serviceDetail->service,
                'total_weight' => $totalWeight,
                'total_price' => $serviceDetail->cost[0]->value
            ]);
            
            $message = 'Berhasil mengajukan pemesanan melalui ekspedisi. Silakan lanjutkan ke pembayaran agar pesanan dapat diproses oleh Admin.';
        } else {
            $message = 'Berhasil mengajukan pemesanan melalui link shopee. Harap tunggu konfirmasi oleh Admin berupa link shopee dari pemesanan tersebut.';
        }

        $orderPayment = OrderPayment::create([
            'order_id' => $order->id,
            'amount' => $order->total_price,
            'date' => now(),
            'payment_status' => OrderPayment::NOT_YET,
            'proof_of_payment' => null,
            'approved_by' => null,
            'admin_notes' => null,
        ]);

        $data = [
            'id' => $order->reseller->user->id,
            'success' => true,
            'action' => "update_pending_order_count",
            'message' => 'Pesanan "' . $order->code . '" telah diajukan. Silakan verifikasi pada menu Pesanan.'
        ];
    
        AdminEvent::dispatch($data);

        return redirect()->route('order.index')->with(['success' => $message]);
    }

    /**
     * Display the detail of the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function detail($order)
    {
        $order = Order::with(['orderDetail.productVariant.product', 'reseller', 'orderType', 'orderShipping.courier', 'externalOrderLink'])->find($order);
        $order->status_badge = auth()->user()->isAdmin() 
            ? $order->verificationStatus()
            : $order->statusBadge();
        $order->orderType->status_badge = $order->orderType->statusBadge();
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
        $action = "show_verified_order";
        $success = true;
        
        $order->status = $request->status;

        if($request->status == Order::APPROVED) {
            $order->handled_by = auth()->user()->id;
            
            if($order->orderType->isShopee()) {
                $externalOrderLink = ExternalOrderLink::create([
                    'order_id' => $order->id,
                    'link' => $request->link,
                    'added_by' => auth()->user()->id,
                    'valid_until' => today()->addDay(),
                ]);    
            }

            $message = "Pesanan #" . $order->code . " telah berhasil terverifikasi oleh Admin.";
        } else if($request->status == Order::REJECTED) {
            $success = false;
            $action = "show_unverified_order";
            $order->admin_notes = $request->admin_notes;
            $message = "Pesanan #" . $order->code . " ditolak. Silahkan cek kembali detail pesanan pada menu pesanan";
        }

        $data = [
            'id' => $order->reseller->user->id,
            'success' => $success,
            'action' => $action,
            'message' => $message
        ];
    
        ResellerEvent::dispatch($data);
        
        if($order->save()) {
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
        if($order->orderType->isExpedition()) {
            $order->orderShipping->delete();
        }

        foreach($order->orderDetail as $item) {
            $item->productVariant->update([
                'stock' => $item->productVariant->stock + $item->quantity
            ]);

            $item->delete();
        }

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
