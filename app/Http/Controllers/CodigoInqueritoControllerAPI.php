<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CodigoInqueritoControllerAPI extends Controller
{
    public function isValid() {
        
        $cursos = Curso::all();

        return CursoResource::collection($cursos);
    }
}
