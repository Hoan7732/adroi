<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ResetRequest;
use Illuminate\Support\Facades\Password;

class ResetController extends Controller
{
    public function reset($token)
    {

        return view('auth.reset', ['token' => $token]); // Assuming you have a view named 'auth.reset'
    }

    public function postReset(ResetRequest $request)
    {
        $request->validated();

        $status = Password::reset(
            $request->only('token','email', 'password','password_confirmation'),
            function ($user, $password) {
                $user->password = bcrypt($password);
                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login');
        }
        return redirect()->back()->withErrors(['email'=>__($status)]);
    }
}
