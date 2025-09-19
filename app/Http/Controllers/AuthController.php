<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register()
    {
        // Logic for handling user registration
        return view('auth.register'); // Assuming you have a view for registration
    }

    public function postRegister(RegisterRequest $request)
    {
        $avatarPath = null;

    if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images'), $filename); 
            $avatarPath = 'images/' . $filename; 
        }

    User::create([
        'name' => $request->input('name'),
        'email' => $request->input('email'),
        'password' => bcrypt($request->input('password')),
        'avatar' => $avatarPath, // Ghi đường dẫn hoặc null nếu không có file
        'role' => 'user',
        'status' => 'active',
    ]);

        return redirect()->route('login');
    }
    public function login()
    {
        // Logic for showing the login form
        return view('auth.login'); // Assuming you have a view for login
    }

    public function postLogin(LoginRequest $request)
    {
        // Logic for handling user login
        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            $request->session()->regenerate();
            $user = auth()->user();
            if ($user->role === 'admin') {
                return redirect()->route('admin.home')->with('success', 'Chào mừng bạn trở lại, Admin!');
            }
            return redirect()->intended('guest')->with('success', 'Xin chào bạn trở lại, ' . $user->name . '!');
        }

        return redirect()->back()->withErrors(['email' => 'Sai thông tin đăng nhập'])->withInput();
    }

    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
