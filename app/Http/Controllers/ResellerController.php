<?php

namespace App\Http\Controllers;

use App\Events\ResellerEvent;
use App\Events\AdminEvent;
use App\Jobs\SendEmailJob;
use App\Models\Order;
use App\Models\Reseller;
use App\Traits\Rajaongkir;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Storage;

class ResellerController extends Controller
{
    use Rajaongkir;
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reseller = Reseller::all();
        return view('reseller.index', compact('reseller'));
    }

    /**
     * Display a listing of the resource for DataTables.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_dt(Request $request)
    {
        $data = Reseller::select('*');

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row) {
                $actionBtn = '<button data-reseller-id="' . $row->id . '" data-bs-toggle="modal" data-bs-target="#reseller_detail_modal" class="text-info btn btn-link"><i class="fa fa-search fa-sm"></i></button>';
                $actionBtn .= '<a target="_blank" href="' . route('order.history', Crypt::encrypt($row->id)) . '" class="text-primary"><i class="fa fa-shopping-cart fa-sm"></i></button>';
                return $actionBtn;
            })
            ->addColumn('name', function($row) {
                return $row->user->name;
            })
            ->addColumn('switch_button', function($row) {
                return $row->statusSwitchButton();
            })
            ->addColumn('last_order_date', function($row) {
                if($row->orders->count() > 0) {
                    $textColor = "text-dark";
                    $lastOrder = Order::where('ordered_by', $row->id)->orderBy('date', 'DESC')->first();
                    
                    if($lastOrder->date->diffInMonths(today()) >= 3) {
                        $textColor = "text-danger";
                    }

                    return '<span class="' . $textColor . '">' . $lastOrder->date->format('Y-m-d') . "</span>";
                }
                return "-";
            })
            ->editColumn('photo', function($row){
                return '<a class="image-popup" href="' . Storage::url($row->user->photo) . '"><img class="avatar" style="object-fit: cover; width: 36px; height: 36px;" src="' . Storage::url($row->user->photo) . '"></a>';
            })
            ->editColumn('phone_number', function($row) {
                return $row->phoneNumberBadge();
            })
            ->editColumn('reseller_status', function($row) {
                return $row->statusBadge();
            })
            ->filter(function ($instance) use ($request) {
                if($request->get('reseller_status') != null && $request->get('reseller_status') != "VAKUM") {
                    $instance->where('reseller_status', $request->get('reseller_status'));
                }

                if($request->get('province') != null) {
                    $instance->where('province', $request->get('province'));
                }

                if($request->get('city') != null) {
                    $instance->where('city', $request->get('city'));
                }
                
                if( ! empty($request->get('search'))) {
                    $instance->where(function($w) use ($request){
                        $search = $request->get('search');
                        $w->orWhere('shop_name', 'LIKE', "%$search%");
                        $w->orWhere('phone_number', 'LIKE', "%$search%");
                    });
                }
            })
            ->rawColumns(['action','photo','reseller_status','switch_button','dropdown','phone_number','last_order_date'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Reseller  $reseller
     * @return \Illuminate\Http\Response
     */
    public function show(Reseller $reseller)
    {
        // 
    }

    /**
     * Display the detail of the specified resource.
     *
     * @param  \App\Models\Reseller  $reseller
     * @return \Illuminate\Http\Response
     */
    public function detail($reseller)
    {
        $reseller = Reseller::with(['user', 'approvedBy'])->find($reseller);
        $reseller->verification_status = $reseller->verificationStatus() ?? null;
        $reseller->user->photo = Storage::url($reseller->user->photo);
        $reseller->reseller_registration_proof_of_payment = Storage::url($reseller->reseller_registration_proof_of_payment) ?? null;
        $reseller->status_badge = $reseller->statusBadge() ?? null;

        if($reseller) {
            return response()->json([
                'success' => true,
                'type' => 'detail_reseller',
                'message' => 'Data reseller',
                'data' => $reseller,
                'statusCode' => 200
            ], 200);
        }
        
        return response()->json([
            'success' => false,
            'type' => 'detail_reseller',
            'message' => 'Reseller tidak ditemukan!',
            'data' => [],
            'statusCode' => 404
        ], 404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $reseller = auth()->user()->reseller;

        return view('reseller.edit', compact('reseller'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Reseller  $reseller
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reseller $reseller)
    {
        // dd($request->all());
        $message = "";
        $validator = $request->validate([
            'shop_name' => 'required|string',
            'shop_address' => 'required|string',
            'province' => 'required|numeric',
            'city' => 'required|numeric',
            'postal_code'  => 'required|numeric',
            'phone_number'  => 'required|numeric',
            'account_number' => 'required|numeric',
            'bank_name' => 'required|string',
            'bank_code' => 'required|numeric',
            'account_holder_name' => 'required|string',
            'social_media'  => 'nullable|array:facebook,twitter,instagram,tiktok',
            'social_media.facebook'  => 'nullable|url',
            'social_media.twitter'  => 'nullable|url',
            'social_media.instagram'  => 'nullable|url',
            'social_media.tiktok'  => 'nullable|url',
            'reseller_registration_proof_of_payment' => 'nullable|file|mimes:jpg,jpeg,png,gif|max:4096',
        ]);

        $reseller = Reseller::where('user_id', $request->user_id)->first();
        if($reseller) {
            $reseller->update([
                'shop_name' => $request->shop_name,
                'shop_address' => $request->shop_address,
                'province' => $request->province,
                'city' => $request->city,
                'postal_code'  => $request->postal_code,
                'phone_number'  => $request->phone_number,
                'social_media'  => array_filter($request->social_media),
                'account_number' => $request->account_number,
                'bank_name' => $request->bank_name,
                'bank_code' => $request->bank_code,
                'account_holder_name' => $request->account_holder_name,
            ]);
        } else {
            $reseller = Reseller::create([
                'user_id' => $request->user_id,
                'shop_name' => $request->shop_name,
                'shop_address' => $request->shop_address,
                'province' => $request->province,
                'city' => $request->city,
                'postal_code'  => $request->postal_code,
                'phone_number'  => $request->phone_number,
                'social_media'  => array_filter($request->social_media),
                'account_number' => $request->account_number,
                'bank_name' => $request->bank_name,
                'bank_code' => $request->bank_code,
                'account_holder_name' => $request->account_holder_name,
                'rejection_reason' => NULL,
                'reseller_status' => 'PENDING'
            ]);
        }

        if( ! $reseller->isActive()) {
            if($request->hasFile('reseller_registration_proof_of_payment')) {
                $reseller->update([
                    'reseller_registration_proof_of_payment' => $request->file('reseller_registration_proof_of_payment')->store('public/reseller_registration_proof_of_payment'),
                    'rejection_reason' => NULL,
                    'reseller_status' => Reseller::PENDING
                ]);
            }

            $data = [
                'id' => $reseller->user->id,
                'success' => true,
                'action' => "update_pending_reseller_count",
                'message' => 'User "' . $reseller->user->name . '" telah mengajukan data reseller untuk diverifikasi.',
            ];

            // Notify Admin via Web Notification
            AdminEvent::dispatch($data);

            // Notify Admin via Email
            dispatch(new SendEmailJob([
                'email' => "admin@laudable-me.com",
                'subject' => "Verifikasi Data Reseller Baru",
                'message' => 'User **' . $reseller->user->name . '** telah mengajukan data reseller untuk diverifikasi. Silakan kunjungi halaman **Reseller** pada menu **User > Reseller** atau klik tombol di bawah ini.',
                'button' => 'Lihat Daftar Reseller',
                'url' => route('user.index', 'reseller')
            ]));

            $message = "Berhasil mengisi data reseller. Harap tunggu verifikasi oleh admin.";
        } else {
            $message = "Berhasil memperbarui data reseller.";
        }

        return redirect()->route('reseller.edit')->with('success', $message);
    }

    /**
     * Verify reseller.
     *
     * @param  int  $reseller
     * @return \Illuminate\Http\Response
     */
    public function verify(Request $request, $reseller)
    {
        $action = "hide_unverified_alert";
        $success = true;
        $reseller = Reseller::withTrashed()->find($reseller);
        
        $reseller->reseller_status = $request->reseller_status;
        if($request->reseller_status == Reseller::ACTIVE) {
            $reseller->approval_date = now();
            $reseller->approved_by = auth()->user()->id;
            $message = "Data reseller Anda telah berhasil terverifikasi oleh Admin.";
        } else if($request->reseller_status == Reseller::REJECTED) {
            $success = false;
            $action = "show_unverified_alert";
            $reseller->rejection_reason = $request->rejection_reason;
            $message = "Permintaan verifikasi data reseller Anda ditolak. Silakan perbarui pengajuan data reseller Anda.";
        }

        $data = [
            'id' => $reseller->user->id,
            'success' => $success,
            'action' => $action,
            'message' => $message
        ];
        
        if($reseller->save()) {
            // Notify Reseller via Web Notification
            ResellerEvent::dispatch($data);

            // Notify Reseller via Email
            dispatch(new SendEmailJob([
                'email' => $reseller->user->email,
                'subject' => "Status Verifikasi Data Reseller",
                'message' => $message,
                'button' => 'Ke Halaman Dashboard',
                'url' => route('dashboard',)
            ]));

            return response()->json([
                'success' => true,
                'type' => 'change_reseller_status',
                'message' => 'Berhasil melakukan verifikasi data reseller.',
                'data' => $request->all(),
                'statusCode' => 200
            ], 200);
        }

        return response()->json([
            'success' => false,
            'type' => 'change_reseller_status',
            'message' => 'Gagal melakukan verifikasi data reseller, silahkan coba lagi.',
            'data' => [],
            'statusCode' => 422
        ], 422);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Reseller  $reseller
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reseller $reseller)
    {
        //
    }

    /**
     * Change reseller status.
     *
     * @param  int  $reseller
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request, $reseller)
    {
        $reseller = Reseller::withTrashed()->find($reseller);
        $changeStatus = $reseller->update([
            'reseller_status'    => $request->reseller_status ? Reseller::ACTIVE : Reseller::INACTIVE,
        ]);
        
        if($changeStatus && $request->reseller_status) {
            return response()->json([
                'success' => true,
                'type' => 'change_reseller_status',
                'message' => 'Status reseller diubah menjadi aktif.',
                'data' => [],
                'statusCode' => 200
            ], 200);
        } else {
            return response()->json([
                'success' => true,
                'type' => 'change_reseller_status',
                'message' => 'Status reseller diubah menjadi tidak aktif.',
                'data' => [],
                'statusCode' => 200
            ], 200);
        }

        return response()->json([
            'success' => false,
            'type' => 'change_reseller_status',
            'message' => 'Gagal mengubah status produk, silahkan coba lagi.',
            'data' => [],
            'statusCode' => 404
        ], 404);
    }

    /**
     * Return the pending reseller user count.
     *
     * @return \Illuminate\Http\Response
     */
    public function pending()
    {
        $count = Reseller::where('reseller_status', Reseller::PENDING)->count();

        return response()->json([
            'success' => true,
            'type' => 'pending_reseller_count',
            'message' => 'Jumlah reseller pending',
            'data' => [
                'count' => $count,
            ],
            'statusCode' => 200
        ], 200);
    }
}
