@extends('layouts.app')

@section('content')
    <main class="main">
        <div class="container">
            <!-- Profile Section -->
            <div class="profile-container">
                <h1 class="page-title">Hồ sơ của tôi</h1>
                <form class="profile-form" action="{{ route('guest.account.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                <div class="profile-content">
                    <div class="profile-avatar-section">
                        <div class="avatar-container">
                            <img id="profileAvatar" src="{{ auth()->user()->avatar ? asset(auth()->user()->avatar) : asset('images/default-avatar.png') }}" alt="Avatar" class="profile-avatar">
                            <label for="avatarInput" class="avatar-change-btn">Đổi ảnh đại diện</label>
                            <input type="file" id="avatarInput" name="avatar" accept="image/*" style="display: none;" onchange="previewAvatar(event)">
                        </div>
                    </div>
                    
                    <div class="profile-form-section">
                            <div class="form-group">
                                <label for="profileName">Tên hiển thị</label>
                                <input type="text" id="profileName" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                                @error('name')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="profileEmail">Email</label>
                                <input type="email" id="profileEmail" name="email" value="{{ old('email', auth()->user()->email) }}" readonly required>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                                <button type="reset" class="btn btn-secondary">Hủy</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        function previewAvatar(event) {
            const input = event.target;
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profileAvatar').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection