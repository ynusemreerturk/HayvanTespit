<?php

namespace App\Http\Controllers\Admin;

use App\Models\Building;
use App\Models\City;
use App\Models\Neighbourhood;
use App\Models\Street;
use App\Models\Town;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function index(){
        return view('admin.user.index');
    }

    public function fetch(){
        $user = User::all();
        return DataTables::of($user)
            ->editColumn('name', function ($user) {
                return $user->name.' '.$user->surname;
            })
            ->editColumn('subscription', function ($user) {
                if($user->subscription == 0){
                    return $user->subscription = 'Abone Değil';
                }
                return  $user->subscription = 'Abone';
            })
            ->addColumn('update', function ($user) {
                return "<button onclick='updateUserForm(" . $user->id . ")' class='btn btn-warning'>Güncelle</button>";
            })
            ->addColumn('delete', function ($user) {
                return '<a class="btn btn-danger" onclick="productsDelete(' . $user->id . ')"><i class="fas fa-trash"></i> Sil</a>';
            })
            ->rawColumns(['description','status','solved_time','transaction_id','update', 'delete'])
            ->make();
    }
    public function createIndex(Request $request){
        return view('admin.user.create');
    }

    public function getCity(){
        $data = City::orderBy('city_key','ASC')->get();
        return response()->json($data);
    }
    public function getTown(Request $request){
        $data = City::where('city_key',$request->city)->first();
        $data = Town::where('town_city_key',$data->city_key)->get();
        return response()->json($data);
    }
    public function getNeighbourhood(Request $request){
        $data = Town::where('town_key',$request->town)->first();
        $data = Neighbourhood::where('neighbourhood_town_key',$data->town_key)->get();
        return response()->json($data);
    }
    public function getStreet(Request $request){
        $data = Neighbourhood::where('neighbourhood_key',$request->street)->first();
        $data = Street::where('street_neighbourhood_key',$data->neighbourhood_key)->get();
        return response()->json($data);
    }

    public function createPost(Request $request){
        $request->validate([
            'phone'=>'unique:users,phone',
            'il' =>'required',
            'ilce' =>'required',
            'mahalle' =>'required',
        ]);
        $city = (City::where('city_key',$request->il)->first())->city_title;
        $town = (Town::where('town_key',$request->ilce)->first())->town_title;
        $neighbourhood =(Neighbourhood::where('neighbourhood_key',$request->mahalle)->first())->neighbourhood_title;
        $street = (Street::where('street_id',$request->sokak)->first())->street_title;
        $user = new User();
        $user->phone = $request->phone;
        $user->name = $request->name;
        $user->surname = $request->surname;
        $user->address = $city.'/' .$town.' '.$neighbourhood.' '.$street.' Bina Numarası: '.$request->building;
        $user->email = $request->email;
        $user->date_of_birth = $request->date_of_birth;
        $user->password = Hash::make('123456');
        $building = new Building();
        $building->building_title = $request->building;
        if(is_null($request->sokak)){
            $building->neighbourhood_key = $request->mahalle;
        }
        else{
            $building->street_key = $request->sokak;
        }
        $user->save();
        $building->user_key = $user->id;
        $building->save();
        return redirect()->route('user.index');
    }

    public function userget(Request $request){
        $user = User::where('id',$request->id)->first();
        $building = Building::where('user_key',$user->id)->first();
        if(!is_null($building->street_key)){
            $street = Street::where('street_id',$building->street_key)->first();
            $neighbourhood = Neighbourhood::where('neighbourhood_key',$street->street_neighbourhood_key)->first();
            $town = Town::where('town_key',$neighbourhood->neighbourhood_town_key)->first();
            $city = City::where('city_key',$town->town_city_key)->first();
            $data = [
                'city' => $city->city_key,
                'town' => $town->town_key,
                'neighbourhood' => $neighbourhood->neighbourhood_key,
                'street' => $street->street_id,
                'building' => $building->id,
                'user' => $user,
            ];
        }
        else{
            $neighbourhood = Neighbourhood::where('neighbourhood_key',$building->neighbourhood_key)->first();
            $town = Town::where('town_key',$neighbourhood->neighbourhood_town_key)->first();
            $city = City::where('city_key',$town->town_city_key)->first();
            $data = [
                'city' => $city->city_key,
                'town' => $town->town_key,
                'neighbourhood' => $$neighbourhood->neighbourhood_key,
                'building' => $building->id,
                'user' => $user,
            ];
        }

        if(!is_null($user)){
            return response()->json($data);
        }
    }
    public function update(Request $request){
        $user = User::where('id',$request->user_id)->first();
        if(!is_null($user)){
            $request->validate([
                'phoneUpdate'=>'unique:users,phone,'. $request->user_id,
            ]);
            if (!is_null($request->ilceUpdate)){
                $request->validate([
                    'ilceUpdate' =>'required',
                    'mahalleUpdate' =>'required',
                    'buildingUpdate' =>'required',
                ]);
                $city = (City::where('city_key',$request->ilUpdate)->first())->city_title;
                $town = (Town::where('town_key',$request->ilceUpdate)->first())->town_title;
                $neighbourhood =(Neighbourhood::where('neighbourhood_key',$request->mahalleUpdate)->first())->neighbourhood_title;
                if (!is_null($request->sokakUpdate)){
                    $street = (Street::where('street_id',$request->sokakUpdate)->first())->street_title;
                }
                else{
                    $street = null;
                }
            }
            $user->phone = $request->phoneUpdate;
            $user->name = $request->nameUpdate;
            $user->surname = $request->surnameUpdate;
            if (!is_null($request->ilceUpdate)){
                $user->address = $city.'/' .$town.' '.$neighbourhood.' '.$street.' Bina Numarası: '.$request->buildingUpdate;
            }
            $user->email = $request->emailUpdate;
            $user->date_of_birth = $request->date_of_birthUpdate;
            $user->password = Hash::make('123456');
            if(!is_null($request->ilceUpdate)){
                $building = Building::where('user_key',$user->id)->first();
                $building->building_title = $request->buildingUpdate;
                if(is_null($request->sokakUpdate)){
                    $building->neighbourhood_key = $request->mahalleUpdate;
                }
                else{
                    $building->street_key = $request->sokakUpdate;
                }
                $building->save();
            }
            $user->save();
            return response()->json(['Success' => 'success']);
        }
    }
    public function delete(Request $request){
        $delete = User::where('id',$request->id)->first();
        $delete->delete();
        return response()->json(['Success' => 'success']);
    }
}
