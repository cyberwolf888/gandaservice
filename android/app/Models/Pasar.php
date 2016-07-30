<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Validator;

class Pasar extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pasar';
    protected $dates = ['deleted_at'];

    public $incrementing = false;

    public function validator(array $data)
    {
        return Validator::make($data, [
            'nama_pasar' => 'required|max:100'
        ]);
    }

    public function createId()
    {
        $lastRecord = $this->withTrashed()->orderBy('created_at', 'DESC')->first();
        if($lastRecord){
            $lastId = substr($lastRecord->id,2)+1;
            $newId = "PS".substr("0".$lastId,-2);
        }else{
            $newId = "PS01";
        }
        return $newId;
    }

    public function dagang()
    {
        return $this->hasMany('App\Models\Dagang', 'id_pasar');
    }

    public function pegawai()
    {
        return $this->hasMany('App\Models\Pegawai', 'id_pasar');
    }

    public function pungutan()
    {
        return $this->hasMany('App\Models\Pungutan', 'id_pasar');
    }

    protected static function boot() {
        parent::boot();

        static::deleting(function($model) {
            foreach ($model->dagang()->get() as $value) {
                $value->delete();
            }
        });
    }
}
