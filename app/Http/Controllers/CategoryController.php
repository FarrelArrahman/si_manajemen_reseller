<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use DataTables;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Menampilkan halaman daftar kategori.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('category.index');
    }

    /**
     * Mengambil data kategori ke dalam format datatable.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_dt(Request $request)
    {
        // Ambil seluruh data kategori
        $data = Category::all();

        // Kembalikan datatable dalam format json
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                // Tambahkan kolom action yang berisi tombol edit dan hapus
                $actionBtn = '<a href="' . route('category.edit', $row->id) . '" data-id="' . $row->id . '" class="btn btn-link p-0 text-warning me-1 ms-1"><i class="fa fa-edit fa-sm"></i></a>';
                // Selain kategori dengan id 1,
                if($row->id != 1) {
                    // maka tampilkan tombol hapusnya
                    $actionBtn .= '<button data-id="' . $row->id . '" class="btn btn-link p-0 text-danger me-1 ms-1 delete-button"><i class="fa fa-trash-alt fa-sm"></i></button>';
                }
                return $actionBtn;
            })
            ->editColumn('description', function($row) {
                if(empty($row->description)) {
                    // Jika description kosong, maka tampilkan teks "tidak ada deskripsi"
                    return '<i class="text-danger">(tidak ada deskripsi)</i>';
                }

                // Jika description ada, maka tampilkan description
                return $row->description;
            })
            ->rawColumns(['action','description'])
            ->make(true);
    }

    /**
     * Mengambil data kategori ke dalam format json
     *
     * @return \Illuminate\Http\Response
     */
    public function index_api(Request $request)
    {
        // Ambil seluruh data kategori (id dan category_name)
        // Format ke dalam bentuk array
        $categories = Category::select(['id', 'category_name'])->get()->toArray();

        // Kembalikan json yang berisi daftar kategori
        return response()->json([
            'success' => true,
            'type' => 'category_list',
            'message' => 'Daftar kategori',
            'data' => $categories,
            'statusCode' => 200
        ], 200);
    }

    /**
     * Menampilkan halaman untuk tambah kategori.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('category.create');
    }

    /**
     * Menambahkan data kategori baru.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi data terlebih dahulu
        $validator = $request->validate([
            'category_name'     => 'required|string',
            'description'       => 'nullable|string',
        ]);

        // Buat kategori baru sesuai dengan input dari user
        $category = Category::create([
            'category_name'     => $request->category_name,
            'description'       => $request->description,
            'status'            => $request->status == 'on' ? 1 : 0,
        ]);

        // Setelah berhasil, alihkan kembali ke halaman daftar kategori
        // Dengan pesan "Berhasil menambahkan kategori baru"
        return redirect()->route('category.index')->with('success', 'Berhasil menambahkan kategori baru.');
    }

    /**
     * Menampilkan halaman untuk ubah kategori.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('category.edit', compact('category'));
    }

    /**
     * Meng-update data kategori.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        // Validasi data terlebih dahulu
        $validator = $request->validate([
            'category_name'     => 'required|string',
            'description'       => 'nullable|string',
        ]);

        // Lakukan update pada kategori sesuai dengan input dari user
        $category->update([
            'category_name'     => $request->category_name,
            'description'       => $request->description,
            'status'            => $request->status == 'on' ? 1 : 0,
        ]);

        // Setelah berhasil, alihkan kembali ke halaman daftar kategori
        // Dengan pesan "Berhasil mengubah kategori"
        return redirect()->route('category.index')->with('success', 'Berhasil mengubah kategori.');
    }

    /**
     * Menghapus kategori.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        // Ubah dulu id kategori dari produk yang kategorinya akan dihapus
        // Ubah id kategori nya 1 (Tanpa Kategori)
        $product = Product::where('category_id', $category->id)->update([
            'category_id' => 1
        ]);
        
        // Jika berhasil menghapus kategori,
        if($category->delete()) {
            // maka kembalikan json yang berisi pesan "Kategori berhasil dihapus"
            return response()->json([
                'success' => true,
                'type' => 'delete_category',
                'message' => 'Kategori berhasil dihapus.',
                'data' => [],
                'statusCode' => 200
            ], 200);
        }

        // Jika tidak berhasil, maka kembalikan json 
        // yang berisi pesan "Gagal menghapus kategori, silakan coba lagi"
        return response()->json([
            'success' => false,
            'type' => 'delete_category',
            'message' => 'Gagal menghapus kategori, silahkan coba lagi.',
            'data' => [],
            'statusCode' => 404
        ], 404);
    }
}
