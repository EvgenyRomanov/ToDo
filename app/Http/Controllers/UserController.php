<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthUserRequest;
use App\Http\Requests\RegisterUserRequest;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class UserController
{
    public function home(AuthManager $authManager): View|string
    {
        return Cache::remember("home:{$authManager->id()}", 60, function () {
            return view('home')->render();
        });
    }

    public function registerDisplay(): View
    {
        return view('register');
    }

    public function register(RegisterUserRequest $request, AuthManager $authManager): RedirectResponse
    {
        $data = $request->toArray();
        $data['password'] = bcrypt($data['password']);
        /** @var User $newUser */
        $newUser = User::query()->create($data);
        $authManager->login($newUser);

        return redirect('/tasks')->with('message', trans('flush.user_created'));
    }

    public function login(AuthManager $authManager): View|RedirectResponse
    {
        if ($authManager->check()) {
            return redirect('/tasks')->with('message',  trans('flush.already_Logged'));
        }

        return view('login');
    }

    public function logout(Request $request, AuthManager $authManager): RedirectResponse
    {
        $authManager->logout();
        $request->session()->invalidate();
        $request->session()->regenerate();

        return redirect('/')->with('message',  trans('flush.logout'));
    }

    public function authenticate(AuthUserRequest $request, AuthManager $authManager): RedirectResponse
    {
        $credentials = [
            'email' => $request->get('email'),
            'password' => $request->get('password'),
        ];
        $remember =  $request->get('remember', false);

        if ($authManager->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect('/tasks')->with('message', trans('flush.login'));
        }

        return back()->withErrors([
            'email' => trans('validation.provided_credentials'),
        ])->onlyInput('email');
    }
}
