<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\SignInRequest;
use App\Http\Requests\SignUpRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function signIn(SignInRequest $request)
    {
        $credentials = $request->only(["email", "password"]);

        if (Auth::attempt($credentials)) {
            $token = Auth::user()->createToken(str()->random(20))->plainTextToken;
            $data = [
                "user" => Auth::user(),
                "token" => $token
            ];
            return response($data, 200);
        } else {
            return response(["message" => "Kullanıcı bulunamadı. Lütfen girdiğiniz bilgileri kontrol ediniz."], 404);
        }
    }

    public function signUp(SignUpRequest $request)
    {
        $user = new User();
        $data = $this->prepare($request, $user->getFillable());
        $data["is_active"] = true;
        $user->fill($data);
        $user->save();

        $token = $user->createToken(str()->random(20))->plainTextToken;

        $data = [
            "user" => $user,
            "token" => $token
        ];

        return response($data, 201);
    }

    public function logout(Request $request)
    {
        $bearerToken = $request->bearerToken();
        $token = PersonalAccessToken::findToken($bearerToken);
        $token->delete();

        return response(["message" => "Çıkış yaptınız."]);
    }
}
