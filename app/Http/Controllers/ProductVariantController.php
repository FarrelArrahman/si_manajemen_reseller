<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantStockLog;
use DataTables;
use Illuminate\Http\Request;
use Storage;

class ProductVariantController extends Controller
{
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
    public function index_dt(Request $request, Product $product)
    {        
        $data = ProductVariant::select('*')->where('product_id', $product->id);

        if($request->get('show') != null) {
            if($request->get('show') == 0) {
                $data = $data->onlyTrashed();
            } else if($request->get('show') == 1) {
                $data = $data->withTrashed();
            }
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){                
                if($row->trashed() && auth()->user()->isAdmin()) {
                    $actionBtn = '<button data-id="' . $row->id . '" class="btn btn-text text-primary me-1 ms-1 restore-button"><i class="fa fa-undo-alt fa-sm"></i></button>';
                } else {
                    $actionBtn = '<a href="' . route('product_variant.show', ['product' => $row->product->id, 'productVariant' => $row->id]) . '" class="text-info me-1 ms-1"><i class="fa fa-search fa-sm"></i></a>';
                    if(auth()->user()->isAdmin()) {
                        $actionBtn .= '<a href="' . route('product_variant.edit', ['product' => $row->product->id, 'productVariant' => $row->id]) . '" data-id="' . $row->id . '" class="btn btn-link p-0 text-warning me-1 ms-1"><i class="fa fa-edit fa-sm"></i></a>';
                        $actionBtn .= '<button data-product-variant-id="' . $row->id . '" class="btn btn-link p-0 text-danger me-1 ms-1 delete-button"><i class="fa fa-trash-alt fa-sm"></i></button>';
                    }
                }
                return $actionBtn;
            })
            ->addColumn('switch_button', function($row) {
                return $row->statusSwitchButton();
            })
            ->editColumn('photo', function($row){
                return '<a class="image-popup" href="' . Storage::url($row->photo) . '"><img style="object-fit: cover; width: 96px; height: 96px;" src="' . Storage::url($row->photo) . '"></a>';
            })
            ->editColumn('color', function($row) {
                return '<div class="rounded-circle" style="border:1px solid black;background-color:' . $row->color . ';width:20px;height:20px;">&nbsp;</div>';
            })
            ->editColumn('reseller_price', function($row) {
                return number_format($row->reseller_price, 0, '', '.');
            })
            ->filter(function ($instance) use ($request) {
                if($request->get('product_variant_status') != null) {
                    $instance->where('product_variant_status', $request->get('product_variant_status'));
                }
                
                if( ! empty($request->get('search'))) {
                     $instance->where(function($w) use ($request){
                        $search = $request->get('search');
                        $w->orWhere('product_variant_name', 'LIKE', "%$search%");
                        $w->orWhere('reseller_price', 'LIKE', "%$search%");
                        $w->orWhere('stock', 'LIKE', "%$search%");
                    });
                }
            })
            ->rawColumns(['action', 'color', 'switch_button', 'photo'])
            ->make(true);
    }

    /**
     * Display a listing of color.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkVariant(Request $request, $product, $productVariant)
    {
        $exists = false;
        $product = Product::find($product);

        if($product) {
            $exists = $product->productVariants
                ->where('product_variant_name', $productVariant)
                ->first() 
                ? true 
                : false;
        }

        return response()->json([
            'success' => true,
            'type' => 'check_product_variant',
            'message' => 'Cek varian produk',
            'data' => [
                'exists' => $exists
            ],
            'statusCode' => 200
        ], 200);
    }

    /**
     * Display a listing of variant colors.
     *
     * @return \Illuminate\Http\Response
     */
    public function color(Request $request, $productVariant)
    {        
        $data = ProductVariant::where('product_variant_name', $productVariant)->pluck('color');

        return response()->json([
            'success' => true,
            'type' => 'product_variant_color_list',
            'message' => 'List varian warna',
            'data' => $data,
            'statusCode' => 200
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Product $product)
    {
        $productVariant = ProductVariant::select('product_variant_name', 'color')->distinct('product_variant_name')->get()->unique('product_variant_name');
        return view('product_variant.create', compact('product', 'productVariant'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Product $product)
    {
        // dd($request->all());
        $product = Product::find($request->product_id);

        if( ! $product) {
            return redirect()->route('product.index')->with('error', 'Produk tidak ditemukan, tidak dapat menambahkan varian produk.');
        }

        $validator = $request->validate([
            'product_variant_name'  => 'required|string',
            'color'                 => 'nullable|string',
            'stock'                 => 'required',
            'base_price'            => 'required',
            'reseller_price'        => 'required',
            'general_price'         => 'required',
            'weight'                => 'required|numeric',
        ]);

        $productVariant = ProductVariant::create([
            'product_variant_name'      => $request->product_variant_name,
            'product_id'                => $product->id,
            'color'                     => $request->color,
            'stock'                     => $request->stock,
            'base_price'                => str_replace('.', '', $request->base_price),
            'reseller_price'            => str_replace('.', '', $request->reseller_price),
            'general_price'             => str_replace('.', '', $request->general_price),
            'photo'                     => $request->hasFile('photo') ? $request->file('photo')->store('public/products/' . $product->id) : 'public/no-image.png',
            'weight'                    => $request->weight,
            'added_by'                  => auth()->user()->id ?? null,
            'last_edited_by'            => auth()->user()->id ?? null,
            'product_variant_status'    => $request->product_variant_status == 'on' ? 1 : 0,
        ]);

        $productVariantStockLog = ProductVariantStockLog::create([
            'product_variant_id' => $productVariant->id,
            'qty_change' => $request->stock,
            'qty_before' => 0,
            'qty_after' => $request->stock,
            'date' => now(),
            'note' => "Entri awal varian produk",
            'handled_by' => auth()->user()->id,
        ]);

        return redirect()->route('product.show', $product->id)->with('success', 'Berhasil menambahkan varian produk baru.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductVariant  $productVariant
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product, $productVariant)
    {
        $productVariant = ProductVariant::withTrashed()->findOrFail($productVariant);

        return view('product_variant.show', compact('productVariant'));
    }

    /**
     * Display the detail of the specified resource.
     *
     * @param  \App\Models\ProductVariant  $productVariant
     * @return \Illuminate\Http\Response
     */
    public function detail($productVariant)
    {
        $productVariant = ProductVariant::select([
            'id',
            'product_id',
            'product_variant_name',
            'base_price',
            'general_price',
            'reseller_price',
            'stock',
            'color',
            'photo',
            'weight',
            'product_variant_status',
        ])
        ->with('product')
        ->where('product_variant_status', 1)
        ->whereHas('product', function($product) {
            $product->where('product_status', 1);
        })
        ->find($productVariant);

        if($productVariant) {
            $productVariant->base_price_rp = "Rp. " . number_format($productVariant->base_price, 0, '', '.');
            $productVariant->general_price_rp = "Rp. " . number_format($productVariant->general_price, 0, '', '.');
            $productVariant->reseller_price_rp = "Rp. " . number_format($productVariant->reseller_price, 0, '', '.');
            $productVariant->photo = Storage::url($productVariant->photo);
            
            return response()->json([
                'success' => true,
                'type' => 'detail_product_variant',
                'message' => 'Data varian produk',
                'data' => $productVariant,
                'statusCode' => 200
            ], 200);
        }
        
        return response()->json([
            'success' => false,
            'type' => 'detail_product_variant',
            'message' => 'Varian produk tidak ditemukan!',
            'data' => [],
            'statusCode' => 404
        ], 404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProductVariant  $productVariant
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product, ProductVariant $productVariant)
    {
        $productVariantColor = ProductVariant::select('product_variant_name', 'color')->distinct('product_variant_name')->get()->unique('product_variant_name');
        return view('product_variant.edit', compact('productVariant', 'productVariantColor'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProductVariant  $productVariant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product, ProductVariant $productVariant)
    {
        $validator = $request->validate([
            'product_variant_name'  => 'required|string',
            'color'                 => 'nullable|string',
            'stock'                 => 'required',
            'base_price'            => 'required',
            'reseller_price'        => 'required',
            'general_price'         => 'required',
            'weight'                => 'required|numeric',
        ]);

        $photo = $productVariant->photo;
        if($request->hasFile('photo') && $photo != 'public/no-image.png') {
            Storage::delete($photo);
            $photo = $request->file('photo')->store('public/products/' . $productVariant->product->id);
        }

        $productVariant->update([
            'product_variant_name'      => $request->product_variant_name,
            'color'                     => $request->color,
            'stock'                     => $request->stock,
            'base_price'                => str_replace('.', '', $request->base_price),
            'reseller_price'            => str_replace('.', '', $request->reseller_price),
            'general_price'             => str_replace('.', '', $request->general_price),
            'photo'                     => $photo,
            'weight'                    => $request->weight,
            'added_by'                  => auth()->user()->id ?? null,
            'last_edited_by'            => auth()->user()->id ?? null,
            'product_variant_status'    => $request->product_variant_status == 'on' ? 1 : 0,
        ]);

        return redirect()->route('product.show', $productVariant->product->id)->with('success', 'Berhasil mengubah varian produk.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductVariant  $productVariant
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product, ProductVariant $productVariant)
    {
        if($productVariant->delete()) {
            return response()->json([
                'success' => true,
                'type' => 'delete_product_variant',
                'message' => 'Varian produk berhasil dihapus sementara.',
                'data' => [],
                'statusCode' => 200
            ], 200);
        }

        return response()->json([
            'success' => false,
            'type' => 'delete_product_variant',
            'message' => 'Gagal menghapus varian produk, silahkan coba lagi.',
            'data' => [],
            'statusCode' => 404
        ], 404);
    }

    /**
     * Restore the specified resource in storage.
     *
     * @param  int  $product
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request, Product $product, $productVariant)
    {
        $productVariant = ProductVariant::withTrashed()->find($productVariant);
        
        if($productVariant->restore()) {
            return response()->json([
                'success' => true,
                'type' => 'restore_product_variant',
                'message' => 'Varian produk berhasil dipulihkan kembali.',
                'data' => [],
                'statusCode' => 200
            ], 200);
        }

        return response()->json([
            'success' => false,
            'type' => 'restore_product_variant',
            'message' => 'Gagal memulihkan varian produk, silahkan coba lagi.',
            'data' => [],
            'statusCode' => 404
        ], 404);
    }

    /**
     * Change product status.
     *
     * @param  int  $product
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request, Product $product, $productVariant)
    {
        $productVariant = ProductVariant::withTrashed()->find($productVariant);

        $changeStatus = $productVariant->update([
            'product_variant_status'    => $request->product_variant_status ? 1 : 0,
            'last_edited_by'            => auth()->user()->id
        ]);
        
        if($changeStatus && $request->product_variant_status) {
            return response()->json([
                'success' => true,
                'type' => 'change_product_variant_status',
                'message' => 'Produk akan ditampilkan pada pencarian.',
                'data' => [],
                'statusCode' => 200
            ], 200);
        } else {
            return response()->json([
                'success' => true,
                'type' => 'change_product_variant_status',
                'message' => 'Produk akan disembunyikan dari pencarian.',
                'data' => $request->all(),
                'statusCode' => 200
            ], 200);
        }

        return response()->json([
            'success' => false,
            'type' => 'change_product_variant_status',
            'message' => 'Gagal mengubah status produk, silahkan coba lagi.',
            'data' => [],
            'statusCode' => 404
        ], 404);
    }

    /**
     * Change product status.
     *
     * @param  int  $product
     * @return \Illuminate\Http\Response
     */
    public function changeStock(Request $request, Product $product, $productVariant)
    {
        $productVariant = ProductVariant::withTrashed()->find($productVariant);
        
        if($request->stock != 0) {
            $qtyBefore = $productVariant->stock;
            $qtyAfter = $productVariant->stock + $request->stock;

            $changeStock = $productVariant->update([
                'stock'             => $qtyAfter,
                'last_edited_by'    => auth()->user()->id
            ]);

            if($changeStock) {
                $productVariantStockLog = ProductVariantStockLog::create([
                    'product_variant_id' => $productVariant->id,
                    'qty_change' => $request->stock,
                    'qty_before' => $qtyBefore,
                    'qty_after' => $qtyAfter,
                    'date' => now(),
                    'note' => $request->note,
                    'handled_by' => auth()->user()->id,
                ]);

                return response()->json([
                    'success' => true,
                    'type' => 'change_product_variant_stock',
                    'message' => 'Stok berhasil ' . ($request->stock < 0 ? 'dikurangi' : 'ditambahkan') . '.',
                    'data' => [
                        'qty_after' => $qtyAfter
                    ],
                    'statusCode' => 200
                ], 200);
            }

        }

        return response()->json([
            'success' => false,
            'type' => 'change_product_variant_stock',
            'message' => 'Stok tidak diubah.',
            'data' => [],
            'statusCode' => 422
        ], 422);
    }
}
