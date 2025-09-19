@extends('layouts.pla')

@section('pla')

<main class="auth-container">
        <div class="auth-box">
            <h1>Register</h1>
            <form id="registerForm" class="auth-form" action="{{ route('postRegister') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password"  name="password" required>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Confirm Password</label>
                    <input type="password" name="password_confirmation" required>
                </div>
                <div class="form-group">
                    <label for="avatar">Avatar</label>
                    <input type="file" name="avatar" accept="image/*">
                </div>
                <button type="submit" class="auth-btn">Register</button>
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