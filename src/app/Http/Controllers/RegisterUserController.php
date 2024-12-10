<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\RegisterRequest;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Fortify\Fortify;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;


class RegisterUserController extends Controller
{
    public function store(
        RegisterRequest $request,
        CreatesNewUsers $creator
    ) {
        if (config('fortify.lowercase_usernames')) {
            $request->merge([
                Fortify::username() => Str::lower($request->{Fortify::username()}),
            ]);
        }
        $user = $creator->create($request->validated());
        event(new Registered($user));

        return redirect()->route('thanks');
    }

    public function login(LoginRequest $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();

            if ($user->email_verified_at === null) {
                return redirect()->route('verification.notice');
            }
            $role = $user->role; // ユーザーの役割を取得

            switch ($role) {
                case '管理者':
                    return redirect()->route('admin.index');
                case '店舗代表者':
                    return redirect()->route('manager.index');
                case 'ユーザー':
                default:
                    return redirect()->route('home');
            }
        }

        return back()->with('error', 'このメールアドレスは登録されていません。');
    }

}
