<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\TingkatPendidikan;
use Illuminate\Http\Request;

class TingkatController extends Controller
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
        $model = TingkatPendidikan::all();
        if($model && count($model)>0){
            return response()->json(['status'=>1,'data'=>$model]);
        }else{
            return response()->json(['status'=>0]);
        }

    }

    //
}
