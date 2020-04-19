<?php

namespace App\Http\Controllers;

use App\Docente;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DocenteControllerAPI extends Controller {
    public function store(Request $request) {
        $valid = validator($request->only('nome', 'telefone_interno', 'telefone', 'email'), [
            'nome'=> 'required|string|max:255',
            'telefone_interno' => ['required','string','max:9','min:9','regex:/^[0-9]*$/'],
            'telefone' => ['required','string','max:9','min:9','regex:/^[0-9]*$/'],
            'email' => 'required|string|email|max:255',
        ]);

        if ($valid->fails()) {
            $jsonError=response()->json($valid->errors()->all(), 400);
            return response()->json($jsonError);
        }
        $data = $request->only('nome', 'telefone_interno', 'telefone', 'email');

        $docente = Docente::create($data);
        $msg = "Docente criado com sucesso";
        $data = array($msg, $docente);
        return response()->json($data, 200);
    }

    public function update(Request $request, $id) {
        $valid = validator($request->only('nome', 'telefone_interno', 'telefone', 'email'), [
            'nome'=> 'required|string|max:255',
            'telefone_interno' => ['required','string','max:9','min:9','regex:/^[0-9]*$/'],
            'telefone' => ['required','string','max:9','min:9','regex:/^[0-9]*$/'],
            'email' => 'required|string|email|max:255',
        ]);

        if ($valid->fails()) {
            $jsonError=response()->json($valid->errors()->all(), 400);
            return response()->json($jsonError);
        }

        $docente = Docente::find($id);
        $docente["telefone_interno"] = $request["telefone_interno"];
        $docente["telefone"] = $request["telefone"];
        $docente["email"] = $request["email"];
        $docente->save();
        
        $msg = "Docente atualizado com sucesso";
        return response()->json($msg, 200);
    }

    public function remove($id){
        $docente = Docente::find($id);
        $docente->delete();
        $msg = 'Docente removido com sucesso';
        return response()->json($msg, 200);
    }

    public function getDocentes() {
        $per_page = empty(request('per_page')) ? 10 : (int)request('per_page');
        $docentes = Docente::paginate($per_page);
        return $docentes;
    }
}
