<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Mapel;
use App\Models\TingkatPendidikan;
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

    //
}