<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MapelPengajar extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_mapel_pengajar';

    public function mapel()
    {
        return $this->belongsTo('App\Models\Mapel','mapel_id');
    }

    public function pengajar()
    {
        return $this->belongsTo('App\Models\Pengajar','pengajar_id');
    }
}
