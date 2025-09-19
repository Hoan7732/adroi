@extends('layouts.pla')

@section('pla')

<main class="auth-container">
        <div class="auth-box">
            <h1>Quên mật khẩu</h1>
            <form id="registerForm" class="auth-form" action="{{ route('postForgot') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required>
                </div>

                <button type="submit" class="auth-btn">Send</button>
            </form>
            <p class="auth-link">Already have an account? <a href="{{ route('login') }}">Login</a></p>
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