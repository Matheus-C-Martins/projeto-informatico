<?php

namespace App\Http\Controllers;

use App\CodigoInquerito as Codigo;
use App\Http\Resources\CodigoInquerito as CodigoResource;
use Illuminate\Http\Request;

class CodigoInqueritoControllerAPI extends Controller {
    public function getCodigoM(Request $request, $id) {
        $codigo = Codigo::findOrFail($id);
        return response()->json($codigo, 200);
    }

    public function getCodigos() {
        $per_page = empty(request('per_page')) ? 10 : (int)request('per_page');
        $codigos = Codigo::paginate($per_page);
        return CodigoResource::collection($codigos);
    }

    public function store(Request $request) {
        $valid = validator($request->only('nome'), [
            'nome' => 'required|string',
        ]);

        if ($valid->fails()) {
            $jsonError=response()->json($valid->errors()->all(), 400);
            return response()->json($jsonError);
        }

        $data = $request->only('nome');
        $data["validade"] = 1;
        $data["data"] = date('Y-m-d H:i:s');

        $codigo = Codigo::create($data);
        $msg = "Código criado com sucesso";
        $data = array($msg, $codigo);
        return response()->json($data, 200);
    }

    public function update(Request $request, $id) {
        $codigo = Codigo::findOrFail($id);
        $valid = validator($request->only('nome', 'validade'), [
            'nome' => 'required|string',
            'validade' => 'required|boolean',
        ]);

        if ($valid->fails()) {
            $jsonError=response()->json($valid->errors()->all(), 400);
            return response()->json($jsonError);
        }

        $codigo["nome"] = $request["nome"];
        $codigo["validade"] = $request["validade"];

        $codigo->save();

        $msg = "Código atualizado com sucesso";
        return response()->json($msg, 200);
    }
}
