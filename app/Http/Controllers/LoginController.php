<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index() {
        return view('auth.login');
    }

    public function loginPost(Request $request) {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            toastr()->success('Tekrardan Hoşgeldiniz. '.Auth::user()->name,'Başarılı');

            if (Auth::user()->type === 0) {
                return redirect()->route('dashboard');
            } else {
                return redirect()->route('user.dashboard');
            }
        } else {
            toastr()->error('Email veya şifreniz hatalı.','Başarısız');
            return redirect()->route('login')->withErrors("Email adresi veya şifre hatalı")->withInput();
        }
    }

    public function logOut() {
        Auth::logout();
        toastr()->success('Çıkış Yapıldı.','Başarılı');
        return redirect()->route('login');
    }

//    public function registerIndex() {
//        return view('auth.register');
//    }
//    public function registerPost() {
//
//    }

}
