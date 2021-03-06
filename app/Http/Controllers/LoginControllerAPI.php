<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

define('YOUR_SERVER_URL', env('APP_URL'));
define('CLIENT_ID', '2');
define('CLIENT_SECRET',env('PASSPORT_SECRET'));

class LoginControllerAPI extends Controller {
    public function login(Request $request) {
        $http = new \GuzzleHttp\Client;
        $response = $http->post(YOUR_SERVER_URL.'/oauth/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => CLIENT_ID,
                'client_secret' => CLIENT_SECRET,
                'username' => $request->email,
                'password' => $request->password,
                'scope' => ''
            ],
            'exceptions' => false,
            ]);

        $errorCode= $response->getStatusCode();

        if ($errorCode=='200') {
            return json_decode((string) $response->getBody(), true);
        } else {
            return response()->json('Credênciais de Utilizador Inválidas', 202);
        }
    }

    public function logout() {
        \Auth::guard('api')->user()->token()->revoke();
        \Auth::guard('api')->user()->token()->delete();
        return response()->json(['msg'=>'Token revoked'], 200);
    }
}
