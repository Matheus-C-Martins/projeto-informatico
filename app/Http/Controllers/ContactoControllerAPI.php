<?php

namespace App\Http\Controllers;

use App\Contacto;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Contacto as ContactoResource;

class ContactoControllerAPI extends Controller {
    public function store(Request $request) {
        $valid = validator($request->only('nome', 'telefone', 'email', 'sexo'), [
            'nome'=> 'required|string|max:255',
            'telefone' => 'required|string|max:9',
            'email' => 'required|string|email|max:255',
            'sexo' => ['required', 'regex:/^[masculino]{9}$|^[feminino]{8}$|^[outro]{5}$/'],
        ]);

        if ($valid->fails()) {
            $jsonError=response()->json($valid->errors()->all(), 400);
            return response()->json($jsonError);
        }

        $data = $request->only('Nome', 'Telefone', 'Email', 'Sexo');
        $contacto = Contacto::create($data);
        $msg = "Contacto criado com sucesso";
        $data = array($msg, $contacto);
        return response()->json($data, 200);
    }

    public function update(Request $request, $id) {
        $contacto = Contacto::find($id);

        $contacto["Telefone"] = $request["telefone"];
        $contacto["Email"] = $request["email"];
        $contacto->save();

        $msg = "Contacto atualizado com sucesso";
        return response()->json($msg, 200);
    }

    public function getContactos() {
        $per_page = empty(request('per_page')) ? 10 : (int)request('per_page');
        $contactos = Contacto::paginate($per_page);
        return ContactoResource::collection($contactos);
    }
}
