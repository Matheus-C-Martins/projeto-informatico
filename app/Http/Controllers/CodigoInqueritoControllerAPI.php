<?php

namespace App\Http\Controllers;

use App\CodigoInquerito;
use Illuminate\Http\Request;

class CodigoInqueritoControllerAPI extends Controller
{
    public function getValidadeM(Request $request, $id) {
        $codigo = CodigoInquerito::findOrFail($id);

        return $codigo["validade"];
    }

    public function store(Request $request) {
        $valid = validator($request->only('validade', 'data'), [
            'validade'=> 'required|boolean',
            'data' => 'required|date_format:Y-m-d H:i:s',
        ]);

        if ($valid->fails()) {
            $jsonError=response()->json($valid->errors()->all(), 400);
            return response()->json($jsonError);
        }

        $data = $request->only('validade', 'data');

        $codigo = CodigoInquerito::create($data);
        $msg = "Código criado com sucesso";
        $data = array($msg, $codigo);
        return response()->json($data, 200);
    }

    public function isValid(Request $request, $id) {
        $codigo = CodigoInquerito::findOrFail($id);
        $codigo["validade"] = $request["validade"]
        $codigo->save();

        $msg = "Validade atualizada com sucesso";
        return response()->json($msg, 200);
    }
}
