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

    public function siswa()
    {
        return $this->belongsTo('App\Models\Siswa', 'siswa_id');
    }

    public function mapel()
    {
        return $this->belongsTo('App\Models\Mapel','mapel_id');
    }

    public function paket()
    {
        return $this->belongsTo('App\Models\Paket','paket_id');
    }

}
