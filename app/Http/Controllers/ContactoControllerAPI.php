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

    public function marcarEfetuado(Request $request, $contacto) {
        $valid = validator($request->only('tipo','descricao'), [
            'tipo' => ['required', 'regex:/^[Telefone]{8}$|^[Email]{5}$/'],
            'descricao' => 'nullable',
        ]);

        if ($valid->fails()) {
            $jsonError=response()->json($valid->errors()->all(), 400);
            return response()->json($jsonError);
        }
        $data = $request->only('contacto', 'tipo');

        if(!$request->only('descricao')){
            $data['descricao'] = null;
        } else {
            $data['descricao'] = $request->descricao;
        }

        $data['contacto'] = $contacto;
        $data['data'] = Carbon::now()->toDateTimeString();

        $contactosEfetuado = ContactosEfetuados::create($data);
        $msg = "Contacto marcado como efetuado com sucesso";
        $data = array($msg, $contactosEfetuado);
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

        $contacto = Contacto::findOrFail($id);
        $contacto["telefone"] = $request["telefone"];
        $contacto["email"] = $request["email"];
        $contacto->save();
        
        $msg = "Contacto atualizado com sucesso";
        return response()->json($msg, 200);
    }

    public function updateAssociado(Request $request, $id) {
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

        $contactoEscola = ContactosEscolas::findOrFail($id);
        $contactoEscola["contacto"] = $request["contacto"];
        $contactoEscola["tipo"] = $request["tipo"];
        $contactoEscola["escola"] = $request["escola"];
        $contactoEscola["descricao"] = $request["descricao"];
        $contactoEscola->save();
        
        $msg = "Contacto Associado atualizado com sucesso";
        return response()->json($msg, 200);
    }

    public function updateEfetuado(Request $request, $contacto, $id) {
        $valid = validator($request->only('tipo','descricao'), [
            'tipo' => ['required', 'regex:/^[Telefone]{8}$|^[Email]{5}$/'],
            'descricao' => 'nullable',
        ]);

        if ($valid->fails()) {
            $jsonError=response()->json($valid->errors()->all(), 400);
            return response()->json($jsonError);
        }

        $contactosEfetuado = ContactosEfetuados::findOrFail($id);
        $date = Carbon::now()->toDateTimeString();

        $contactosEfetuado["contacto"] = $contacto;
        $contactosEfetuado["tipo"] = $request["tipo"];
        $contactosEfetuado["data"] = $date;
        $contactosEfetuado["descricao"] = $request["descricao"];
        $contactosEfetuado->save();

        $msg = "Contacto efetuado atualizado com sucesso";
        $data = array($msg, $contactosEfetuado);
        return response()->json($data, 200);
    }

    public function remove($id){
        $contacto = Contacto::findOrFail($id);
        $contacto->delete();
        $msg = 'Contacto removido com sucesso';
        return response()->json($msg, 200);
    }

    public function removeAssociado($id){
        $contactosEfetuados = ContactosEfetuados::where('contacto', $id);
        $contactosEfetuados->delete();
        $contactoEscola = ContactosEscolas::findOrFail($id);
        $contactoEscola->delete();
        $msg = 'Contacto desassociado com sucesso';
        return response()->json($msg, 200);
    }

    public function removeEfetuado($aux, $id){
        $contactoEscola = ContactosEscolas::findOrFail($aux);        
        $contactoEfetuado = ContactosEfetuados::findOrFail($id);
        $contactoEfetuado->delete();
        $msg = 'Contacto desmarcado com sucesso';
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
