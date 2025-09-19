@extends('layouts.admin')

@section('admin-content')
            <!-- Content -->
            <div class="content fade-in">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Có lỗi xảy ra:</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="form-container">
                    <form method="POST" action="{{ route('admin.update', $product->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div class="form-group">
                                <label class="form-label">Tên game *</label>
                                <input type="text" name="txtname" class="form-input" 
                                       placeholder="Nhập tên game" value="{{ old('txtname', $product->name) }}" required>
                                @error('txtname')
                                    <span class="error-text">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Thể loại *</label>
                                <select name="txtcategory" class="form-select" required>
                                    <option value="">Chọn thể loại</option>
                                    @foreach ($categories as $cate)
                                        <option value="{{ $cate->theloai_ct }}" 
                                                {{ old('txtcategory', $product->category) == $cate->theloai_ct ? 'selected' : '' }}>
                                            {{ $cate->theloai_ct }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('txtcategory')
                                    <span class="error-text">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Mô tả *</label>
                            <textarea name="txtmota" class="form-textarea" 
                                      placeholder="Mô tả về game..." required>{{ old('txtmota', $product->mota) }}</textarea>
                            @error('txtmota')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div class="form-group">
                                <label class="form-label">Giá *</label>
                                <input type="text" name="txtgia" class="form-input" 
                                       placeholder="0 hoặc Free" value="{{ old('txtgia', $product->gia) }}" required>
                                @error('txtgia')
                                    <span class="error-text">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Số lượng </label>
                                <input type="number" name="txtsoluong" class="form-input" placeholder="Nhập số lượng" 
                                       min="0" value="{{ old('txtsoluong', $product->soluong) }}" required>
                                @error('txtsoluong')
                                    <span class="error-text">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Ảnh chính hiện tại</label>
                            @if($product->anh)
                                <div style="margin-bottom: 12px;">
                                    <img src="{{ asset('images/' . $product->anh) }}" 
                                         style="width: 200px; height: 120px; object-fit: cover; border-radius: 8px;">
                                </div>
                            @endif
                            <div class="file-upload">
                                <input type="file" name="txtanh" accept="image/*" style="display: none;" 
                                       onchange="document.getElementById('main-image-preview').src = window.URL.createObjectURL(this.files[0])">
                                <div onclick="this.previousElementSibling.click()">
                                    <div style="font-size: 32px; margin-bottom: 8px;">🔄</div>
                                    <p>Click để thay đổi ảnh chính</p>
                                    <p style="color: var(--text-light); font-size: 12px;">PNG, JPG tối đa 5MB</p>
                                    <img id="main-image-preview" style="display: none; max-width: 100px; margin-top: 10px;">
                                </div>
                            </div>
                            @error('txtanh')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>

                            <div class="form-group">
                                <label class="form-label">Ngày phát hành *</label>
                                <input type="date" name="txtdate" class="form-input" 
                                       value="{{ old('txtdate', $product->date) }}" required>
                                @error('txtdate')
                                    <span class="error-text">{{ $message }}</span>
                                @enderror
                            </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div class="form-group">
                                <label class="form-label">Cấu hình tối thiểu *</label>
                                <textarea name="txtcauhinhtt" class="form-textarea" 
                                          placeholder="Mô tả cấu hình tối thiểu..." required>{{ old('txtcauhinhtt', $product->cauhinhtt) }}</textarea>
                                @error('txtcauhinhtt')
                                    <span class="error-text">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Cấu hình đề xuất *</label>
                                <textarea name="txtcauhinhdx" class="form-textarea" 
                                          placeholder="Mô tả cấu hình đề xuất..." required>{{ old('txtcauhinhdx', $product->cauhinhdx) }}</textarea>
                                @error('txtcauhinhdx')
                                    <span class="error-text">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div style="display: flex; gap: 12px; justify-content: flex-end;">
                            <a href="{{ route('admin.index') }}" class="btn btn-secondary">Hủy</a>
                            <button type="submit" name="submit" class="btn btn-primary">Lưu thay đổi</button>
                        </div>
                    </form>
                </div>
            </div>

    <script src="{{ asset('js/script.js') }}"></script>
    <script>
        function previewMultipleImages(input, previewId) {
            const preview = document.getElementById(previewId);
            preview.innerHTML = '';
            for (let file of input.files) {
                const img = document.createElement('img');
                img.src = window.URL.createObjectURL(file);
                img.style.maxWidth = '100px';
                img.style.height = '60px';
                img.style.objectFit = 'cover';
                img.style.borderRadius = '8px';
                img.style.marginTop = '10px';
                preview.appendChild(img);
            }
        }
    </script>
@endsection