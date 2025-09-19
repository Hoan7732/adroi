@extends('layouts.app')

@section('content')
    <main class="main">
        <div class="container">

            <!-- Settings Section -->
            <div class="settings-container">
                <h1 class="page-title">Cài đặt tài khoản</h1>
                
                <div class="settings-section">
                    <h2>Đổi mật khẩu</h2>
                    <form class="settings-form" id="passwordForm" action="{{ route('guest.account.changePassword') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="currentPassword">Mật khẩu hiện tại</label>
                            <input type="password" id="currentPassword" name="current_password" required>
                            @error('current_password')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="newPassword">Mật khẩu mới</label>
                            <input type="password" id="newPassword" name="new_password" required minlength="6">
                            @error('new_password')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="confirmPassword">Xác nhận mật khẩu mới</label>
                            <input type="password" id="confirmPassword" name="new_password_confirmation" required minlength="6">
                            @error('new_password_confirmation')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Đổi mật khẩu</button>
                    </form>
                </div>
                
            </div>
        </div>
    </main>
@endsection