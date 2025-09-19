@extends('layouts.admin')

@section('admin-content')
<div class="content fade-in">

    <!-- Content -->
    <div class="form-container">
        <div class="form-tabs">
            <button class="tab-button active">Thông tin cá nhân</button>
            <button class="tab-button">Đổi mật khẩu</button>
        </div>

        <!-- Tab 1: Profile Info -->
        <div class="tab-content active">
            <form action="{{ route('admin.account.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label">Ảnh đại diện</label>
                    <div class="file-upload">
                        <input type="file" id="avatar" name="avatar" accept="image/*" style="display:none;">
                        <label for="avatar" style="cursor: pointer;">
                            <div style="font-size: 32px; margin-bottom: 8px;"><i class="fa-solid fa-image"></i></div>
                            <p>Click để chọn ảnh hoặc kéo thả vào đây</p>
                            <p style="color: var(--text-light); font-size: 12px;">PNG, JPG tối đa 5MB</p>
                        </label>
                        @if(auth()->user()->avatar)
                            <img src="{{ asset(auth()->user()->avatar) }}" 
                                 alt="Avatar" class="current-avatar">
                        @else
                            <img src="{{ asset('images/default-avatar.png') }}" 
                                 alt="Avatar" class="current-avatar">
                        @endif
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label class="form-label">Họ và tên</label>
                        <input type="text" name="name" class="form-input" value="{{ auth()->user()->name }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-input" value="{{ auth()->user()->email }}" readonly>
                    </div>
                </div>

                <div style="display: flex; gap: 12px; justify-content: flex-end;">
                    <a href="{{ route('admin.home') }}" class="btn btn-secondary">Hủy</a>
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </div>
            </form>
        </div>

        <!-- Tab 2: Change Password -->
        <div class="tab-content">
            <form action="{{ route('admin.account.changePassword') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label">Mật khẩu hiện tại</label>
                    <input type="password" name="current_password" class="form-input" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Mật khẩu mới</label>
                    <input type="password" name="new_password" class="form-input" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Xác nhận mật khẩu mới</label>
                    <input type="password" name="new_password_confirmation" class="form-input" required>
                </div>

                <div style="padding: 16px; background: rgb(59 130 246 / 0.05); border: 1px solid rgb(59 130 246 / 0.2); border-radius: 8px; margin-bottom: 20px;">
                    <h4 style="margin-bottom: 8px; color: var(--primary);">Yêu cầu mật khẩu:</h4>
                    <ul style="color: var(--text-secondary); font-size: 13px; line-height: 1.6;">
                        <li>Ít nhất 8 ký tự</li>
                        <li>Có chữ thường</li>
                        <li>Có ít nhất 1 số</li>
                    </ul>
                </div>

                <div style="display: flex; gap: 12px; justify-content: flex-end;">
                    <button type="reset" class="btn btn-secondary">Hủy</button>
                    <button type="submit" class="btn btn-primary">Đổi mật khẩu</button>
                </div>
            </form>
        </div>

        <!-- Hiển thị lỗi -->
        @if($errors->any())
            <div class="error mt-4">
                <strong style="color: red;">Có lỗi xảy ra:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Hiển thị thông báo -->
        @if(session('success'))
            <div class="success mt-4" style="color: green;">
                {{ session('success') }}
            </div>
        @endif
    </div>
</div>
@endsection
