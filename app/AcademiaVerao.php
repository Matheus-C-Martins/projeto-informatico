<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AcademiaVerao extends Model {
    public function participante() {
        return $this->belongsTo('App\Participante', 'id', 'Participante');
    }

    protected $fillable = [
        'Data', 'Participante',
    ];
}
