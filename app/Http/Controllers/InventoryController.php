<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use DataTables;
use Illuminate\Http\Request;

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
    public function inventory_dt(Request $request)
    {
        $data = Product::select('*');

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){                
                if($row->trashed()) {
                    $actionBtn = '<button data-id="' . $row->id . '" class="btn btn-text text-primary me-1 ms-1 restore-button"><i class="fa fa-undo-alt fa-sm"></i></button>';
                } else {
                    $actionBtn = '<a href="' . route('product.show', $row->id) . '" class="text-info me-1 ms-1"><i class="fa fa-search fa-sm"></i></a>';
                    $actionBtn .= '<a href="' . route('product.edit', $row->id) . '" data-id="' . $row->id . '" class="btn btn-link p-0 text-warning me-1 ms-1"><i class="fa fa-edit fa-sm"></i></a>';
                    $actionBtn .= '<button data-id="' . $row->id . '" class="btn btn-link p-0 text-danger me-1 ms-1 delete-button"><i class="fa fa-trash-alt fa-sm"></i></button>';
                }
                return $actionBtn;
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
            ->rawColumns(['action', 'product_status', 'switch_button'])
            ->make(true);
    }
}
