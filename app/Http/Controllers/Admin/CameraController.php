<?php

namespace App\Http\Controllers\Admin;

use App\Models\Camera;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CameraController extends Controller
{
    public function index() {
        $cameras = Camera::all();
        $users = User::all();
        return view('admin.cameras.index',compact('cameras','users'));
    }

    public function fetch() {
        $cameras = Camera::query();
        return DataTables::of($cameras)
            ->addColumn('user_id', function ($cameras) {
                if (isset($cameras->getUser)) {
                    $user = $cameras->getUser->name;
                }
                else {
                    $user = "Belirtilmedi";
                }
                return $user;
            })
            ->addColumn('state', function ($cameras) {
                if ($cameras->state == 0) {
                    $status = "Pasif";
                }
                else {
                    $status = "Aktif";
                }
                return $status;
            })
            ->addColumn('update', function ($cameras) {
                return '<button title="Güncelle" class="btn btn-warning cameraUpdateModal" data-bs-toggle="modal"
                        data-bs-target="#cameraUpdateModal" data-id="'.$cameras->id.'"><i class="fas fa-edit"></i> Güncelle</button>';
            })
            ->addColumn('delete', function ($cameras) {
                return '<a class="btn btn-danger" onclick="cameraDelete(' . $cameras->id . ')"><i class="fas fa-trash"></i> Sil</a>';
            })
            ->rawColumns(['state','user_id','update', 'delete'])
            ->make();
    }

    public function create(Request $request) {
        $request->validate([
            'key_code' => 'required|size:16|unique:cameras',
        ]);

        $camera = new Camera();
        $camera->name = $request->name;
        $camera->location = $request->location;
        $camera->user_id = $request->user_id;
        $camera->state = 0;
        $camera->key_code = $request->key_code;
        $camera->save();

        return response()->json(['Success' => 'success']);
    }

    public function edit($id){
        $camera = Camera::find($id);
        return $camera;
    }

    public function update(Request $request) {
        $request->validate([
            'key_code' => 'required|size:16|unique:cameras,key_code,' . $request->id,
        ]);

        $camera = Camera::find($request->id);
        $camera->name = $request->name;
        $camera->location = $request->location;
        $camera->user_id = $request->user_id;
        $camera->state = 0;
        $camera->key_code = $request->key_code;
        $camera->save();
        return response()->json(['Success' => 'success']);
    }

    public function delete(Request $request) {
        $camera = Camera::find($request->id);
        $camera->delete();
        return response()->json(['Success' => 'success']);
    }
}
