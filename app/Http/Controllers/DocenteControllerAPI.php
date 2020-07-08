<?php

namespace App\Http\Controllers;

use App\Docente;
use App\DocenteAtividade as Atividade;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Docente as DocenteResource;
use App\Http\Resources\DocenteAtividades as AtividadesSResource;

class DocenteControllerAPI extends Controller {
    public function store(Request $request) {
        $valid = validator($request->only('nome', 'telefone_interno', 'telemovel', 'email'), [
            'nome' => 'required|string|max:255',
            'telefone_interno' => ['required','string','max:9','min:9','regex:/^[0-9]*$/'],
            'telemovel' => ['required','string','max:9','min:9','regex:/^[0-9]*$/'],
            'email' => 'required|string|email|max:255',
        ]);

        if ($valid->fails()) {
            $jsonError=response()->json($valid->errors()->all(), 400);
            return response()->json($jsonError);
        }
        $data = $request->only('nome', 'telefone_interno', 'telemovel', 'email');

        $docente = Docente::create($data);
        $msg = "Docente criado com sucesso";
        $data = array($msg, $docente);
        return response()->json($data, 200);
    }

    public function associarAtividade(Request $request, $id) {
        $docente = Docente::findOrFail($id);
        $valid = validator($request->only('atividade', 'descricao_participacao'), [
            'atividade'=> 'required|integer',
            'descricao_participacao' => 'nullable|string',
        ]);

        if ($valid->fails()) {
            $jsonError=response()->json($valid->errors()->all(), 400);
            return response()->json($jsonError);
        }

        $data = $request->only('atividade');
        $data['docente'] = $id;
        if(!$request->only('descricao_participacao')) {
            $data['descricao_participacao'] = null;
        } else {
            $data['descricao_participacao'] = $request->descricao_participacao;
        }

        $atividade = Atividade::create($data);
        $msg = "Atividade associada com sucesso";
        $data = array($msg, $atividade);
        return response()->json($data, 200);
    }

    public function update(Request $request, $id) {
        $valid = validator($request->only('nome', 'telefone_interno', 'telemovel', 'email'), [
            'telefone_interno' => ['required','string','max:9','min:9','regex:/^[0-9]*$/'],
            'telemovel' => ['required','string','max:9','min:9','regex:/^[0-9]*$/'],
            'email' => 'required|string|email|max:255',
        ]);

        if ($valid->fails()) {
            $jsonError=response()->json($valid->errors()->all(), 400);
            return response()->json($jsonError);
        }

        $docente = Docente::find($id);
        $docente["telefone_interno"] = $request["telefone_interno"];
        $docente["telemovel"] = $request["telemovel"];
        $docente["email"] = $request["email"];
        $docente->save();
        
        $msg = "Docente atualizado com sucesso";
        return response()->json($msg, 200);
    }

    public function updateAtividade(Request $request, $aux, $id) {
        $docente = Docente::findOrFail($aux);
        $valid = validator($request->only('atividade', 'descricao_participacao'), [
            'atividade'=> 'required|integer',
            'descricao_participacao' => 'nullable|string',
        ]);

        if ($valid->fails()) {
            $jsonError=response()->json($valid->errors()->all(), 400);
            return response()->json($jsonError);
        }

        $atividade = Atividade::findOrFail($id);
        $atividade["docente"] = $aux;
        $atividade["atividade"] = $request["atividade"];

        if(!$request->only('descricao_participacao')) {
            $atividade['descricao_participacao'] = null;
        } else {
            $atividade['descricao_participacao'] = $request->descricao_participacao;
        }
        $atividade->save();
        
        $msg = "Atividade atualizada com sucesso";
        return response()->json($msg, 200);
    }

    public function remove($id){
        $docente = Docente::find($id);
        $docente->delete();
        $msg = 'Docente removido com sucesso';
        return response()->json($msg, 200);
    }

    public function desassociarAtividade($aux, $id) {
        $docente = Docente::findOrFail($aux);
        $atividade = Atividade::findOrFail($id);
        $atividade->delete();
        $msg = 'Atividade desassociada com sucesso';
        return response()->json($msg, 200);
    }

    public function getDocentes() {
        $arrayWhere = [];
        $per_page = empty(request('per_page')) ? 10 : (int)request('per_page');

        if(!empty(request('nome'))){
            array_push($arrayWhere, ['nome', 'LIKE' , '%'.request('nome').'%']);
        }
        if(!empty(request('telefone'))){
            array_push($arrayWhere, ['telefone_interno', 'LIKE' , '%'.request('telefone').'%']);
        }
        if(!empty(request('email'))){
            array_push($arrayWhere, ['email', 'LIKE' , '%'.request('email').'%']);
        }
        if(!empty(request('telemovel'))){
            array_push($arrayWhere, ['telemovel', 'LIKE' , '%'.request('telemovel').'%']);
        }

        $docentes = Docente::where($arrayWhere)->paginate($per_page);
        return DocenteResource::collection($docentes);
    }

    public function getDocenteAtividades($id) {
        $per_page = empty(request('per_page')) ? 10 : (int)request('per_page');
        $atividades = Atividade::where('docente', $id)->paginate($per_page);
        return AtividadesSResource::collection($atividades);
    }
}
