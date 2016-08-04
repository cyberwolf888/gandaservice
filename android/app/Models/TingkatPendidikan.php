<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TingkatPendidikan extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_tingkat_pendidikan';

    public function mapel()
    {
        return $this->hasMany('App\Models\Mapel','tingkat_pendidikan');
    }

}
