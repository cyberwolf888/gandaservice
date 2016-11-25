<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use App\Models\ProgramEdukasi;
use Illuminate\Http\Request;

class ProgramEdukasiController extends Controller
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
        $model = ProgramEdukasi::all();
        if($model && count($model)>0){
            $data = array();
            $i=0;
            foreach ($model as $row){
                $data[$i] = $row;
                $data[$i]['label_biaya'] = $row->biaya;
                $i++;
            }
            return response()->json(['status'=>1,'data'=>$data]);
        }else{
            return response()->json(['status'=>0]);
        }

    }

    //
}
