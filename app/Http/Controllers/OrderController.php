<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Configuration;
use App\Models\Courier;
use App\Models\Order;
use App\Models\OrderType;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Traits\Rajaongkir;

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
        //
    }

    /**
     * Display a listing of the resource for DataTables.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_dt(Request $request)
    {
        $data = [];
        
        if(auth()->user()->isAdmin) {
            $data = Order::all();
        } else {
            $data = auth()->user()->reseller->orders;
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row) {
                $actionBtn = '<a href="' . route('user.edit', ['role' => $role, 'user' => $row->id]) . '" data-id="' . $row->id . '" class="btn btn-link p-0 text-warning me-1 ms-1"><i class="fa fa-edit fa-sm"></i></a>';
                return $actionBtn;
            })
            ->editColumn('status', function($row) {
                return $row->statusBadge();
            })
            ->filter(function ($instance) use ($request) {
                if($request->get('status') != null) {
                    $instance->where('status', $request->get('status'));
                }
                
                if( ! empty($request->get('search'))) {
                     $instance->where(function($w) use ($request){
                        $search = $request->get('search');
                        $w->orWhere('name', 'LIKE', "%$search%");
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
        $weight = 0;
        $orderType = OrderType::all();
        $courier = Courier::all();
        $cart = Cart::where([
            'reseller_id' => auth()->user()->reseller->id,
            'status' => Cart::ACTIVE
        ])
        ->latest()
        ->first();
        
        foreach($cart->cartDetail as $item) {
            $weight += $item->quantity * $item->productVariant->weight;
        }

        if($weight < 1000) $weight = 1000;

        $configuration = Configuration::first();

        return view('order.create', compact('orderType', 'cart', 'configuration', 'courier', 'weight'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $orderType = OrderType::find($request->order_type);
        $insufficientStock = false;
        $weight = 0;
        $cart = Cart::where([
            'reseller_id' => auth()->user()->reseller->id,
            'status' => Cart::ACTIVE
        ])
        ->latest()
        ->first();
        
        foreach($cart->cartDetail as $item) {
            if($item->quantity > $item->productVariant->stock) {
                $insufficientStock = true;
                break;
            } 
            $weight += $item->quantity * $item->productVariant->weight;
        }

        if($insufficientStock) {
            return redirect()->back()->withInput()->withErrors(['insufficient_stock' => 'Terdapat stok produk yang tidak mencukupi jumlah pesanan. Silakan cek kembali pada menu Produk.']);
        }

        $order = Order::create([
            'code' => $orderType->code . date('Ymdhis'),
            'ordered_by' => auth()->user()->reseller->id,
            'handled_by' => null,
            'notes' => $request->notes,
            'discount' => 0,
            'address' => auth()->user()->reseller->address,
            'province' => auth()->user()->reseller->province,
            'city' => auth()->user()->reseller->city,
            'postal_code' => auth()->user()->reseller->postal_code,
            'order_type_id' => $orderType->id,
            'date' => date('Y-m-d'),
            'status' => Order::
        ]);

        $message = 'Berhasil melakukan pemesanan. Harap tunggu konfirmasi oleh Admin berupa link shopee dari pemesanan tersebut.';
        return redirect()->route('order.index')->with(['success' => $message]);
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
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}
