<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use DataTables;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('announcement.index');
    }

    /**
     * Display a listing of the resource for DataTables.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_dt(Request $request)
    {
        if(auth()->user()->isAdmin()) {
            $data = Announcement::select('*');
        } else {
            $data = Announcement::select('*')
                ->where('is_private', 0)
                ->whereDate('start_from', '<=', today())
                ->whereDate('valid_until', '>=', today());
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $actionBtn = '<a href="' . route('announcement.show', $row->id) . '" data-id="' . $row->id . '" class="btn btn-link p-0 text-info me-1 ms-1"><i class="fa fa-search fa-sm"></i></a>';
                if(auth()->user()->isAdmin()) {
                    $actionBtn .= '<a href="' . route('announcement.edit', $row->id) . '" data-id="' . $row->id . '" class="btn btn-link p-0 text-warning me-1 ms-1"><i class="fa fa-edit fa-sm"></i></a>';
                    $actionBtn .= '<button data-id="' . $row->id . '" class="btn btn-link p-0 text-danger me-1 ms-1 delete-button"><i class="fa fa-trash-alt fa-sm"></i></button>';
                }
                return $actionBtn;
            })
            ->addColumn('switch_button', function($row) {
                return $row->statusSwitchButton();
            })
            ->editColumn('description', function($row) {
                if(empty($row->description)) {
                    return '<i class="text-danger">(tidak ada deskripsi)</i>';
                }

                return $row->description;
            })
            ->editColumn('is_private', function($row) {
                return $row->statusBadge();
            })
            ->editColumn('created_by', function($row) {
                return $row->createdBy->name;
            })
            ->editColumn('start_from', function($row) {
                return $row->start_from->format('Y-m-d');
            })
            ->editColumn('valid_until', function($row) {
                return $row->valid_until->format('Y-m-d');
            })
            ->rawColumns(['action','description','is_private','switch_button'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('announcement.create');
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
            'title'         => 'required|string',
            'content'       => 'nullable|string',
            'start_from'    => 'required|date|date_format:Y-m-d|before_or_equal:valid_until',
            'valid_until'   => 'required|date|date_format:Y-m-d|after:start_from',
        ]);

        $announcement = Announcement::create([
            'title'                 => $request->title,
            'content'               => $request->content ?? "",
            'start_from'            => $request->start_from,
            'valid_until'           => $request->valid_until,
            'created_by'            => auth()->user()->id,
            'is_private'            => $request->is_private == 'on' ? 0 : 1,
        ]);

        return redirect()->route('announcement.index')->with('success', 'Berhasil menambahkan pengumuman baru.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function show(Announcement $announcement)
    {
        if(auth()->user()->isReseller() && $announcement->isPrivate()) {
            abort(404);
        }

        return view('announcement.show', compact('announcement'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function edit(Announcement $announcement)
    {
        return view('announcement.edit', compact('announcement'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Announcement $announcement)
    {
        // dd($request->all());
        $validator = $request->validate([
            'title'         => 'required|string',
            'content'       => 'nullable|string',
            'start_from'    => 'required|date|date_format:Y-m-d|before_or_equal:valid_until',
            'valid_until'   => 'required|date|date_format:Y-m-d|after:start_from',
        ]);

        $announcement->update([
            'title'                 => $request->title,
            'content'               => $request->content ?? "",
            'start_from'            => $request->start_from,
            'valid_until'           => $request->valid_until,
            'is_private'            => $request->is_private == 'on' ? 0 : 1,
        ]);

        return redirect()->route('announcement.index')->with('success', 'Berhasil mengubah pengumuman.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Announcement $announcement)
    {
        if($announcement->delete()) {
            return response()->json([
                'success' => true,
                'type' => 'delete_announcement',
                'message' => 'Pengumuman berhasil dihapus.',
                'data' => [],
                'statusCode' => 200
            ], 200);
        }

        return response()->json([
            'success' => false,
            'type' => 'delete_announcement',
            'message' => 'Gagal menghapus pengumuman, silahkan coba lagi.',
            'data' => [],
            'statusCode' => 404
        ], 404);
    }

    /**
     * Change product status.
     *
     * @param  int  $announcement
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request, Announcement $announcement)
    {
        $is_private = $announcement->is_private == 1 ? 1 : 0;

        $changeStatus = $announcement->update([
            'is_private'    => ! $is_private,
        ]);
        
        if($changeStatus && $is_private) {
            return response()->json([
                'success' => true,
                'type' => 'change_product_status',
                'message' => 'Pengumuman akan disembunyikan dari reseller.',
                'data' => $request->all(),
                'statusCode' => 200
            ], 200);
        } else {
            return response()->json([
                'success' => true,
                'type' => 'change_product_status',
                'message' => 'Pengumuman akan ditampilkan kepada reseller.',
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
