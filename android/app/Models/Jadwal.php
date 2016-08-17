<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_jadwal';

    public function detail_jadwal()
    {
        return $this->hasMany('App\Models\DetailJadwal','jadwal_id');
    }

}
