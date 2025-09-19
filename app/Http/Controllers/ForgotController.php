<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ForgotRequest;
use Illuminate\Support\Facades\Password;

class ForgotController extends Controller
{
    public function forgot()
    {
        // Logic for handling forgot password functionality
        return view('auth.forgot'); // Assuming you have a view named 'auth.forgot'
    }

    public function postForgot(ForgotRequest $request)
    {
        $request->validated();

        $status = Password::sendResetLink(
            $request->only('email')
        );
        if ($status === Password::RESET_LINK_SENT) {
            return redirect()->back();
        }

        return redirect()->back()->withErrors(['email'=>__($status)]);
    }
}
