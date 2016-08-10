<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Mapel;
use App\Models\MapelPengajar;
use App\Models\TingkatPendidikan;
use App\User;
use Illuminate\Http\Request;

class MapelController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function showAll(Request $request)
    {
        $tingkat_pendidikan = explode(';', substr($request->input('tingkat_pendidikan'), 0,-1));
        $where = "";
        foreach ($tingkat_pendidikan as $key=>$mtingkat){
            $where.=" tingkat_pendidikan='$mtingkat' OR";
        }
        $where = substr($where, 0, -3);
        $model = Mapel::with('tingkat')
            ->whereRaw($where)
            ->get();
        if($model && count($model)>0){
            $data = array();
            foreach ($model as $row){
                $mData = array();
                $mData['id'] = $row->id;
                $mData['nama'] = $row->nama." - ".$row->tingkat->nama;
                $mData['tingkat_pendidikan'] = $row->tingkat_pendidikan;
                array_push($data, $mData);
            }
            return response()->json(['status'=>1,'data'=>$data]);
        }else{
            return response()->json(['status'=>0]);
        }
    }

    public function getMapelPengajar(Request $request)
    {
        $user_id = $request->input('user_id');
        $user = User::find($user_id);
        $pengajar = $user->pengajar;
        $mapel_pengajar = MapelPengajar::where('pengajar_id',$pengajar->id)->with('mapel.tingkat')->get();
        $data = array();
        if(count($mapel_pengajar)>0){
            foreach ($mapel_pengajar as $mapel){
                array_push($data, ['id'=>$mapel->mapel_id,'label'=>$mapel->mapel->nama . " - " . $mapel->mapel->tingkat->nama]);
            }
            return response()->json(['status'=>1,'data'=>$data]);
        }else{
            return response()->json(['status'=>0]);
        }
    }

    //
}
