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

    public static function getLabelTanggal($tanggal)
    {
        $hari = ['Sunday'=>'Minggu','Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu'];
        return $hari[date("l", strtotime($tanggal))].", ".date("d-m-Y", strtotime($tanggal));
    }

}
