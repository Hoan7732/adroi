@extends('layouts.pla')

@section('pla')

    <main class="auth-container">
        <div class="auth-box">
            <h1>Login</h1>
            <form id="loginForm" class="auth-form" action="{{ route('postLogin') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="auth-btn">Login</button>
            </form>
            <p class="auth-link">Don't have an account? <a href="{{ route('register') }}">Register</a> | <a href="{{ route('forgot') }}">Forgot Pass</a></p>
            @if ($errors->any())
                <div class="error">
                    <strong style="color: red;">Có lỗi xảy ra:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </main>

@endsection