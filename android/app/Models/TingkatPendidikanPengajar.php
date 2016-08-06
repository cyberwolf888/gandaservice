<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TingkatPendidikanPengajar extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_tingkat_pendidikan_pengajar';

    public function tingkat_pendidikan()
    {
        return $this->belongsTo('App\Models\TingkatPendidikan','tingkat_pendidikan_id');
    }
}
