<?php

namespace App\Http\Controllers;

use App\Models\CheckRating;
use App\Models\Jadwal;
use App\Models\Pengajar;
use App\Models\Rating;
use App\User;
use Illuminate\Http\Request;

class RatingController extends Controller
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

    public function cekRating(Request $request)
    {
        $user = User::find($request->input('user_id'));
        $siswa = $user->siswa;
        $cekRating = CheckRating::where('siswa_id',$siswa->id)->first();
        if(count($cekRating)>0){
            $pengajar = Pengajar::find($cekRating->pengajar_id);
            $jadwal = Jadwal::find($cekRating->jadwal_id);
            $data = array();

            $data['cek_id'] = $cekRating->id;
            $data['siswa_id'] = $cekRating->siswa_id;
            $data['pengajar_id'] = $cekRating->pengajar_id;
            $data['jadwal_id'] = $cekRating->jadwal_id;
            $data['nama_pengajar'] = $pengajar->fullname;
            $data['photo'] = $pengajar->photo;
            $data['telp'] = $pengajar->pengajar_cp;
            $data['alamat'] = $pengajar->pengajar_alamat;
            $data['label_mapel'] = $jadwal->mapel->nama." - ".$jadwal->mapel->tingkat->nama;

            return response()->json(['status'=>1,'data'=>$data]);
        }else{
            return response()->json(['status'=>0]);
        }
    }

    public function createRating(Request $request)
    {
        $rating = new Rating();
        $rating->pengajar_id = $request->input('pengajar_id');
        $rating->siswa_id = $request->input('siswa_id');
        $rating->jadwal_id = $request->input('jadwal_id');
        $rating->rating = $request->input('rating');
        if($rating->save()){
            $cekRating = CheckRating::find($request->input('cek_id'));
            if($cekRating->delete()){
                return response()->json(['status'=>1]);
            }
        }else{
            return response()->json(['status'=>0]);
        }
    }

    //
}
