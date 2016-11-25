<?php
/**
 * Created by PhpStorm.
 * User: Karen
 * Date: 8/24/2016
 * Time: 8:27 PM
 */

namespace App\Http\Controllers;


use App\Models\BuktiPembayaran;
use App\Models\History;
use App\Models\Jadwal;
use App\Models\Paket;
use App\Models\Pembayaran;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
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

    public function getPayment(Request $request)
    {
        $user = User::find($request->input('user_id'));
        $siswa = $user->siswa;

        $pembayaran = Pembayaran::where('siswa_id',$siswa->id)->get();
        if(count($pembayaran)>0){
            $data= array();
            $i = 0;
            foreach ($pembayaran as $row){
                if($row->jenis_tagihan == Pembayaran::PROGRAM){
                    $jadwal = Jadwal::where('id',$row->jadwal_id)->with('paket')->first();
                    $data[$i]['id'] = $row->id;
                    $data[$i]['title'] = $jadwal->paket->nama;
                    $data[$i]['keterangan'] = $jadwal->paket->jumlah_pertemuan." x pertemuan";
                    $data[$i]['cost'] = number_format($row->jumlah,0,',','.');
                }elseif($row->jenis_tagihan == Pembayaran::JADWAL){
                    $jadwal = Jadwal::where('id',$row->jadwal_id)->with('mapel.tingkat')->first();
                    $data[$i]['id'] = $row->id;
                    $data[$i]['title'] = $jadwal->mapel->nama." - ".$jadwal->mapel->tingkat->nama;
                    $data[$i]['keterangan'] = $row->keterangan;
                    $data[$i]['cost'] = number_format($row->jumlah,0,',','.');
                }elseif($row->jenis_tagihan == Pembayaran::JARAK){
                    $jadwal = Jadwal::where('id',$row->jadwal_id)->with('mapel.tingkat')->first();
                    $data[$i]['id'] = $row->id;
                    $data[$i]['title'] = $jadwal->mapel->nama." - ".$jadwal->mapel->tingkat->nama;
                    $data[$i]['keterangan'] = $row->keterangan;
                    $data[$i]['cost'] = number_format($row->jumlah,0,',','.');
                }
                $i++;
            }

            $total = DB::select('SELECT SUM(jumlah) AS total FROM tb_pembayaran WHERE siswa_id = "'.$siswa->id.'"');
            $xtotal = number_format($total[0]->total,0,',','.');
            return response()->json(['status'=>1,'data'=>$data,'total'=>$xtotal]);

        }else{
            return response()->json(['status'=>0]);
        }
    }

    public function cekBuktiPembayaran(Request $request)
    {
        $pembayaran = BuktiPembayaran::where('pembayaran_id',$request->input('pembayaran_id'))->first();
        if(count($pembayaran)>0){
            return response()->json(['status'=>1]);
        }else{
            return response()->json(['status'=>0]);
        }
    }

    public function createBuktiPembayaran(Request $request)
    {
        $pembayaran = Pembayaran::find($request->input('payment_id'));
        $pembayaran->pembayaran_metode = Pembayaran::TRANSFER_BANK;
        $model = new BuktiPembayaran();
        $model->pembayaran_id = $request->input('payment_id');
        $model->user_id = $request->input('user_id');
        if ($request->hasFile('image_bukti')) {
            $photoFolder = base_path('../images/buktipembayaran');
            $image = $request->file('image_bukti');
            $ext = $image->getClientOriginalExtension();
            $filename = str_random(10).".".$ext;
            if($image->move($photoFolder, $filename)){
                $model->image = $filename;
            }
        }
        if($model->save()){
            $pembayaran->save();
            return response()->json(['status'=>1]);
        }else{
            return response()->json(['status'=>0]);
        }
    }
}