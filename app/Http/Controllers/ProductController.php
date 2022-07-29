<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Http\Request;
use DataTables;
use Storage;
use Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = Category::all();
        $products = Product::withTrashed()->get();
        
        if($request->show == "allWithTrashed") {
            $products = Product::withTrashed()->get();
        } else if($request->show == "onlyTrashed") {
            $products = Product::onlyTrashed()->get();
        } else {
            $products = Product::all();
        }

        return view('product.index', compact('products','categories'));
    }

    /**
     * Display a listing of the resource for DataTables.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_dt(Request $request)
    {
        $data = Product::select('*');

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                if($row->trashed() && auth()->user()->isAdmin()) {
                    $actionBtn = '<button data-id="' . $row->id . '" class="btn btn-text text-primary me-1 ms-1 restore-button"><i class="fa fa-undo-alt fa-sm"></i></button>';
                } else {
                    $actionBtn = '<a href="' . route('product.show', $row->id) . '" class="text-info me-1 ms-1"><i class="fa fa-search fa-sm"></i></a>';
                    if(auth()->user()->isAdmin()) {
                        $actionBtn .= '<a href="' . route('product.edit', $row->id) . '" data-id="' . $row->id . '" class="btn btn-link p-0 text-warning me-1 ms-1"><i class="fa fa-edit fa-sm"></i></a>';
                        $actionBtn .= '<button data-id="' . $row->id . '" class="btn btn-link p-0 text-danger me-1 ms-1 delete-button"><i class="fa fa-trash-alt fa-sm"></i></button>';
                    }
                }
                return $actionBtn;
            })
            ->editColumn('default_photo', function($row){                
                return '<a class="image-popup" href="' . Storage::url($row->default_photo) . '"><img style="object-fit: cover; width: 96px; height: 96px;" src="' . Storage::url($row->default_photo) . '"></a>';
            })
            ->editColumn('product_status', function($row) {
                return $row->statusBadge();
            })
            ->addColumn('category', function($row) {
                return $row->category->category_name;
            })
            ->addColumn('switch_button', function($row) {
                return $row->statusSwitchButton();
            })
            ->filter(function ($instance) use ($request) {
                if($request->get('product_status') != null) {
                    $instance->where('product_status', $request->get('product_status'));
                }

                if($request->get('category_id') != null) {
                    $instance->where('category_id', $request->get('category_id'));
                }

                if($request->get('show') != null) {
                    if($request->get('show') == 0) {
                        $instance->onlyTrashed();
                    } else if($request->get('show') == 1) {
                        $instance->withTrashed();
                    }
                }
                
                if( ! empty($request->get('search'))) {
                     $instance->where(function($w) use ($request){
                        $search = $request->get('search');
                        $w->orWhere('product_name', 'LIKE', "%$search%");
                    });
                }
            })
            ->rawColumns(['action', 'product_status', 'switch_button', 'default_photo'])
            ->make(true);
    }

    /**
     * Display a listing of the resource as json.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_api(Request $request)
    {
        $products = Product::select(['id', 'product_name']);

        if($request->has('category_id') && $request->category_id != 0) {
            $products = $products->where('category_id', $request->category_id);
        }

        $products = $products->get()->toArray();

        return response()->json([
            'success' => true,
            'type' => 'product_list',
            'message' => 'Daftar produk',
            'data' => $products,
            'statusCode' => 200
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $units = Unit::all();
        $categories = Category::all();
        return view('product.create', compact('units','categories'));
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
        $validator = $request->validate([
            'product_name'      => 'required|string',
            'description'       => 'nullable|string',
            'category_id'       => 'required|exists:App\Models\Category,id',
            'unit_id'           => 'required|exists:App\Models\Unit,id',
            'default_photo'     => 'nullable|file|mimes:jpg,jpeg,png,gif|max:4096',
        ]);

        $product = Product::create([
            'product_name'      => $request->product_name,
            'description'       => $request->description,
            'category_id'       => $request->category_id,
            'unit_id'           => $request->unit_id,
            'default_photo'     => $request->hasFile('default_photo') ? $request->file('default_photo')->store('public/products') : 'public/no-image.png',
            'added_by'          => auth()->user()->id ?? null,
            'last_edited_by'    => auth()->user()->id ?? null,
            'product_status'    => $request->product_status == 'on' ? 1 : 0,
        ]);

        return redirect()->route('product.index')->with('success', 'Berhasil menambahkan master produk baru.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $product
     * @return \Illuminate\Http\Response
     */
    public function show($product)
    {
        $product = Product::withTrashed()->findOrFail($product);

        return view('product.show', compact('product'));
    }

    /**
     * Return the specified resource as json.
     *
     * @param  int  $product
     * @return \Illuminate\Http\Response
     */
    public function detail($product)
    {
        $product = Product::withTrashed()->find($product);

        if($product) {
            return response()->json([
                'success' => true,
                'type' => 'show_product',
                'message' => 'Data produk',
                'data' => $product,
                'statusCode' => 200
            ], 200);
        }
        
        return response()->json([
            'success' => false,
            'type' => 'show_product',
            'message' => 'Produk tidak ditemukan!',
            'data' => [],
            'statusCode' => 404
        ], 404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  id
     * @return \Illuminate\Http\Response
     */
    public function edit($product)
    {
        $product = Product::withTrashed()->findOrFail($product);
        $units = Unit::all();
        $categories = Category::all();

        return view('product.edit', compact('product', 'units', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $product)
    {
        $product = Product::withTrashed()->findOrFail($product);

        $validator = Validator::make($request->all(), [
            'product_name'      => 'required|string',
            'description'       => 'nullable|string',
            'category_id'       => 'required|exists:App\Models\Category,id',
            'unit_id'           => 'required|exists:App\Models\Unit,id',
        ]);
    
        $default_photo = $product->default_photo;
        if($request->hasFile('default_photo') && $default_photo != 'public/no-image.png') {
            Storage::delete($default_photo);
            $default_photo = $request->file('default_photo')->store('public/products');
        }

        $product->update([
            'product_name'      => $request->product_name,
            'description'       => $request->description,
            'category_id'       => $request->category_id,
            'unit_id'           => $request->unit_id,
            'default_photo'     => $default_photo,
            'last_edited_by'    => auth()->user()->id ?? null,
            'product_status'    => $request->product_status == 'on' ? 1 : 0,
        ]);

        return redirect()->route('product.index')->with('success', 'Berhasil mengubah master produk.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        if($product->delete()) {
            return response()->json([
                'success' => true,
                'type' => 'delete_product',
                'message' => 'Produk berhasil dihapus sementara.',
                'data' => [],
                'statusCode' => 200
            ], 200);
        }

        return response()->json([
            'success' => false,
            'type' => 'delete_product',
            'message' => 'Gagal menghapus produk, silahkan coba lagi.',
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
    public function restore(Request $request, $product)
    {
        $product = Product::withTrashed()->find($product);
        
        if($product->restore()) {
            return response()->json([
                'success' => true,
                'type' => 'restore_product',
                'message' => 'Produk berhasil dipulihkan kembali.',
                'data' => [],
                'statusCode' => 200
            ], 200);
        }

        return response()->json([
            'success' => false,
            'type' => 'restore_product',
            'message' => 'Gagal memulihkan produk, silahkan coba lagi.',
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
    public function changeStatus(Request $request, $product)
    {
        $product = Product::withTrashed()->find($product);

        $changeStatus = $product->update([
            'product_status'    => $request->product_status ? 1 : 0,
        ]);
        
        if($changeStatus && $request->product_status) {
            return response()->json([
                'success' => true,
                'type' => 'change_product_status',
                'message' => 'Produk akan ditampilkan pada pencarian.',
                'data' => [],
                'statusCode' => 200
            ], 200);
        } else {
            return response()->json([
                'success' => true,
                'type' => 'change_product_status',
                'message' => 'Produk akan disembunyikan dari pencarian.',
                'data' => $request->all(),
                'statusCode' => 200
            ], 200);
        }

        return response()->json([
            'success' => false,
            'type' => 'change_product_status',
            'message' => 'Gagal mengubah status produk, silahkan coba lagi.',
            'data' => [],
            'statusCode' => 404
        ], 404);
    }
}
