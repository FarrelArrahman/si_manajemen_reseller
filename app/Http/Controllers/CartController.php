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
     * Mengambil data keranjang ke dalam format datatable.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_dt(Request $request)
    {
        // Cek dulu apakah ada data keranjang dari reseller tersebut
        $data = Cart::where([
                'reseller_id' => auth()->user()->reseller->id,
                'status' => Cart::ACTIVE
            ])
            ->latest()
            ->first();

        // Kembalikan datatable dalam format json
        return DataTables::of($data->cartDetail ?? [])
            ->addIndexColumn()
            ->addColumn('action', function($row){
                // Tambahkan kolom action yang berisi tombol hapus varian produk dari keranjang
                $actionBtn = '<button data-cart-detail-id="' . $row->id . '" class="btn btn-link p-0 text-danger me-1 ms-1 removefromcart-button"><i class="fa fa-trash fa-sm"></i></button>';
                return $actionBtn;
            })
            ->editColumn('photo', function($row){                
                // Tampilkan foto dari varian produk
                return '<img style="object-fit: cover; width: 64px; height: 64px;" src="' . Storage::url($row->productVariant->photo) . '">';
            })
            ->addColumn('product_name', function($row) {
                // Tampilkan nama produk dan variannya
                return $row->productVariant->product->product_name . " (" . $row->productVariant->product_variant_name . ")";
            })
            ->editColumn('reseller_price', function($row) {
                // Tampilkan harga varian produk yang telah diformat ke dalam rupiah
                return "Rp. " . number_format($row->productVariant->reseller_price, 0, '', '.');
            })
            ->editColumn('quantity', function($row) {
                // Tampilkan form input quantity dari varian produk
                $quantity = '<input data-cart-detail-id="' . $row->id . '" type="number" class="form-control change-quantity" value="' . $row->quantity . '" min="1" max="' . $row->productVariant->stock . '">';
                return $quantity;
            })
            ->rawColumns(['action', 'photo', 'quantity'])
            ->make(true);
    }

    /**
     * Menambahkan varian produk ke dalam keranjang.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Inisialisasi error (kosong), message (kosong) dan success = true
        $error = "";
        $message = "";
        $success = true;

        // Cek dulu apakah ada data keranjang dari reseller tersebut
        $cart = Cart::where([
            'reseller_id' => auth()->user()->reseller->id,
            'status' => Cart::ACTIVE
        ])->latest()->first();

        // Jika data keranjang dari reseller tersebut belum ada,
        if( ! $cart) {
            // maka buat data keranjang untuk reseller tersebut
            $cart = Cart::create([
                'reseller_id' => auth()->user()->reseller->id,
                'status' => Cart::ACTIVE
            ]);
        }

        // Ambil seluruh varian produk dari keranjang reseller tersebut
        $productInCart = $cart->cartDetail;
        // Ambil data varian produk dari model ProductVariant berdasarkan product_variant_id
        $productVariant = ProductVariant::find($request->product_variant_id);
        // Cek apakah varian produk yang akan ditambahkan ke dalam keranjang
        // Sama dengan varian produk telah ditambahkan sebelumnya
        $productVariantInCart = $cart->cartDetail->where('product_variant_id', $productVariant->id)->first();

        // Jika tidak ada varian produk dalam keranjang
        // atau varian produk belum pernah ditambahkan ke dalam keranjang,
        if(count($productInCart) == 0 || ! $productVariantInCart) {
            // Jika stok varian produk kurang dari jumlah pesan,
            if($productVariant->stock < $request->quantity) {
                // Atur jenis errornya menjadi stok tidak mencukupi
                $error = "insufficient_stock";
            } else {
                // Jika stok varian cukup dengan jumlah pesan,
                // maka tambahkan varian produk ke dalam keranjang beserta jumlah 
                CartDetail::create([
                    'cart_id' => $cart->id,
                    'product_variant_id' => $request->product_variant_id,
                    'quantity' => $request->quantity
                ]);
            }
        // Jika varian produk sudah pernah ditambahkan sebelumnya,
        } else {
            // Jika stok varian produk kurang dari tambahan jumlah pesan,
            if($productVariant->stock < ($request->quantity + $productVariantInCart->quantity)) {
                // Atur jenis errornya menjadi stok tidak mencukupi
                $error = "insufficient_stock";
            } else {
                // Jika stok varian cukup dengan jumlah pesan,
                // maka ubah jumlah pesan varian produk dalam keranjang
                // jumlah pesan di keranjang + tambahan jumlah pesan
                $productVariantInCart->update([
                    'quantity' => $productVariantInCart->quantity + $request->quantity
                ]);
            }            
        }
        
        // Jika errornya stok tidak mencukupi,
        if($error == "insufficient_stock") {
            // maka atur success nya menjadi false
            $success = false;
            // dan berikan pesan error berikut
            $message = "Stok tidak mencukupi, silahkan cek kembali keranjang dan masukkan jumlah kurang atau sama dengan stok yang tersedia.";
        } else {
            // Jika tidak ada error, maka berikan pesan berhasil berikut
            $message = "Berhasil menambahkan varian produk ke keranjang.";
        }

        // Kembalikan data dalam bentuk json
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
        // Cek dulu apakah ada data keranjang dari reseller tersebut
        $cart = Cart::with('cartDetail.productVariant.product')
            ->where([
                'reseller_id' => auth()->user()->reseller->id,
                'status' => Cart::ACTIVE
            ])
            ->latest()
            ->first();

        // Kembalikan data keranjang dalam bentuk json 
        return response()->json([
            'success' => true,
            'type' => 'show_cart',
            'message' => 'Daftar produk pada keranjang',
            'data' => $cart,
            'statusCode' => 200
        ], 200);
    }

    /**
     * Ubah jumlah pesan varian produk pada keranjang.
     *
     * @param  int  $product
     * @return \Illuminate\Http\Response
     */
    public function changeQuantity(Request $request, CartDetail $cartDetail)
    {
        // Ubah jumlah pesan sesuai dengan input dari reseller
        $cartDetail->update([
            'quantity' => $request->quantity,
        ]);
        
        // Jika ubah jumlah pesan berhasil,
        if($cartDetail) {
            // maka kembalikan pesan berhasil dalam bentuk json
            return response()->json([
                'success' => true,
                'type' => 'change_quantity',
                'message' => 'Berhasil mengubah jumlah produk yang dipesan.',
                'data' => [],
                'statusCode' => 200
            ], 200);
        }

        // Jika gagal, maka kembalikan pesan error dalam bentuk json
        return response()->json([
            'success' => false,
            'type' => 'change_quantity',
            'message' => 'Gagal mengubah jumlah produk yang dipesan, silahkan coba lagi.',
            'data' => [],
            'statusCode' => 404
        ], 404);
    }

    /**
     * Hapus salah satu varian produk pada keranjang.
     *
     * @return \Illuminate\Http\Response
     */
    public function removeCartItem(CartDetail $cartDetail)
    {
        // Hapus salah satu varian produk
        $cartDetail->delete();

        // Kembalikan pesan berhasil dalam bentuk json
        return response()->json([
            'success' => true,
            'type' => 'remove_item_from_cart',
            'message' => 'Berhasil membatalkan produk dari keranjang.',
            'data' => [],
            'statusCode' => 200
        ], 200);
    }

    /**
     * Hapus semua varian produk pada keranjang.
     *
     * @return \Illuminate\Http\Response
     */
    public function removeAll()
    {
        // Cek dulu apakah ada data keranjang dari reseller tersebut
        $cart = Cart::where([
            'reseller_id' => auth()->user()->reseller->id,
            'status' => Cart::ACTIVE
        ])->latest()->first();

        // Jika ada data keranjang dan keranjang tersebut ada isinya,
        if($cart && $cart->cartDetail && count($cart->cartDetail) > 0) {
            // maka hapus seluruh varian produk pada keranjang
            $cart->cartDetail()->delete();
            // dan hapus data keranjang
            $cart->delete();
        }

        // Kembalikan pesan berhasil dalam bentuk json
        return response()->json([
            'success' => true,
            'type' => 'remove_all_item_from_cart',
            'message' => 'Berhasil mengosongkan keranjang.',
            'data' => [],
            'statusCode' => 200
        ], 200);
    }
    
    /**
     * Mengecek jumlah pesan pada keranjang.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkQtyOfCartDetail(ProductVariant $productVariant)
    {
        // Inisialiasi awal jumlah pesan = 0
        $quantity = 0;

        // Cek dulu apakah ada data keranjang dari reseller tersebut
        $cart = Cart::where([
            'reseller_id' => auth()->user()->reseller->id,
            'status' => Cart::ACTIVE
        ])->latest()->first();

        // Jika ada data keranjang dan keranjang tersebut ada isinya,
        if($cart && $cartDetail = $cart->cartDetail->where('product_variant_id', $productVariant->id)->first()) {
            // maka set jumlah pesannya sesuai dengan varian produk 
            // yang telah masuk ke dalam keranjang
            $quantity = $cartDetail->quantity;
        }

        // Kembalikan jumlah pesan varian produk dalam bentuk json
        return response()->json([
            'success' => true,
            'type' => 'check_qty_of_cart_detail',
            'message' => 'Jumlah varian produk pada keranjang',
            'data' => [
                'quantity' => $quantity
            ],
            'statusCode' => 200
        ], 200);
    }
}
