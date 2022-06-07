<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\SignInRequest;
use App\Http\Requests\SignUpRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class AuthController extends Controller
{
    public function signIn(SignInRequest $request)
    {
        $credentials = $request->only(["email", "password"]);
        $rememberMe = $request->get("remember-me", false);

        if (Auth::attempt($credentials, $rememberMe)) {
            $token = Auth::user()->createToken("ecommerce")->plainTextToken;
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

        $token = $user->createToken("ecommerce")->plainTextToken;

        $data = [
            "user" => $user,
            "token" => $token
        ];

        return response($data, 201);
    }

    public function logout()
    {
        Auth::logout();
        //request()->user()->currentAccessToken()->delete();
        return response(["message" => "Çıkış yaptınız."]);
    }


}
