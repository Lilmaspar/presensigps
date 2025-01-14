<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function proseslogin(Request $request)
    {
        if(Auth::guard('mahasiswa')->attempt(['nim'=> $request->nim,'password'=>$request->password])){
            return redirect('/dashboard');
        } else{
            return redirect('/')->with(['warning'=>'NIM / Password Anda Salah']);
        }
    }

    public function proseslogout()
    {
        if(Auth::guard('mahasiswa')->check()){
            Auth::guard('mahasiswa')->logout();
            return redirect('/');
        }
    }

    public function prosesloginadmin(Request $request)
    {
        if(Auth::guard('user')->attempt(['email'=> $request->email,'password'=>$request->password])){
            return redirect('/panel/dashboardadmin');
        } else{
            return redirect('/panel')->with(['warning'=>'Email / Password Anda Salah']);
        }
    }

    public function proseslogoutadmin()
    {
        if(Auth::guard('user')->check()){
            Auth::guard('user')->logout();
            return redirect('/panel');
        }
    }
}
