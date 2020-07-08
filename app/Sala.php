<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sala extends Model {
    public $timestamps = FALSE;

    public function a_verao_docente() {
        return $this->hasMany('App\AVeraoDocente', 'sala', 'id');
    }

    protected $fillable = [
        'edificio', 'nome',
    ];
}
