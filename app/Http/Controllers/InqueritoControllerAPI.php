<?php

namespace App\Http\Controllers;

use App\Inquerito;
use Illuminate\Http\Request;

class InqueritoControllerAPI extends Controller {
    public function getCodigoInquerito($id) {
        $per_page = empty(request('per_page')) ? 10 : (int)request('per_page');
        $inqueritos = Inquerito::where('codigo_id', $id)->paginate($per_page);
        return InqueritoResource::collection($inqueritos);
    }

    public function getInqueritos() {
        $per_page = empty(request('per_page')) ? 10 : (int)request('per_page');
        $inqueritos = Inquerito::paginate($per_page);
        return InqueritoResource::collection($inqueritos);
    }

    public function storeM(Request $request) {
        $valid = validator($request->only('resposta1', 'resposta2', 'resposta3', 'resposta4', 'resposta5', 'resposta6', 'sugestao', 'data', 'codigo_id'), [
            'resposta1'=> 'required|integer',
            'resposta2' => 'required|integer',
            'resposta3' => 'required|integer',
            'resposta4' => 'required|integer',
            'resposta5' => 'required|integer',
            'resposta6' => 'required|integer',
            'sugestao' => 'nullable|string',
            'data' => 'required|date_format:Y-m-d H:i:s',
            'codigo_id' => 'required|integer',
        ]);

        if ($valid->fails()) {
            $jsonError=response()->json($valid->errors()->all(), 400);
            return response()->json($jsonError);
        }

        $data = $request->only('resposta1', 'resposta2', 'resposta3', 'resposta4', 'resposta5', 'resposta6', 'sugestao', 'data', 'codigo_id');

        $inquerito = Inquerito::create($data);
        return response()->json($inquerito, 200);
    }
}
