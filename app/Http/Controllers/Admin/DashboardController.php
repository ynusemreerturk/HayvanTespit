<?php

namespace App\Http\Controllers\Admin;

use App\Models\Camera;
use App\Models\Contacts;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index() {
        $user = Auth::user();
        $usersCount = User::where('id','!=',1)->count();
        $camerasCount = Camera::get()->count();
        $contacts = Contacts::orderBy('created_at','DESC')->select('name','surname','message')->take(3)->get();
        return view('admin.dashboard', compact('user','usersCount','contacts','camerasCount'));
    }
}
