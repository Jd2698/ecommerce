<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserRegisterRequest;
use App\Http\Requests\User\UserRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    // protected $redirectTo = '/home';
    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function register(UserRegisterRequest $request)
    {
        $user = new User($request->all());
        $user->save();
        $user->assignRole('user');
        Auth::login($user);
        return redirect($this->redirectPath());
    }
}
