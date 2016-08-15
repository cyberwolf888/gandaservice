<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
