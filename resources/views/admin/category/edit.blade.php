@extends('layouts.admin')

@section('admin-content')
    <!-- Content -->
    <div class="content fade-in">
        <div class="form-container">
            <form method="POST" action="{{ route('category.update', $category->id_ct) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label">Tên thể loại *</label>
                    <input type="text" name="theloai_ct" class="form-input" value="{{ old('theloai_ct', $category->theloai_ct) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Mô tả</label>
                    <textarea name="mota_ct" class="form-textarea">{{ old('mota_ct', $category->mota_ct) }}</textarea>
                </div>

                <div style="display: flex; gap: 12px; justify-content: flex-end;">
                    <a href="{{ route('category.index') }}" class="btn btn-secondary">Hủy</a>
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
@endsection
