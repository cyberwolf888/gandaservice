<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailJadwal extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_detail_jadwal';

    public function jadwal()
    {
        return $this->belongsTo('App\Models\Jadwal','jadwal_id');
    }

}
