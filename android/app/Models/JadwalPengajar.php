<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalPengajar extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_jadwal_pengajar';

    public function mapel()
    {
        return $this->belongsTo('App\Models\Mapel','mapel_id');
    }

    public function cabang()
    {
        return $this->belongsTo('App\Models\Cabang','zona_id');
    }
}
