<?php

namespace App\Http\Controllers;

use App\Atividade;
use App\WorkshopsAtividade;
use App\Workshop;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Atividade as AtividadeResource;
use App\Http\Resources\AtividadesWorkshop as AtividadesWorkshopResource;
use App\Http\Resources\Workshop as WorkshopResource;

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
            'tipo_de_atividade' => ['required', 'regex:/^[diaESTG]{7}$|^[workshop]{8}$|^[seminario]{9}$/'],
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

    public function associar(Request $request, $id) {
        $atividade = Atividade::findOrFail($id);
        $valid = validator($request->only('workshop', 'data', 'descricao'), [
            'workshop'=> 'required|integer',
            'data' => 'required|date_format:Y-m-d H:i:s',
            'descricao' => 'nullable|string',
        ]);

        if ($valid->fails()) {
            $jsonError=response()->json($valid->errors()->all(), 400);
            return response()->json($jsonError);
        }

        $data = $request->only('workshop', 'data');
        $data['atividade'] = $id;
        if(!$request->only('descricao')) {
            $data['descricao'] = null;
        } else {
            $data['descricao'] = $request->descricao;
        }

        $workshop = WorkshopsAtividade::create($data);
        $msg = "Workshop associado com sucesso";
        $data = array($msg, $workshop);
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

    public function updateWorkshop(Request $request, $aux, $id) {
        $atividade = Atividade::findOrFail($aux);
        $valid = validator($request->only('workshop', 'data', 'descricao'), [
            'workshop'=> 'required|integer',
            'data' => 'required|date_format:Y-m-d H:i:s',
            'descricao' => 'nullable|string',
        ]);

        if ($valid->fails()) {
            $jsonError=response()->json($valid->errors()->all(), 400);
            return response()->json($jsonError);
        }

        $workshop = WorkshopsAtividade::findOrFail($id);
        $workshop["atividade"] = $aux;
        $workshop["workshop"] = $request["workshop"];
        $workshop["data"] = $request["data"];
        $workshop["descricao"] = $request["descricao"];
        $workshop->save();
        
        $msg = "Workshop atualizado com sucesso";
        return response()->json($msg, 200);
    }

    public function remove($id){
        $atividade = Atividade::findOrFail($id);
        $atividade->delete();
        $msg = 'Atividade removida com sucesso';
        return response()->json($msg, 200);
    }

    public function desassociarWorkshop($aux, $id) {
        $atividade = Atividade::findOrFail($aux);
        $workshop = WorkshopsAtividade::findOrFail($id);
        $workshop->delete();
        $msg = 'Workshop desassociado com sucesso';
        return response()->json($msg, 200);
    }

    public function getAtividades() {
        $arrayWhere = [];
        $per_page = empty(request('per_page')) ? 10 : (int)request('per_page');

        if(!empty(request('escola'))){
            array_push($arrayWhere, ['escola', request('escola')]);
        }
        if(!empty(request('turma'))){
            array_push($arrayWhere, ['turma', 'LIKE' , '%'.request('turma').'%']);
        }
        if(!empty(request('ano'))){
            array_push($arrayWhere, ['ano', 'LIKE' , '%'.request('ano').'%']);
        }
        if(!empty(request('tipo_atividade'))){
            array_push($arrayWhere, ['tipo_de_atividade', request('tipo_atividade')]);
        }
        if(!empty(request('dataInicio'))){
            array_push($arrayWhere, ['data', '>=', request('dataInicio')]);
        }
        if(!empty(request('dataFim'))){
            array_push($arrayWhere, ['data', '<=', request('dataFim')]);
        }

        $atividades = Atividade::where($arrayWhere)->paginate($per_page);
        return AtividadeResource::collection($atividades);
    }

    public function getAtividadesWorkshops($id) {
        $per_page = empty(request('per_page')) ? 10 : (int)request('per_page');
        $workshops = WorkshopsAtividade::where('atividade', $id)->paginate($per_page);
        return AtividadesWorkshopResource::collection($workshops);
    }

    public function getWorkshops() {
        $workshops = Workshop::all();
        return WorkshopResource::collection($workshops);
    }

    public function getAtividadesAno() {
        $arrayWhere = [];
        $today = Carbon::now()->format('Y-m-d H:i:s');
        $end = Carbon::now()->format('Y')."-12-31 23:59:59";
        
        array_push($arrayWhere, ['data', '>=', $today]);
        array_push($arrayWhere, ['data', '<=', $end]);
        return Atividade::where($arrayWhere)->count();
    }
}
