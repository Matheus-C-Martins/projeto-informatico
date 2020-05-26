<?php

namespace App\Http\Controllers;

use App\Curso;
use Illuminate\Http\Request;
use App\Http\Resources\Curso as CursoResource;

class CursosControllerAPI extends Controller
{
    public function getCursos() {
        return Curso::all();
    }
}