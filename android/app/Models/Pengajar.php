<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Pengajar extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    const AVALAIBLE = '200';
    const NOT_AVALAIBLE = '400';
    protected $table = 'tb_pengajar';

    public function cabang()
    {
        return $this->belongsTo('App\Models\Cabang','zona_id');
    }
    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }

    public function getRating()
    {
        $rating = DB::select('SELECT pengajar_id,SUM(rating) AS total, COUNT(rating) AS jumlah FROM tb_rating WHERE pengajar_id = "'.$this->id.'"');
        if($rating[0]->jumlah>0){
            $average = (float) $rating[0]->total/$rating[0]->jumlah;
            return round($average, 1, PHP_ROUND_HALF_DOWN);
        }else{
            return (float) 0;
        }

    }
}
