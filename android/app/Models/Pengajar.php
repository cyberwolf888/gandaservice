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

}
