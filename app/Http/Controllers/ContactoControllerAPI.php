<?php

namespace App\Http\Controllers;

use App\Contacto;
use App\ContactosEscolas;
use App\ContactosEfetuados;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Contacto as ContactoResource;
use App\Http\Resources\ContactosEscolas as ContactosEscolasResource;
use App\Http\Resources\ContactosEfetuados as ContactosEfetuadosResource;

class ContactoControllerAPI extends Controller {
    public function store(Request $request) {
        $valid = validator($request->only('nome', 'telefone', 'email', 'sexo'), [
            'nome'=> 'required|string|max:255',
            'telefone' => ['nullable','string','max:9','min:9','regex:/^[0-9]*$/'],
            'email' => 'nullable|string|email|max:255',
            'sexo' => ['required', 'regex:/^[masculino]{9}$|^[feminino]{8}$|^[outro]{5}$/'],
        ]);

        if ($valid->fails()) {
            $jsonError=response()->json($valid->errors()->all(), 400);
            return response()->json($jsonError);
        }
        $data = $request->only('nome', 'sexo');

        if(!$request->only('telefone')){
            $data['telefone'] = null;
        } else {
            $data['telefone'] = $request->telefone;
        }

        if(!$request->only('email')){
            $data['email'] = null;
        } else {
            $data['email'] = $request->email;
        }

        $contacto = Contacto::create($data);
        $msg = "Contacto criado com sucesso";
        $data = array($msg, $contacto);
        return response()->json($data, 200);
    }

    public function associarEscola(Request $request) {
        $valid = validator($request->only('contacto', 'tipo', 'escola', 'descricao'), [
            'contacto'=> 'required|integer',
            'tipo' => ['required', 'regex:/^[Telefone]{8}$|^[Email]{5}$/'],
            'escola' => 'required|integer',
            'descricao' => 'nullable',
        ]);

        if ($valid->fails()) {
            $jsonError=response()->json($valid->errors()->all(), 400);
            return response()->json($jsonError);
        }
        $data = $request->only('contacto', 'tipo', 'escola');

        if(!$request->only('descricao')){
            $data['descricao'] = null;
        } else {
            $data['descricao'] = $request->descricao;
        }

        $contactoEscola = ContactosEscolas::create($data);
        $msg = "Escola associada com sucesso";
        $data = array($msg, $contactoEscola);
        return response()->json($data, 200);
    }

    public function update(Request $request, $id) {
        $valid = validator($request->only('telefone', 'email'), [
            'telefone' => ['required','string','max:9','min:9','regex:/^[0-9]*$/'],
            'email' => 'required|string|email|max:255',
        ]);

        if ($valid->fails()) {
            $jsonError=response()->json($valid->errors()->all(), 400);
            return response()->json($jsonError);
        }

        $contacto = Contacto::find($id);
        $contacto["telefone"] = $request["telefone"];
        $contacto["email"] = $request["email"];
        $contacto->save();
        
        $msg = "Contacto atualizado com sucesso";
        return response()->json($msg, 200);
    }

    public function remove($id){
        $contacto = Contacto::find($id);
        $contacto->delete();
        $msg = 'Contacto removido com sucesso';
        return response()->json($msg, 200);
    }

    public function getContactos() {
        $per_page = empty(request('per_page')) ? 10 : (int)request('per_page');
        $contactos = Contacto::paginate($per_page);
        return ContactoResource::collection($contactos);
    }
    
    public function getContactosEscolas($id) {
        $per_page = empty(request('per_page')) ? 10 : (int)request('per_page');
        $contactos = ContactosEscolas::where('contacto', $id)->paginate($per_page);
        return ContactosEscolasResource::collection($contactos);
    }

    public function getContactosEfetuados($id) {
        $per_page = empty(request('per_page')) ? 10 : (int)request('per_page');
        $contactos = ContactosEfetuados::where('contacto', $id)->paginate($per_page);
        return ContactosEfetuadosResource::collection($contactos);
    }
}
