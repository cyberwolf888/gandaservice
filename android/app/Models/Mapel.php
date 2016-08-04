<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_mapel';

    public function tingkat()
    {
        return $this->belongsTo('App\Models\TingkatPendidikan','tingkat_pendidikan');
    }

    public function bedebah(){
        return "asdasdasdasd";
    }

}
