<?php

namespace App\Http\Controllers;

use App\AtividadeAVerao;
use App\AVeraoDocente;
use App\AVeraoParticipante;
use App\Sala;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\AtividadeAVerao as AtividadeResource;
use App\Http\Resources\AVeraoDocente as DocenteResource;
use App\Http\Resources\AVeraoParticipante as ParticipanteResource;
use App\Http\Resources\Sala as SalaResource;

class AcademiaAtividadeControllerAPI extends Controller {
    public function store(Request $request) {
        $valid = validator($request->only('nome', 'academia_de_verao', 'observacoes', 'tipo'), [
            'nome'=> 'required|string',
            'academia_de_verao' => 'required|integer',
            'observacoes' => 'nullable|string',
            'tipo' => ['required', 'regex:/^[aula]{4}$|^[workshop]{8}$/'],
        ]);

        if ($valid->fails()) {
            $jsonError=response()->json($valid->errors()->all(), 400);
            return response()->json($jsonError);
        }

        $data = $request->only('nome', 'academia_de_verao', 'tipo');

        if(!$request->only('observacoes')) {
            $data['observacoes'] = null;
        } else {
            $data['observacoes'] = $request->observacoes;
        }

        $atividade = AtividadeAVerao::create($data);
        $msg = "Atividade criada com sucesso";
        $data = array($msg, $atividade);
        return response()->json($data, 200);
    }

    public function associarDocente(Request $request, $id) {
        $atividade = AtividadeAVerao::findOrFail($id);
        $valid = validator($request->only('docente', 'data', 'sala'), [
            'docente'=> 'required|integer',
            'sala'=> 'required|integer',
            'data' => 'required|date_format:Y-m-d H:i:s',
        ]);

        if ($valid->fails()) {
            $jsonError=response()->json($valid->errors()->all(), 400);
            return response()->json($jsonError);
        }

        $data = $request->only('docente', 'data', 'sala');
        $data['atividade'] = $id;

        $docente = AVeraoDocente::create($data);
        $msg = "Docente associado com sucesso";
        $data = array($msg, $docente);
        return response()->json($data, 200);
    }

    public function associarParticipante(Request $request, $id) {
        $atividade = AtividadeAVerao::findOrFail($id);
        $valid = validator($request->only('participante', 'data'), [
            'participante'=> 'required|integer',
            'data' => 'required|date_format:Y-m-d H:i:s',
        ]);

        if ($valid->fails()) {
            $jsonError=response()->json($valid->errors()->all(), 400);
            return response()->json($jsonError);
        }

        $data = $request->only('participante', 'data');
        $data['atividade'] = $id;

        $participante = AVeraoParticipante::create($data);
        $msg = "Participante associado com sucesso";
        $data = array($msg, $participante);
        return response()->json($data, 200);
    }

    public function update(Request $request, $id) {
        $valid = validator($request->only('nome', 'academia_de_verao', 'observacoes', 'tipo'), [
            'nome'=> 'required|string',
            'academia_de_verao' => 'required|integer',
            'observacoes' => 'nullable|string',
            'tipo' => ['required', 'regex:/^[aula]{4}$|^[workshop]{8}$/'],
        ]);

        if ($valid->fails()) {
            $jsonError=response()->json($valid->errors()->all(), 400);
            return response()->json($jsonError);
        }

        $atividade = AtividadeAVerao::findOrFail($id);
        $atividade["nome"] = $request["nome"];
        $atividade["academia_de_verao"] = $request["academia_de_verao"];
        $atividade["tipo"] = $request["tipo"];
        $atividade["observacoes"] = $request["observacoes"];
        $atividade->save();
        
        $msg = "Atividade atualizada com sucesso";
        return response()->json($msg, 200);
    }

    public function updateDocente(Request $request, $aux, $id) {
        $atividade = AtividadeAVerao::findOrFail($aux);
        $valid = validator($request->only('data', 'sala'), [
            'sala'=> 'required|integer',
            'data' => 'required|date_format:Y-m-d H:i:s',
        ]);

        if ($valid->fails()) {
            $jsonError=response()->json($valid->errors()->all(), 400);
            return response()->json($jsonError);
        }

        $docente = AVeraoDocente::findOrFail($id);
        $docente["atividade"] = $aux;
        $docente["sala"] = $request["sala"];
        $docente["data"] = $request["data"];
        $docente->save();
        
        $msg = "Docente atualizado com sucesso";
        return response()->json($msg, 200);
    }

    public function updateParticipante(Request $request, $aux, $id) {
        $atividade = AtividadeAVerao::findOrFail($aux);
        $valid = validator($request->only('data'), [
            'data' => 'required|date_format:Y-m-d H:i:s',
        ]);

        if ($valid->fails()) {
            $jsonError=response()->json($valid->errors()->all(), 400);
            return response()->json($jsonError);
        }

        $participante = AVeraoParticipante::findOrFail($id);
        $participante["atividade"] = $aux;
        $participante["data"] = $request["data"];
        $participante->save();
        
        $msg = "Participante atualizado com sucesso";
        return response()->json($msg, 200);
    }

    public function remove($id){
        $atividade = AtividadeAVerao::findOrFail($id);
        $atividade->delete();
        $msg = 'Atividade removida com sucesso';
        return response()->json($msg, 200);
    }

    public function desassociarDocente($aux, $id) {
        $atividade = AtividadeAVerao::findOrFail($aux);
        $docente = AVeraoDocente::findOrFail($id);
        $docente->delete();
        $msg = 'Docente desassociado com sucesso';
        return response()->json($msg, 200);
    }

    public function desassociarParticipante($aux, $id) {
        $atividade = AtividadeAVerao::findOrFail($aux);
        $participante = AVeraoParticipante::findOrFail($id);
        $participante->delete();
        $msg = 'Participante desassociado com sucesso';
        return response()->json($msg, 200);
    }

    public function getAtividades() {
        $arrayWhere = [];
        $per_page = empty(request('per_page')) ? 10 : (int)request('per_page');

        if(!empty(request('academia_de_verao'))){
            array_push($arrayWhere, ['academia_de_verao', request('academia_de_verao')]);
        }
        if(!empty(request('nome'))){
            array_push($arrayWhere, ['nome', 'LIKE' , '%'.request('nome').'%']);
        }
        if(!empty(request('tipo'))){
            array_push($arrayWhere, ['tipo', request('tipo')]);
        }

        $atividades = AtividadeAVerao::where($arrayWhere)->paginate($per_page);
        return AtividadeResource::collection($atividades);
    }

    public function getSalas() {
        $salas = Sala::all();
        return SalaResource::collection($salas);
    }

    public function getAtividadesNum() {
        return AtividadeAVerao::count();
    }

    public function getAtividadesParticipantes($id) {
        $per_page = empty(request('per_page')) ? 10 : (int)request('per_page');
        $participantes = AVeraoParticipante::where('atividade', $id)->paginate($per_page);
        return ParticipanteResource::collection($participantes);
    }

    public function getAtividadesDocentes($id) {
        $per_page = empty(request('per_page')) ? 10 : (int)request('per_page');
        $docentes = AVeraoDocente::where('atividade', $id)->paginate($per_page);
        return DocenteResource::collection($docentes);
    }
}
