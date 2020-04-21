<?php

namespace App\Http\Controllers;

use App\Escola;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Escola as EscolaResource;

class EscolaControllerAPI extends Controller {
    public function store(Request $request) {
        $valid = validator($request->only('nome', 'localizacao'), [
            'nome'=> 'required|string|max:255',
            'localizacao' => 'required|string',
        ]);

        if ($valid->fails()) {
            $jsonError=response()->json($valid->errors()->all(), 400);
            return response()->json($jsonError);
        }
        $data = $request->only('nome', 'localizacao');

        $escola = Escola::create($data);
        $msg = "Escola criada com sucesso";
        $data = array($msg, $escola);
        return response()->json($data, 200);
    }

    public function update(Request $request, $id) {
        $valid = validator($request->only('nome', 'localizacao'), [
            'nome'=> 'required|string|max:255',
            'localizacao' => 'required|string',
        ]);

        if ($valid->fails()) {
            $jsonError=response()->json($valid->errors()->all(), 400);
            return response()->json($jsonError);
        }

        $escola = Escola::find($id);
        $escola["nome"] = $request["nome"];
        $escola["localizacao"] = $request["localizacao"];
        $escola->save();
        
        $msg = "Escola atualizada com sucesso";
        return response()->json($msg, 200);
    }

    public function remove($id){
        $escola = Escola::find($id);
        $escola->delete();
        $msg = 'Escola removida com sucesso';
        return response()->json($msg, 200);
    }

    public function getEscolas() {
        $per_page = empty(request('per_page')) ? 10 : (int)request('per_page');
        $escolas = Escola::paginate($per_page);
        return EscolaResource::collection($escolas);
    }
}
