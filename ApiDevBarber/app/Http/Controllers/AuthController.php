<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api', ['except' => ['create', 'login', 'unauthorized']]);
    }

    public function create(Request $req){
        $array = ['error' => ''];

        $validator = Validator::make($req->all(),
        [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if(!$validator->fails()) {
            $name = $req->input('name');
            $email = $req->input('email');
            $password = $req->input('password');

            $emailExists = User::where('email', $email)->count();

            if($emailExists === 0){
                $hash = password_hash($password, PASSWORD_DEFAULT);

                $newUser = new User();
                $newUser->name = $name;
                $newUser->email = $email;
                $newUser->password = $hash;
                $newUser->save();

                $token = auth()->attempt([
                    'email' => $email,
                    'password' => $password,
                ]);


                if(!$token) {
                   return response()->json(['error' => 'erro interno, token invalido.']);
                }

                $info = auth()->user();
                $info['avatar'] = url('media/avatars'. $info['avatar']);
                $array['data'] = $info;
                $array['token'] = $token;

                return response()->json($array, 200);

            } else {
                return response()->json(['message' => 'Email já existente'], 401);
            }
        } else {
            return response()->json(['message' => 'Dados incorretos'], 400);
        }
    }

    public function login(Request $req) {
        $array = ['error' => ''];

        $email = $req->input('email');
        $password = $req->input('password');

        $token = auth()->attempt([
            'email' => $email,
            'password' => $password,
        ]);

        if(!$token) {
            return response()->json(['error' => 'Usuario não autorizado'], 405);
        }

        $info = auth()->user();
        $info['avatar'] = url('media/avatars'. $info['avatar']);
        $array['data'] = $info;
        $array['token'] = $token;

        return response()->json($array, 200);
    }

    public function logout() {
        auth()->logout();
        return response()->json('você está deslogado!', 200);
    }

    public function refresh() {

        $token = auth()->refresh();

        $info = auth()->user();
        $info['avatar'] = url('media/avatars'. $info['avatar']);
        $array['data'] = $info;
        $array['token'] = $token;

        return response()->json($array);
    }

    public function unauthorized() {
        return response()->json(['error' => 'unauthorized'], 401);
    }
}
