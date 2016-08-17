<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paket extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_paket';

    public function tarif()
    {
        return $this->belongsTo('App\Models\Tarif','tarif_id');
    }

}
