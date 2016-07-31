<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use Illuminate\Http\Request;

class ArticleController extends Controller
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
        $article = Artikel::all();
        if($article && count($article)>0){
            return response()->json(['status'=>1,'data'=>$article]);
        }else{
            return response()->json(['status'=>0]);
        }

    }

    //
}
