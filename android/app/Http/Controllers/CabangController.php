<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use Illuminate\Http\Request;

class CabangController extends Controller
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
        $cabang = Cabang::all();
        if($cabang && count($cabang)>0){
            return response()->json(['status'=>1,'data'=>$cabang]);
        }else{
            return response()->json(['status'=>0]);
        }

    }

    //
}
