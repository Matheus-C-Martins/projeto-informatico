<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoCurso extends Model {
    public $timestamps = FALSE;
    
    public function curso() {
        return $this->belongsTo('App\Curso', 'tipo');
    }

    protected $fillable = [
        'nome',
    ];
}