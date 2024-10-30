<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\RegisterRequest;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Fortify\Contracts\RegisterResponse;
use Laravel\Fortify\Fortify;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class UserController extends Controller
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

        return redirect()->route('login');
    }

}
