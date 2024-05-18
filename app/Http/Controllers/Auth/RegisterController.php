<?php

namespace App\Http\Controllers\Auth;

use App\Models\File;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\User\UserRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Http\Requests\User\UserRegisterRequest;

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
        $user->assignRole('client');

        $file = new File(['route' => '/storage/images/users/default.png']);
        $user->file()->save($file);
        Auth::login($user);
        return redirect($this->redirectPath());
    }
}
