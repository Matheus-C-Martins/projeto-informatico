<?php

namespace App\Http\Controllers;

use App\Atividade;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Atividade as AtividadeResource;

class AtividadeControllerAPI extends Controller {
    public function store(Request $request) {
        
    }

    public function update(Request $request, $id) {
        
    }

    public function remove($id){
        
    }

    public function getAtividades() {
        $per_page = empty(request('per_page')) ? 10 : (int)request('per_page');
        $atividades = Atividade::paginate($per_page);
        return AtividadeResource::collection($atividades);
    }
}
