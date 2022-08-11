<?php

namespace App\Http\Controllers;

use App\Models\User;
use DataTables;
use Storage;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $roles = ['admin', 'staff', 'reseller'];

    public function check($role)
    {
        return in_array($role, $this->roles);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($role)
    {
        if($this->check($role)) {
            if($role == "reseller") {
                return redirect()->route('reseller.index');
            }

            $users = User::where('role', $role)->get();
            return view('users.index', compact('users', 'role'));
        }

        abort(404);
    }

    /**
     * Display a listing of the resource for DataTables.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_dt(Request $request, $role)
    {
        $data = User::where('role', $role);

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row) use ($role) {
                $actionBtn = '<a href="' . route('user.edit', ['role' => $role, 'user' => $row->id]) . '" data-id="' . $row->id . '" class="btn btn-link p-0 text-warning me-1 ms-1 ' . (auth()->user()->isStaff() ? "disabled text-muted" : "") . '"><i class="fa fa-edit fa-sm"></i></a>';
                return $actionBtn;
            })
            ->editColumn('photo', function($row){
                return '<a class="image-popup" href="' . Storage::url($row->photo_url) . '"><img class="avatar" style="object-fit: cover; width: 36px; height: 36px;" src="' . Storage::url($row->photo_url) . '"></a>';
            })
            ->editColumn('status', function($row) {
                return $row->statusBadge();
            })
            ->addColumn('switch_button', function($row) {
                if(auth()->user()->isAdmin()) {
                    return $row->statusSwitchButton();
                } else {
                    return "-";
                }
            })
            ->filter(function ($instance) use ($request) {
                if($request->get('status') != null) {
                    $instance->where('status', $request->get('status'));
                }
                
                if( ! empty($request->get('search'))) {
                     $instance->where(function($w) use ($request){
                        $search = $request->get('search');
                        $w->orWhere('name', 'LIKE', "%$search%");
                    });
                }
            })
            ->rawColumns(['action','status','photo','switch_button'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($role)
    {
        if($this->check($role)) {
            return view('users.create', compact('role'));
        }

        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $role)
    {
        if( ! $this->check($role)) {
            abort(404);
        }

        $validator = $request->validate([
            'name'      => 'required|string',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|string|confirmed|min:8',
            'photo'     => 'nullable|file|mimes:jpg,jpeg,png,gif|max:4096',
        ]);

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => bcrypt($request->password),
            'photo'     => $request->hasFile('photo') ? $request->file('photo')->store('public/products') : 'public/user-default.png',
            'role'      => $role,
            'status'    => 1,
        ]);

        return redirect()->route('user.index', $role)->with('success', 'Berhasil menambahkan ' . $role . ' baru.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Display user profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        $ref = 'profile';
        $user = auth()->user();
        $role = strtolower($user->role);

        if( ! $this->check($role)) {
            abort(404);
        }

        return view('users.edit', compact('user','role','ref'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit($role, User $user)
    {
        if( ! $this->check($role)) {
            abort(404);
        }

        return view('users.edit', compact('user','role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $role, User $user)
    {
        if( ! $this->check($role)) {
            abort(404);
        }

        $validator = $request->validate([
            'name'                  => 'required|string',
            'password'              => 'nullable|min:8|confirmed',
            'photo'                 => 'nullable|file|mimes:jpg,jpeg,png,gif|max:4096',
        ]);

        $photo = $user->photo;
        if($request->hasFile('photo')) {
            if($photo != 'public/user-default.png') {
                Storage::delete($photo);
            }
            $photo = $request->file('photo')->store('public/users');
        }

        $password = $request->has('password') && $request->password != null
            ? bcrypt($request->password) 
            : $user->password;

        $user->update([
            'name'      => $request->name,
            'password'  => $password,
            'photo'     => $photo,
        ]);

        if($request->ref == "profile") {
            return redirect()->route('user.profile')->with('success', 'Berhasil memperbarui profil.');
        }

        return redirect()->route('user.index', $role)->with('success', 'Berhasil mengubah data ' . $role);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($role, User $user)
    {
        if($user->update(['status' => 0])) {
            return response()->json([
                'success' => true,
                'type' => 'deactivate_user',
                'message' => $user->role . ' berhasil dinonaktifkan.',
                'data' => [
                    'role' => $role,
                    'user' => $user
                ],
                'statusCode' => 200
            ], 200);
        }

        return response()->json([
            'success' => false,
            'type' => 'deactivate_user',
            'message' => 'Gagal menonaktifkan ' . $user->role . ', silahkan coba lagi.',
            'data' => [],
            'statusCode' => 422
        ], 422);
    }

    /**
     * Restore the specified resource in storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function restore($role, User $user)
    {
        if($user->update(['status' => 1])) {
            return response()->json([
                'success' => true,
                'type' => 'activate_user',
                'message' => $user->role . ' berhasil diaktifkan kembali.',
                'data' => [],
                'statusCode' => 200
            ], 200);
        }

        return response()->json([
            'success' => false,
            'type' => 'activate_user',
            'message' => 'Gagal mengaktifkan ' . $user->role .  ', silahkan coba lagi.',
            'data' => [],
            'statusCode' => 422
        ], 422);
    }
}
