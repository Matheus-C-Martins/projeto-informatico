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
        $valid = validator($request->only('contacto', 'numero_de_alunos', 'escola', 'data', 'turma',
                                          'ano', 'duracao', 'descricao', 'tipo_de_atividade'), [
            'contacto'=> 'required|integer',
            'escola' => 'required|integer',
            'numero_de_alunos' => 'required|integer',
            'ano' => 'required|string',
            'turma' => 'required|string',
            'data' => 'required|date_format:Y-m-d H:i:s',
            'duracao' => 'required|date_format:H:i:s',
            'descricao' => 'nullable|string',
            'tipo_de_atividade' => ['required', 'regex:/^[diaESTG]{7}$|^[workshop]{8}$/'],
        ]);

        if ($valid->fails()) {
            $jsonError=response()->json($valid->errors()->all(), 400);
            return response()->json($jsonError);
        }

        $data = $request->only('contacto', 'escola', 'numero_de_alunos', 'turma',
                               'ano', 'data', 'duracao', 'tipo_de_atividade');

        if(!$request->only('descricao')) {
            $data['descricao'] = null;
        } else {
            $data['descricao'] = $request->descricao;
        }

        $atividade = Atividade::create($data);
        $msg = "Atividade criada com sucesso";
        $data = array($msg, $atividade);
        return response()->json($data, 200);
    }

    public function update(Request $request, $id) {
        $valid = validator($request->only('contacto', 'escola', 'numero_de_alunos', 'descricao'), [
            'contacto'=> 'required|integer',
            'escola' => 'required|integer',
            'numero_de_alunos' => 'required|integer',
            'descricao' => 'nullable|string',
        ]);

        if ($valid->fails()) {
            $jsonError=response()->json($valid->errors()->all(), 400);
            return response()->json($jsonError);
        }

        $atividade = Atividade::findOrFail($id);
        $atividade["contacto"] = $request["contacto"];
        $atividade["escola"] = $request["escola"];
        $atividade["numero_de_alunos"] = $request["numero_de_alunos"];
        $atividade["descricao"] = $request["descricao"];
        $atividade->save();
        
        $msg = "Atividade atualizada com sucesso";
        return response()->json($msg, 200);
    }

    public function remove($id){
        $atividade = Atividade::findOrFail($id);
        $atividade->delete();
        $msg = 'Atividade removida com sucesso';
        return response()->json($msg, 200);
    }

    public function getAtividades() {
        $per_page = empty(request('per_page')) ? 10 : (int)request('per_page');
        $atividades = Atividade::paginate($per_page);
        return AtividadeResource::collection($atividades);
    }
}
