<?php

namespace App\Http\Controllers;

use App\CodigoInquerito;
use Illuminate\Http\Request;

class CodigoInqueritoControllerAPI extends Controller
{
    public function getCodigoM(Request $request, $id) {
        $codigo = CodigoInquerito::findOrFail($id);

        return response()->json($codigo, 200);
    }

    public function store(Request $request) {
        $valid = validator($request->only('nome', 'validade'), [
            'nome' => 'required',
            'validade'=> 'required|boolean',
        ]);

        if ($valid->fails()) {
            $jsonError=response()->json($valid->errors()->all(), 400);
            return response()->json($jsonError);
        }

        $data = $request->only('validade', 'data');
        $data["data"] = date('Y-m-d H:i:s');

        $codigo = CodigoInquerito::create($data);
        $msg = "Código criado com sucesso";
        $data = array($msg, $codigo);
        return response()->json($data, 200);
    }

    public function update(Request $request, $id) {
        $codigo = CodigoInquerito::findOrFail($id);

        $codigo["nome"] = $request["nome"];
        $codigo["validade"] = $request["validade"];

        $codigo->save();

        $msg = "Codigo atualizado com sucesso";
        return response()->json($msg, 200);
    }
}
