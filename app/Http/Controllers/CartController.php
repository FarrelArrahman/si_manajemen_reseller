<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\ProductVariant;
use DataTables;
use Illuminate\Http\Request;
use Storage;

class CartController extends Controller
{
    /**
     * Display a listing of the resource for DataTables.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_dt(Request $request)
    {
        $data = Cart::where([
                'reseller_id' => auth()->user()->reseller->id,
                'status' => Cart::ACTIVE
            ])
            ->latest()
            ->first();

        return DataTables::of($data->cartDetail ?? [])
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $actionBtn = '<button data-cart-detail-id="' . $row->id . '" class="btn btn-link p-0 text-danger me-1 ms-1 removefromcart-button"><i class="fa fa-trash fa-sm"></i></button>';
                return $actionBtn;
            })
            ->editColumn('photo', function($row){                
                return '<img style="object-fit: cover; width: 64px; height: 64px;" src="' . Storage::url($row->productVariant->photo) . '">';
            })
            ->addColumn('product_name', function($row) {
                return $row->productVariant->product->product_name . " (" . $row->productVariant->product_variant_name . ")";
            })
            ->editColumn('reseller_price', function($row) {
                return "Rp. " . number_format($row->productVariant->reseller_price, 0, '', '.');
            })
            ->editColumn('quantity', function($row) {
                $quantity = '<input data-cart-detail-id="' . $row->id . '" type="number" class="form-control change-quantity" value="' . $row->quantity . '" min="1" max="' . $row->productVariant->stock . '">';
                return $quantity;
            })
            ->rawColumns(['action', 'photo', 'quantity'])
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $error = "";
        $message = "";
        $success = true;

        $cart = Cart::where([
            'reseller_id' => auth()->user()->reseller->id,
            'status' => Cart::ACTIVE
        ])->latest()->first();

        if( ! $cart) {
            $cart = Cart::create([
                'reseller_id' => auth()->user()->reseller->id,
                'status' => Cart::ACTIVE
            ]);
        }

        $productInCart = $cart->cartDetail;
        $productVariant = ProductVariant::find($request->product_variant_id);
        $productVariantInCart = $cart->cartDetail->where('product_variant_id', $productVariant->id)->first();

        if(count($productInCart) == 0 || ! $productVariantInCart) {
            if($productVariant->stock < $request->quantity) {
                $error = "insufficient_stock";
            } else {
                CartDetail::create([
                    'cart_id' => $cart->id,
                    'product_variant_id' => $request->product_variant_id,
                    'quantity' => $request->quantity
                ]);
            }
        } else {
            if($productVariant->stock < ($request->quantity + $productVariantInCart->quantity)) {
                $error = "insufficient_stock";
            } else {
                $productVariantInCart->update([
                    'quantity' => $productVariantInCart->quantity + $request->quantity
                ]);
            }            
        }
        
        if($error == "insufficient_stock") {
            $success = false;
            $message = "Stok tidak mencukupi, silahkan cek kembali keranjang dan masukkan jumlah kurang atau sama dengan stok yang tersedia.";
        } else {
            $message = "Berhasil menambahkan varian produk ke keranjang.";
        }

        return response()->json([
            'success' => $success,
            'type' => 'add_product_variant_to_cart',
            'message' => $message,
            'data' => [],
            'statusCode' => 200
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $cart = Cart::with('cartDetail.productVariant.product')
            ->where([
                'reseller_id' => auth()->user()->reseller->id,
                'status' => Cart::ACTIVE
            ])
            ->latest()
            ->first();

        return response()->json([
            'success' => true,
            'type' => 'show_cart',
            'message' => 'Daftar produk pada keranjang',
            'data' => $cart,
            'statusCode' => 200
        ], 200);
    }

    /**
     * Change cart item quantity.
     *
     * @param  int  $product
     * @return \Illuminate\Http\Response
     */
    public function changeQuantity(Request $request, CartDetail $cartDetail)
    {
        $cartDetail->update([
            'quantity' => $request->quantity,
        ]);
        
        if($cartDetail) {
            return response()->json([
                'success' => true,
                'type' => 'change_quantity',
                'message' => 'Berhasil mengubah jumlah produk yang dipesan.',
                'data' => [],
                'statusCode' => 200
            ], 200);
        }

        return response()->json([
            'success' => false,
            'type' => 'change_quantity',
            'message' => 'Gagal mengubah jumlah produk yang dipesan, silahkan coba lagi.',
            'data' => [],
            'statusCode' => 404
        ], 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function removeCartItem(CartDetail $cartDetail)
    {
        $cartDetail->delete();

        return response()->json([
            'success' => true,
            'type' => 'remove_item_from_cart',
            'message' => 'Berhasil membatalkan produk dari keranjang.',
            'data' => [],
            'statusCode' => 200
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function removeAll()
    {
        $cart = Cart::where([
            'reseller_id' => auth()->user()->reseller->id,
            'status' => Cart::ACTIVE
        ])->latest()->first();

        if($cart && $cart->cartDetail && count($cart->cartDetail) > 0) {
            $cart->cartDetail()->delete();
        }

        if($cart) {
            $cart->delete();
        }

        return response()->json([
            'success' => true,
            'type' => 'remove_all_item_from_cart',
            'message' => 'Berhasil mengosongkan keranjang.',
            'data' => [],
            'statusCode' => 200
        ], 200);
    }
}
