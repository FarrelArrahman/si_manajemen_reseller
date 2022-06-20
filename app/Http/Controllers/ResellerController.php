<?php

namespace App\Http\Controllers;

use App\Models\Reseller;
use DataTables;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Storage;

class ResellerController extends Controller
{
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
                $actionBtn = '<button data-reseller-id="' . $row->id . '" data-bs-toggle="modal" data-bs-target="#reseller_detail_modal" class="text-info me-1 ms-1 btn btn-link"><i class="fa fa-search fa-sm"></i></button>';
                return $actionBtn;
            })
            ->addColumn('name', function($row) {
                return $row->user->name;
            })
            ->addColumn('switch_button', function($row) {
                return $row->statusSwitchButton();
            })
            ->editColumn('photo', function($row){
                return '<a class="image-popup" href="' . Storage::url($row->user->photo) . '"><img class="avatar" style="object-fit: cover; width: 36px; height: 36px;" src="' . Storage::url($row->user->photo) . '"></a>';
            })
            ->editColumn('phone_number', function($row) {
                return $row->phoneNumberBadge();
            })
            ->editColumn('status', function($row) {
                return $row->statusBadge();
            })
            ->filter(function ($instance) use ($request) {
                if($request->get('reseller_status') != null) {
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
            ->rawColumns(['action','photo','status','switch_button','dropdown','phone_number'])
            ->make(true);
    }

    public function provinceAPI()
    {
        $client = new Client(['base_uri' => env('RAJAONGKIR_BASE_URI', 'https://api.rajaongkir.com/')]);
        $response = $client->request('GET', env('RAJAONGKIR_PROVINCE', '/starter/province'), ['query' => ['key' => env('RAJAONGKIR_API_KEY', 'e22f1c6f62ab0ff49b35f91cf61a3362')]]);
        $json = json_decode($response->getBody());
        return $json->rajaongkir->results;
    }

    public function cityAPI(Request $request)
    {
        $client = new Client(['base_uri' => env('RAJAONGKIR_BASE_URI', 'https://api.rajaongkir.com/')]);
        $response = $client->request('GET', env('RAJAONGKIR_CITY', '/starter/city'), ['query' => ['key' => env('RAJAONGKIR_API_KEY', 'e22f1c6f62ab0ff49b35f91cf61a3362'), 'province' => $request->province]]);
        $json = json_decode($response->getBody());
        return $json->rajaongkir->results;
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
        $reseller = Reseller::with('user')->find($reseller);
        $reseller->verification_status = $reseller->verificationStatus();
        $reseller->user->photo = Storage::url($reseller->user->photo);
        $reseller->reseller_registration_proof_of_payment = Storage::url($reseller->reseller_registration_proof_of_payment);
        $reseller->status_badge = $reseller->statusBadge();

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
        $validator = $request->validate([
            'shop_name' => 'required|string',
            'shop_address' => 'required|string',
            'province' => 'required|numeric',
            'city' => 'required|numeric',
            'postal_code'  => 'required|numeric',
            'social_media'  => 'nullable|array:facebook,twitter,instagram,tiktok',
            'social_media.facebook'  => 'nullable|url',
            'social_media.twitter'  => 'nullable|url',
            'social_media.instagram'  => 'nullable|url',
            'social_media.tiktok'  => 'nullable|url',
            'shopee_link'  => 'required|url',
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
                'shopee_link'  => $request->shopee_link,
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
                'shopee_link'  => $request->shopee_link,
                'rejection_reason' => NULL,
                'reseller_status' => 'PENDING'
            ]);
        }

        if( ! $reseller->isApproved() && $request->hasFile('reseller_registration_proof_of_payment')) {
            $reseller->update([
                'reseller_registration_proof_of_payment' => $request->file('reseller_registration_proof_of_payment')->store('public/reseller_registration_proof_of_payment'),
                'rejection_reason' => NULL,
            ]);
        }

        return redirect()->route('reseller.edit')->with('success', 'Berhasil mengisi data reseller. Harap tunggu verifikasi oleh admin.');
    }

    /**
     * Verify reseller.
     *
     * @param  int  $reseller
     * @return \Illuminate\Http\Response
     */
    public function verify(Request $request, $reseller)
    {
        $reseller = Reseller::withTrashed()->find($reseller);
        
        $reseller->reseller_status = $request->reseller_status;
        if($request->reseller_status == Reseller::ACTIVE) {
            $reseller->approval_date = now();
            $reseller->approved_by = auth()->user()->id;
        } else if($request->reseller_status == Reseller::REJECTED) {
            $reseller->rejection_reason = $request->rejection_reason;
        }
        
        if($reseller->save()) {
            return response()->json([
                'success' => true,
                'type' => 'change_reseller_status',
                'message' => 'Berhasil melakukan verifikasi data reseller.',
                'data' => [],
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

        $changeStatus = $reseller->update($status);

        return response()->json([
            'success' => false,
            'type' => 'change_reseller_status',
            'message' => 'Gagal mengubah status produk, silahkan coba lagi.',
            'data' => [],
            'statusCode' => 404
        ], 404);
    }
}
