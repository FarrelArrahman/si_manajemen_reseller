<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use DataTables;
use Illuminate\Http\Request;


class AnnouncementController extends Controller
{
    /**
     * Menampilkan halaman daftar pengumuman.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('announcement.index');
    }

    /**
     * Mengambil data pengumuman ke dalam format datatable.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_dt(Request $request)
    {
        // Cek apakah role user adalah Admin atau Staff
        if(auth()->user()->isAdmin() || auth()->user()->isStaff()) {
            // Jika user adalah Admin atau Staff, 
            // maka ambil seluruh data pengumuman
            $data = Announcement::select('*');
        } else {
            // Jika user bukan Admin atau Staff, 
            // Ambil data pengumuman yang is_private = 0, 
            // tanggal start_from kurang dari hari ini, dan
            // tanggal valid_until  lebih dari hari ini
            $data = Announcement::select('*')
                ->where('is_private', 0)
                ->whereDate('start_from', '<=', today())
                ->whereDate('valid_until', '>=', today());
        }

        // Kembalikan datatable dalam format json
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                // Tambahkan kolom action yang berisi tombol lihat, edit dan hapus
                $actionBtn = '<a href="' . route('announcement.show', $row->id) . '" data-id="' . $row->id . '" class="btn btn-link p-0 text-info me-1 ms-1"><i class="fa fa-search fa-sm"></i></a>';
                // Jika user adalah Admin atau Staff, tampilkan tombol edit dan hapus
                if(auth()->user()->isAdmin() || auth()->user()->isStaff()) {
                    $actionBtn .= '<a href="' . route('announcement.edit', $row->id) . '" data-id="' . $row->id . '" class="btn btn-link p-0 text-warning me-1 ms-1"><i class="fa fa-edit fa-sm"></i></a>';
                    $actionBtn .= '<button data-id="' . $row->id . '" class="btn btn-link p-0 text-danger me-1 ms-1 delete-button"><i class="fa fa-trash-alt fa-sm"></i></button>';
                }
                return $actionBtn;
            })
            ->addColumn('switch_button', function($row) {
                // Tambahkan kolom untuk ubah status aktif
                return $row->statusSwitchButton();
            })
            ->editColumn('is_private', function($row) {
                // Tampilkan badge status
                return $row->statusBadge();
            })
            ->editColumn('created_by', function($row) {
                // Tampilkan nama pembuat pengumuman
                return $row->createdBy->name;
            })
            ->editColumn('start_from', function($row) {
                // Tampilkan tanggal start_from dengan format tahun-bulan-tanggal
                return $row->start_from->format('Y-m-d');
            })
            ->editColumn('valid_until', function($row) {
                // Tampilkan tanggal valid_until dengan format tahun-bulan-tanggal
                return $row->valid_until->format('Y-m-d');
            })
            ->rawColumns(['action','description','is_private','switch_button'])
            ->make(true);
    }

    /**
     * Menampilkan halaman untuk tambah pengumuman.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('announcement.create');
    }

    /**
     * Menambahkan data pengumuman baru.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi data terlebih dahulu
        $validator = $request->validate([
            'title'         => 'required|string',
            'content'       => 'nullable|string',
            'start_from'    => 'required|date|date_format:Y-m-d|before_or_equal:valid_until',
            'valid_until'   => 'required|date|date_format:Y-m-d|after:start_from',
        ]);

        // Buat pengumuman baru sesuai dengan input dari user
        $announcement = Announcement::create([
            'title'                 => $request->title,
            'content'               => $request->content ?? "",
            'start_from'            => $request->start_from,
            'valid_until'           => $request->valid_until,
            'created_by'            => auth()->user()->id,
            'is_private'            => $request->is_private == 'on' ? 0 : 1,
        ]);

        // Setelah berhasil, alihkan kembali ke halaman daftar pengumuman
        // Dengan pesan "Berhasil menambahkan pengumuman baru"
        return redirect()->route('announcement.index')->with('success', 'Berhasil menambahkan pengumuman baru.');
    }

    /**
     * Menampilkan isi dari pengumuman.
     *
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function show(Announcement $announcement)
    {
        // Jika user adalah Reseller dan status pengumuman privasi,
        if(auth()->user()->isReseller() && $announcement->isPrivate()) {
            // Maka alihkan ke halaman tidak ditemukan
            abort(404);
        }

        // Jika tidak, tampilkan isi pengumuman
        return view('announcement.show', compact('announcement'));
    }

    /**
     * Menampilkan halaman untuk ubah pengumuman.
     *
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function edit(Announcement $announcement)
    {
        return view('announcement.edit', compact('announcement'));
    }

    /**
     * Meng-update data pengumuman.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Announcement $announcement)
    {
        // Validasi data terlebih dahulu
        $validator = $request->validate([
            'title'         => 'required|string',
            'content'       => 'nullable|string',
            'start_from'    => 'required|date|date_format:Y-m-d|before_or_equal:valid_until',
            'valid_until'   => 'required|date|date_format:Y-m-d|after:start_from',
        ]);

        // Lakukan update pada pengumuman sesuai dengan input dari user
        $announcement->update([
            'title'                 => $request->title,
            'content'               => $request->content ?? "",
            'start_from'            => $request->start_from,
            'valid_until'           => $request->valid_until,
            'is_private'            => $request->is_private == 'on' ? 0 : 1,
        ]);

        // Setelah berhasil, alihkan kembali ke halaman daftar pengumuman
        // Dengan pesan "Berhasil mengubah pengumuman"
        return redirect()->route('announcement.index')->with('success', 'Berhasil mengubah pengumuman.');
    }

    /**
     * Menghapus pengumuman.
     *
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Announcement $announcement)
    {
        // Jika berhasil menghapus pengumuman,
        if($announcement->delete()) {
            // maka kembalikan json yang berisi pesan "Pengumuman berhasil dihapus"
            return response()->json([
                'success' => true,
                'type' => 'delete_announcement',
                'message' => 'Pengumuman berhasil dihapus.',
                'data' => [],
                'statusCode' => 200
            ], 200);
        }

        // Jika tidak berhasil, maka kembalikan json 
        // yang berisi pesan "Gagal menghapus pengumuman, silakan coba lagi"
        return response()->json([
            'success' => false,
            'type' => 'delete_announcement',
            'message' => 'Gagal menghapus pengumuman, silakan coba lagi.',
            'data' => [],
            'statusCode' => 404
        ], 404);
    }

    /**
     * Mengubah status pengumuman.
     *
     * @param  int  $announcement
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request, Announcement $announcement)
    {
        // Cek apakah status pengumuman sebelumnya privasi atau publik
        // Jika is_private 1 maka statusnya privasi
        // Jika is_private 0 maka statusnya publik
        $is_private = $announcement->is_private == 1 ? 1 : 0;

        // Ubah status dengan nilai kebalikan dari status pengumuman sebelumnya
        $changeStatus = $announcement->update([
            'is_private'    => ! $is_private,
        ]);
        
        // Jika ubah status berhasil dan statusnya diubah menjadi privasi,
        if($changeStatus && $is_private) {
            // maka kembalikan json yang berisi pesan 
            // "Pengumuman akan disembunyikan dari reseller"
            return response()->json([
                'success' => true,
                'type' => 'change_product_status',
                'message' => 'Pengumuman akan disembunyikan dari reseller.',
                'data' => $request->all(),
                'statusCode' => 200
            ], 200);
        } else {
            // Jika ubah status berhasil dan statusnya diubah menjadi publik,
            // maka kembalikan json yang berisi pesan 
            // "Pengumuman akan ditampilkan kepada reseller"
            return response()->json([
                'success' => true,
                'type' => 'change_product_status',
                'message' => 'Pengumuman akan ditampilkan kepada reseller.',
                'data' => $request->all(),
                'statusCode' => 200
            ], 200);
        }
    }
}
