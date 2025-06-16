<?php

namespace App\Http\Controllers\Admin;

use App\Models\Action;
use App\Models\Camera;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ActionsController extends Controller
{
    public function index() {
        $actions = Action::all();
        $users = User::all();
        $kameralar = Camera::pluck('name');
        return view('admin.actions.index',compact('actions','users','kameralar'));
    }

    public function fetch() {
        $actions = Action::with('getCamera')
            ->when(request('camera_id'), function($q) {
                $q->whereHas('getCamera', function($sub) {
                    $sub->where('name', request('camera_id'));
                });
            });
        return DataTables::of($actions)
            ->addColumn('camera_id', function ($actions) {
                return isset($actions->getCamera) ? $actions->getCamera->name : 'Belirtilmedi';
            })
            ->filterColumn('camera_id', function($query, $keyword) {
                    $query->whereHas('getCamera', function($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%");
                    });
            })
            ->addColumn('dogruluk', function ($row) {
                return match($row->dogruluk) {
                    0 => 'Yanlış',
                    1 => 'Doğru',
                    default => 'Bilinmeyen',
                };
            })
            ->addColumn('animal_type', function ($row) {
                return match($row->animal_type) {
                    0 => 'Köpek',
                    1 => 'Domuz',
                    2 => 'Güvercin',
                    default => 'Bilinmeyen',
                };
            })
            ->filterColumn('animal_type', function ($query, $keyword) {
                $map = [
                    'köpek' => 0,
                    'domuz' => 1,
                    'güvercin' => 2,
                    'bilinmeyen' => 3,
                ];

                $keyword = mb_strtolower(trim($keyword), 'UTF-8');
                $matches = [];

                foreach ($map as $key => $value) {
                    if (str_contains($key, $keyword)) {
                        $matches[] = $value;
                    }
                }

                if (!empty($matches)) {
                    $query->whereIn('animal_type', $matches);
                } else {
                    $query->whereRaw('1 = 0');
                }
            })
            ->addColumn('photo', function($actions) {
                return '<img src="' . $actions->image_base64 . '" alt="Image" style="width: 100px; height: 100px;">';
            })
            ->addColumn('created_at', function ($actions) {
                return \Carbon\Carbon::parse($actions->created_at)->format('d-m-Y H:i:s');
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->where('created_at', 'like', "%{$keyword}%");
            })
            ->addColumn('update', function ($cameras) {
                return '<button title="Güncelle" class="btn btn-warning actionUpdateModal" data-bs-toggle="modal"
                        data-bs-target="#actionUpdateModal" data-id="'.$cameras->id.'"><i class="fas fa-edit"></i> Güncelle</button>';
            })
            ->rawColumns(['photo','dogruluk', 'camera_id', 'animal_type', 'created_at','update'])
            ->make(true);
    }

    public function get($id){
        $actions = Action::find($id);
        return $actions;
    }
    public function  update(Request $request){
        $request->validate([
            'id' => 'required',
            'dogruluk' => 'boolean'
        ]);
        $action = Action::find($request->id);
        $action->dogruluk = $request->dogruluk;
        $action->save();
        return response()->json(['Success' => 'success']);
    }
    public function getTumDogrulukYanlis()
    {
        $stats = Action::selectRaw('
            SUM(CASE WHEN dogruluk = 1 THEN 1 ELSE 0 END) as dogru,
            SUM(CASE WHEN dogruluk = 0 THEN 1 ELSE 0 END) as yanlis
        ')->first();

        return response()->json([
            'dogru' => $stats->dogru ?? 0,
            'yanlis' => $stats->yanlis ?? 0,
        ]);
    }

    public function getKameraDogrulukYanlis()
    {
        $stats = Action::selectRaw('actions.camera_id, cameras.name as camera_name,
        SUM(CASE WHEN dogruluk = 1 THEN 1 ELSE 0 END) as dogru,
        SUM(CASE WHEN dogruluk = 0 THEN 1 ELSE 0 END) as yanlis')
            ->join('cameras', 'actions.camera_id', '=', 'cameras.id')
            ->groupBy('actions.camera_id', 'cameras.name')
            ->get();


        return response()->json($stats);
    }

    public function getHayvanTipiDogrulukYanlis()
    {
        $stats = Action::selectRaw('animal_type,
            SUM(CASE WHEN dogruluk = 1 THEN 1 ELSE 0 END) as dogru,
            SUM(CASE WHEN dogruluk = 0 THEN 1 ELSE 0 END) as yanlis')
            ->groupBy('animal_type')
            ->get();

        return response()->json($stats);
    }

}
