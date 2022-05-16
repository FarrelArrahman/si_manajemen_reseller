<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Validator;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request) 
    {
        $rules = [
            'email' => 'required',
            'password' => 'required',
        ];

        $messages = [
            'email.required' => "Email wajib diisi.",
            'password.required' => "Password wajib diisi.",
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        $remember_me = $request->has('remember_me') ? true : false;

        $data = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if(auth()->attempt($data, $remember_me)) {
            return redirect()->route('dashboard');
        } else {
            return redirect()->back()->withErrors(['error' => 'Email atau password tidak ditemukan.'])->withInput($request->all());
        }
    }

}
