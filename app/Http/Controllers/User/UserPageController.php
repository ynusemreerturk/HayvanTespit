<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Admin\Controller;
use App\Models\Camera;
use App\Models\Contacts;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;

class UserPageController extends Controller
{
    public function index(){
        $user = Auth::user();
        $camerasCount = Camera::where('user_id', $user->id)->count();
        return view('online.dashboard', compact('user','camerasCount'));
    }

}
