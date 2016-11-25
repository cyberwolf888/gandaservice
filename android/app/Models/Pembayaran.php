<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    const PROGRAM = 'PROGRAM';
    const JADWAL = 'JADWAL';
    const JARAK = 'JARAK';
    const CASH = '1';
    const TRANSFER_BANK = '2';
    const PROSES = '1';
    const LUNAS = '2';
    protected $table = 'tb_pembayaran';


}
