@extends('layouts.admin')

@section('admin-content')
    <!-- Content -->
    <div class="content fade-in">
        <div class="form-container">
            <form method="POST" action="{{ route('category.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label class="form-label">Tên thể loại *</label>
                    <input type="text" name="theloai_ct" class="form-input" placeholder="Nhập tên thể loại" value="{{ old('theloai_ct') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Mô tả</label>
                    <textarea name="mota_ct" class="form-textarea" placeholder="Mô tả về thể loại game này...">{{ old('mota_ct') }}</textarea>
                </div>

                <div style="display: flex; gap: 12px; justify-content: flex-end;">
                    <a href="{{ route('category.index') }}" class="btn btn-secondary">Hủy</a>
                    <button type="submit" class="btn btn-primary">Thêm thể loại</button>
                </div>
            </form>
        </div>
    </div>
@endsection
