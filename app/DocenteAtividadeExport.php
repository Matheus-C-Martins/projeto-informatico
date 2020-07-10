<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocenteAtividadeExport extends Model {
    public $timestamps = FALSE;

    protected $fillable = [
        'atividade', 'data', 'hora', 'tipo_de_atividade', 'descricao_participacao'
    ];
}
