<?php

namespace App\Http\Controllers;

use App\AcademiaDeVerao;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\AcademiaDeVerao as AcademiaDeVeraoResource;

class AcademiaControllerAPI extends Controller {
    public function store(Request $request) {
        $valid = validator($request->only('data'), [
            'data'=> 'required|date_format:Y-m-d',
        ]);

        if ($valid->fails()) {
            $jsonError=response()->json($valid->errors()->all(), 400);
            return response()->json($jsonError);
        }

        $arrayWhere = [];
        $start = Carbon::now()->format('Y')."-01-01";
        $end = Carbon::now()->format('Y')."-12-31";
        
        array_push($arrayWhere, ['data', '>=', $start]);
        array_push($arrayWhere, ['data', '<=', $end]);
        $fail = AcademiaDeVerao::where($arrayWhere)->count();
        if($fail > 0) {
            $msg = "Já existe uma academia de verão marcada para este ano";
            return response()->json($msg, 202);
        }

        $data = $request->only('data');

        $academia = AcademiaDeVerao::create($data);
        $msg = "Academia de verão criada com sucesso";
        $data = array($msg, $academia);
        return response()->json($data, 200);
    }

    public function update(Request $request, $id) {
        $valid = validator($request->only('data'), [
            'data'=> 'required|date_format:Y-m-d',
        ]);

        if ($valid->fails()) {
            $jsonError=response()->json($valid->errors()->all(), 400);
            return response()->json($jsonError);
        }

        $academia = AcademiaDeVerao::findOrFail($id);
        $academia["data"] = $request["data"];
        $academia->save();
        
        $msg = "Academia de verão atualizada com sucesso";
        return response()->json($msg, 200);
    }

    public function remove($id){
        $academia = AcademiaDeVerao::findOrFail($id);
        $academia->delete();
        $msg = 'Academia de verão removida com sucesso';
        return response()->json($msg, 200);
    }

    public function getAcademias() {
        $per_page = empty(request('per_page')) ? 10 : (int)request('per_page');
        $academias = AcademiaDeVerao::paginate($per_page);
        return AcademiaDeVeraoResource::collection($academias);
    }
}
