<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Category;
use DataTables;
use Illuminate\Http\Request;
use Storage;

class InventoryController extends Controller
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

        return view('inventory.index', compact('products', 'categories'));
    }

    /**
     * Display a listing of the resource for DataTables.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_dt(Request $request)
    {
        $data = ProductVariant::select([
            'id',
            'product_id',
            'product_variant_name',
            'reseller_price',
            'photo',
            'stock',
            'color',
        ]);

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){                
                $actionBtn = '<a href="' . route('product.show', $row->id) . '" class="text-info me-1 ms-1"><i class="fa fa-search fa-sm"></i></a>';
                $actionBtn .= '<a href="' . route('product.edit', $row->id) . '" data-id="' . $row->id . '" class="btn btn-link p-0 text-warning me-1 ms-1"><i class="fa fa-edit fa-sm"></i></a>';
                return $actionBtn;
            })
            ->editColumn('photo', function($row){                
                return '<a class="image-popup" href="' . Storage::url($row->photo) . '"><img style="object-fit: cover; width: 96px; height: 96px;" src="' . Storage::url($row->photo) . '"></a>';
            })
            ->editColumn('color', function($row) {
                return '<div class="rounded-circle" style="border:1px solid black;background-color:' . $row->color . ';width:20px;height:20px;">&nbsp;</div>';
            })
            ->addColumn('product_name', function($row) {
                return $row->product->product_name . " (" . $row->product_variant_name . ")";
            })
            ->addColumn('category', function($row) {
                return $row->product->category->category_name;
            })
            ->editColumn('reseller_price', function($row) {
                return number_format($row->reseller_price, 0, '', '.');
            })
            ->filter(function ($instance) use ($request) {
                $category_id = $request->get('category_id');
                $product_id = $request->get('product_id');
                $min_price = $request->get('min_price');
                $max_price = $request->get('max_price');

                if($category_id != null && $category_id != 0) {
                    $instance->whereHas('product', function($q) use ($category_id) {
                        $q->where('category_id', $category_id);
                    });
                }

                if($product_id != null && $product_id != 0) {
                    $instance->where('product_id', $product_id);
                }

                if($min_price != null) {
                    $instance->where('reseller_price', '>=', $min_price);
                }

                if($max_price != null) {
                    $instance->where('reseller_price', '<=', $max_price);
                }
                
                if( ! empty($request->get('search'))) {
                    $instance->where(function($w) use ($request){
                        $search = $request->get('search');
                        $w->whereHas('product', function($q) use ($search) {
                            $q->where('product_name', 'LIKE', "%$search%");
                        });

                        $w->orWhere('product_variant_name', 'LIKE', "%$search%");
                        $w->orWhere('reseller_price', 'LIKE', "%$search%");
                        $w->orWhere('stock', 'LIKE', "%$search%");
                    });
                }
            })
            ->rawColumns(['action', 'photo', 'color'])
            ->make(true);
    }
}
