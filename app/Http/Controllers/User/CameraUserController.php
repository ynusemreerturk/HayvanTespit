<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Admin\Controller;
use App\Models\Camera;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class CameraUserController extends Controller
{
    public function index() {
        return view('online.cameras.index');
    }
    public function fetch() {
        $cameras = Camera::query()->where('user_id',Auth::id());
        return DataTables::of($cameras)
            ->addColumn('state', function ($cameras) {
                if ($cameras->state == 0) {
                    $status = "Pasif";
                }
                else {
                    $status = "Aktif";
                }
                return $status;
            })
            ->rawColumns(['state'])
            ->make();
    }
}
