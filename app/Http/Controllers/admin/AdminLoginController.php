<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    public function index()
    {
        return view('admin.login');
    }
    public function authenticate(Request $request){
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        if(Auth::guard('admin')->attempt($credentials,$request->get('remember'))){
            $admin = Auth::guard('admin')->user();
            if($admin->role==2){
                $request->session()->regenerate();
                return redirect()->route('admin.dashboard');
            }else{
                Auth::guard('admin')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('admin.login')->with('warning','You can not access admin dashboard');
            }

        }
        return back()->with('error','Credentials do not match')->onlyInput('email');
    }
}
