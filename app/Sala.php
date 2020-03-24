<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sala extends Model {
    public function a_verao_docente() {
        return $this->hasMany('App\A_VeraoDocente', 'Sala', 'id');
    }

    protected $fillable = [
        'Nome',
    ];
}
