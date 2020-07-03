<?php

namespace App\Http\Controllers;

use App\Curso;
use App\TipoCurso;
use Illuminate\Http\Request;
use App\Http\Resources\Curso as CursoResource;
use App\Http\Resources\TipoCurso as TipoCursoResource;

class CursosControllerAPI extends Controller {
    public function getCursosM() {
        $cursos = Curso::all();
        return CursoResource::collection($cursos);
    }

    public function getFotoCursoM(Request $request, $nome) {
        return response()->download(public_path().'/storage/logoCursos/'.$nome, 'Imagem curso');
    }

    public function getCursosWeb() {
        $per_page = empty(request('per_page')) ? 10 : (int)request('per_page');
        $cursos = Curso::paginate($per_page);
        return CursoResource::collection($cursos);
    }

    public function getTipoCursos() {
        $per_page = empty(request('per_page')) ? 10 : (int)request('per_page');
        $cursos = TipoCurso::paginate($per_page);
        return TipoCursoResource::collection($cursos);
    }

    public function store(Request $request) {
        $valid = validator($request->only('abreviatura', 'nome', 'tipo', 'semestres', 'ECTS', 'vagas', 'contato', 'objetivos'), [
            'abreviatura'=> 'required|string',
            'nome' => 'required|string',
            'tipo' => 'required|integer',
            'semestres' => 'required|integer',
            'ECTS' => 'required|integer',
            'vagas' => 'required|integer',
            'contato' => 'required|email',
            'objetivos' => 'required|string'
        ]);

        if ($valid->fails()) {
            $jsonError=response()->json($valid->errors()->all(), 205);
            return response()->json($jsonError);
        }

        $data = request()->only('abreviatura', 'nome', 'tipo', 'semestres', 'ECTS', 'vagas', 'contato', 'objetivos');

        if($request->fotografia) {
            $exploded = explode(',', $request->fotografia);
            $decoded = base64_decode($exploded[1]);
            if(Str::contains($exploded[0], 'jpeg')){
                $extension = 'jpg';
            }else{
                $extension = 'png';
            }
            $count = DB::table('cursos')->count();
            $id = $count + 1;
            //generates the photo file name
            $fileName=$data['abreviatura'].".".$extension;
            //saves the image to public/storage/fotos
            $path = public_path().'/storage/logoCursos/'.$fileName;
            file_put_contents($path, $decoded); //we pass the path and the decoded image
            $data['fotografia'] = $fileName;
        } else {
            $data['fotografia'] = null;
        }
        
        $curso = Curso::create($data);        
        return response()->json("Foi criado um novo curso.", 200);
    }

    public function update(Request $request, $id) {
        $cursoModel = Curso::find($id);

        $valid = validator($request->only('abreviatura', 'nome', 'tipo', 'semestres', 'ECTS', 'vagas', 'contato', 'objetivos'), [
            'abreviatura'=> 'required|string',
            'nome' => 'required|string',
            'tipo' => 'required|integer',
            'semestres' => 'required|integer',
            'ECTS' => 'required|integer',
            'vagas' => 'required|integer',
            'contato' => 'required|email',
            'objetivos' => 'required|string'
        ]);

        if ($valid->fails()) {
            $jsonError=response()->json($valid->errors()->all(), 205);
            return response()->json($jsonError);
        }

        if($request->fotografia) {
            $exploded = explode(',', $request->fotografia);
            $decoded = base64_decode($exploded[1]);

            if(Str::contains($exploded[0], 'jpeg')){
                $extension = 'jpg';
            } else {
                $extension = 'png';
            }

            $fileName=$id . "_".time().".".$extension;

            $path = public_path().'/storage/logoCursos/'.$fileName;
            file_put_contents($path, $decoded);
        } else {
            $fileName = null;
        }

        if($fileName != null) {
            $cursoModel["fotografia"] = $fileName;
        }

        $cursoModel['abreviatura'] = $request['abreviatura'];
        $cursoModel['nome'] = $request['nome'];
        $cursoModel['tipo'] = $request['tipo'];
        $cursoModel['semestres'] = $request['semestres'];
        $cursoModel['ECTS'] = $request['ECTS'];
        $cursoModel['vagas'] = $request['vagas'];
        $cursoModel['contato'] = $request['contato'];
        $cursoModel['objetivos'] = $request['objetivos'];

        $cursoModel->save();

        return response()->json("Curso atualizado com sucesso", 200);
    }

    public function remove($id) {
        $curso = Curso::find($id);

        $curso->delete();

        $msg = `Curso removido com sucesso`;
        return response()->json($msg, 200);
    }
}