@extends('layouts.pla')

@section('pla')

<main class="auth-container">
        <div class="auth-box">
            <h1>Đặt lại mật khẩu</h1>
            <form id="registerForm" class="auth-form" action="{{ route('postReset') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password"  name="password" required>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Confirm Password</label>
                    <input type="password" name="password_confirmation" required>
                </div>

                <button type="submit" class="auth-btn">Send</button>
            </form>
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