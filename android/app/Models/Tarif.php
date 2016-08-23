<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tarif extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_tarif';

    public static function getTarifByRate($rate){
        $tarif = $rating = DB::select('SELECT * FROM tb_tarif WHERE tipe="T2" AND keterangan<="'.$rate.'" ORDER BY keterangan DESC LIMIT 1');
        return $tarif[0]->harga;
    }

}
