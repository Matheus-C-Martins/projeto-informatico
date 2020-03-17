<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Docente extends Model {
    public function docentes_atividade() {
        return $this->belongsTo('App\DocentesAtividade', 'Docente');
    }

    protected $fillable = [
        'Nome', 'Telefone Interno', 'Telemovel', 'E-mail',
    ];
}
