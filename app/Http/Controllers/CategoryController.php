<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use DataTables;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();

        return view('category.index', compact('categories'));
    }

    /**
     * Display a listing of the resource for DataTables.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_dt(Request $request)
    {
        $data = Category::all();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $actionBtn = '<a href="' . route('category.edit', $row->id) . '" data-id="' . $row->id . '" class="btn btn-link p-0 text-warning me-1 ms-1"><i class="fa fa-edit fa-sm"></i></a>';
                if($row->id != 1) {
                    $actionBtn .= '<button data-id="' . $row->id . '" class="btn btn-link p-0 text-danger me-1 ms-1 delete-button"><i class="fa fa-trash-alt fa-sm"></i></button>';
                }
                return $actionBtn;
            })
            ->editColumn('description', function($row) {
                if(empty($row->description)) {
                    return '<i class="text-danger">(tidak ada deskripsi)</i>';
                }

                return $row->description;
            })
            ->rawColumns(['action','description'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('category.create');
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
            'category_name'     => 'required|string',
            'description'       => 'nullable|string',
        ]);

        $category = Category::create([
            'category_name'     => $request->category_name,
            'description'       => $request->description,
            'status'            => $request->status == 'on' ? 1 : 0,
        ]);

        return redirect()->route('category.index')->with('success', 'Berhasil menambahkan kategori baru.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        // dd($request->all());
        $validator = $request->validate([
            'category_name'     => 'required|string',
            'description'       => 'nullable|string',
        ]);

        $category->update([
            'category_name'     => $request->category_name,
            'description'       => $request->description,
            'status'            => $request->status == 'on' ? 1 : 0,
        ]);

        return redirect()->route('category.index')->with('success', 'Berhasil mengubah kategori.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $product = Product::where('category_id', $category->id)->update([
            'category_id' => 1
        ]);
        
        if($category->delete()) {
            return response()->json([
                'success' => true,
                'type' => 'delete_category',
                'message' => 'Kategori berhasil dihapus.',
                'data' => [],
                'statusCode' => 200
            ], 200);
        }

        return response()->json([
            'success' => false,
            'type' => 'delete_category',
            'message' => 'Gagal menghapus kategori, silahkan coba lagi.',
            'data' => [],
            'statusCode' => 404
        ], 404);
    }
}
