<?php

namespace App\Http\Controllers;

use App\Participante;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Participante as ParticipanteResource;

class AcademiaParticipanteControllerAPI extends Controller {
    public function store(Request $request) {
        $valid = validator($request->only('nome', 'escola', 'ano', 'curso', 'data_de_nascimento', 'encarregado_de_educacao',
                                          'contacto_do_aluno', 'contacto_do_ee', 'regiao', 'academia_de_verao'), [
            'nome'=> 'required|string',
            'escola'=> 'required|integer',
            'ano'=> 'required|string',
            'curso'=> 'required|string',
            'data_de_nascimento'=> 'required|date_format:Y-m-d',
            'contacto_do_aluno' => ['required','string','max:9','min:9','regex:/^[0-9]*$/'],
            'contacto_do_ee' => ['required','string','max:9','min:9','regex:/^[0-9]*$/'],
            'encarregado_de_educacao' => 'required|string',
            'regiao'=> 'required|string',
            'academia_de_verao' => 'required|integer',
        ]);

        if ($valid->fails()) {
            $jsonError=response()->json($valid->errors()->all(), 400);
            return response()->json($jsonError);
        }

        $data = $request->only('nome', 'escola', 'ano', 'curso', 'data_de_nascimento', 'encarregado_de_educacao',
                               'contacto_do_aluno', 'contacto_do_ee', 'regiao', 'academia_de_verao');

        $participante = Participante::create($data);
        $msg = "Participante criado com sucesso";
        $data = array($msg, $participante);
        return response()->json($data, 200);
    }

    public function update(Request $request, $id) {
        $valid = validator($request->only('nome', 'escola', 'ano', 'curso', 'data_de_nascimento', 'encarregado_de_educacao',
                                          'contacto_do_aluno', 'contacto_do_ee', 'regiao', 'academia_de_verao'), [
            'nome'=> 'required|string',
            'escola'=> 'required|integer',
            'ano'=> 'required|string',
            'curso'=> 'required|string',
            'data_de_nascimento'=> 'required|date_format:Y-m-d',
            'contacto_do_aluno' => ['required','string','max:9','min:9','regex:/^[0-9]*$/'],
            'contacto_do_ee' => ['required','string','max:9','min:9','regex:/^[0-9]*$/'],
            'regiao'=> 'required|string',
            'encarregado_de_educacao'=> 'required|string',
            'academia_de_verao' => 'required|integer',
        ]);

        if ($valid->fails()) {
            $jsonError=response()->json($valid->errors()->all(), 400);
            return response()->json($jsonError);
        }

        $participante = Participante::findOrFail($id);
        $participante["nome"] = $request["nome"];
        $participante["escola"] = $request["escola"];
        $participante["ano"] = $request["ano"];
        $participante["curso"] = $request["curso"];
        $participante["data_de_nascimento"] = $request["data_de_nascimento"];
        $participante["contacto_do_aluno"] = $request["contacto_do_aluno"];
        $participante["contacto_do_ee"] = $request["contacto_do_ee"];
        $participante["encarregado_de_educacao"] = $request["encarregado_de_educacao"];
        $participante["regiao"] = $request["regiao"];
        $participante["academia_de_verao"] = $request["academia_de_verao"];
        $participante->save();
        
        $msg = "Participante atualizado com sucesso";
        return response()->json($msg, 200);
    }

    public function remove($id){
        $participante = Participante::findOrFail($id);
        $participante->delete();
        $msg = 'Participante removido com sucesso';
        return response()->json($msg, 200);
    }

    public function getParticipantes() {
        $arrayWhere = [];
        $per_page = empty(request('per_page')) ? 10 : (int)request('per_page');

        if(!empty(request('escola'))){
            array_push($arrayWhere, ['escola', request('escola')]);
        }
        if(!empty(request('nome'))){
            array_push($arrayWhere, ['nome', 'LIKE' , '%'.request('nome').'%']);
        }
        if(!empty(request('ano'))){
            array_push($arrayWhere, ['ano', 'LIKE' , '%'.request('ano').'%']);
        }
        if(!empty(request('curso'))){
            array_push($arrayWhere, ['curso', 'LIKE' , '%'.request('curso').'%']);
        }
        if(!empty(request('academia_de_verao'))){
            array_push($arrayWhere, ['academia_de_verao', request('academia_de_verao')]);
        }

        $participantes = Participante::where($arrayWhere)->paginate($per_page);
        return ParticipanteResource::collection($participantes);
    }

    public function getParticipantesNum() {
        return Participante::count();
    }
}
