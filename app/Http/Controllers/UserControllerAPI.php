<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Resources\User as UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LoginControllerAPI;
use App\Http\Controllers\WalletControllerAPI;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

define('YOUR_SERVER_URL', env('APP_URL'));
define('CLIENT_ID', '2');
define('CLIENT_SECRET',env('PASSPORT_SECRET'));

class UserControllerAPI extends Controller {
    public function checkEmailExists(Request $request) {
        $user = User::where("email", $request->email)->first();
        if($user){
            return response()->json("Email já existe",202);
        }
        return response()->json("Email não existe",200);
    }

    public function getPhotoByEmail(Request $request){
        $user = User::where("email",$request->email)->first();
        if(!$user){
            return response()->json("Não foi possível encontrar um utilizador válido",202);
        }
        return $user->photo;
    }

    public function store(Request $request) {
        $valid = validator($request->only('name', 'email', 'password','password_confirmation','photo','type'), [
            'name'=> 'required|string|regex:"^[A-Za-z ]+$"',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:3|confirmed',
            'photo' => 'nullable',
        ]);

        if ($valid->fails()) {
            $jsonError=response()->json($valid->errors()->all(), 205);
            return response()->json($jsonError);
        }

        $user = User::where("email",$request->email)->first();
        if($user){
            return response()->json("Email já está a ser usado",202);
        }

        $data = request()->only('name', 'email', 'password');

        if($request->photo) {
            $exploded = explode(',', $request->photo);
            $decoded = base64_decode($exploded[1]);
            if(Str::contains($exploded[0], 'jpeg')){
                $extension = 'jpg';
            }else{
                $extension = 'png';
            }
            $count = DB::table('users')->count();
            $id = $count + 1;
            //generates the photo file name
            $fileName=$id . "_".time().".".$extension;
            //saves the image to public/storage/fotos
            $path = public_path().'/storage/fotos/'.$fileName;
            file_put_contents($path, $decoded); //we pass the path and the decoded image
            $data['photo'] = $fileName;
        } else {
            $data['photo'] = null;
        }

        $data['type'] = $request["type"];

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'type' => $data['type'],
            'photo' => $data['photo'],
        ]);

        return response()->json("Foi criado um novo utilizador.", 200);
    }

    public function updateProfile(Request $request, $id) {
        $userModel = User::find($id);
        $user = $userModel->makeVisible(['password']);

        if($request->current_password && !Hash::check($request["current_password"], $user->password)) {
            $msg = "Passworld atual está errada";
            return response()->json($msg, 206);
        }

        $valid = validator($request->only('name', 'current_password', 'new_password', 'new_password_confirmation'), [
            'name'=> 'required|string|regex:"^[a-zA-ZÀ-ž\s]+$"',
            'new_password' => 'nullable|string|min:3|confirmed'
        ]);

        if ($valid->fails()) {
            $jsonError=response()->json($valid->errors()->all(), 205);
            return response()->json($jsonError);
        }

        if($request->photo) {
            $exploded = explode(',', $request->photo);
            $decoded = base64_decode($exploded[1]);

            if(Str::contains($exploded[0], 'jpeg')){
                $extension = 'jpg';
            } else {
                $extension = 'png';
            }

            $fileName=$id . "_".time().".".$extension;

            $path = public_path().'/storage/fotos/'.$fileName;
            file_put_contents($path, $decoded);
        } else {
            $fileName = null;
        }

        $userModel["name"] = $request["name"];
        if($request->new_password) {
            $userModel["password"] = bcrypt($request["new_password"]);
        }

        if($fileName != null) {
            $userModel["photo"] = $fileName;
        }
        $userModel->save();

        return response()->json("Utilizador atualizado com sucesso", 200);
    }

    public function remove($id){
        /**
        * MUST IMPLEMMENT MIDDLEWARE, ONLY ACCESSIBLE BY ADMINISTRATORS [se nao der é so por mais um if, mas dá]
        * Confirmar também se posso fazer Auth::user ou tenho de passar quando chamo a rota no $request
        * Pode faltar alguma confirmação
        */

        if($id == Auth::user()->id){
            return response()->json("Não pode romover a si mesmo",202);
        }

        $user = User::find($id);
        $authUserId = request('authUser');

        if($id == $authUserId){
            $msg = "Não pode romover a si mesmo";
            return response()->json($msg, 205);
        }

        $user->delete();

        $typeStr = $user->type=='a'?'Administrator':'Gestor';
        $msg = `$typeStr removido com sucesso`;
        return response()->json($msg, 200);
    }
}
