@extends('layouts.admin')

@section('admin-content')

            <!-- Content -->
            <div class="content fade-in">
                <!-- Hiển thị thông báo -->
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="table-container">
                    <div class="table-header">
                        <h2 class="table-title">Danh sách game</h2>
                        <div class="table-actions">
                            <form method="GET" action="{{ route('admin.index') }}" class="search-box">
                                <input type="text" name="search" class="search-input" 
                                       placeholder="Tìm kiếm game..." value="{{ request('search') }}">
                                <span class="search-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                            </form>
                            <div class="filters">
                                <div class="filter-group">
                                    <label class="filter-label">Thể loại:</label>
                                    <form method="GET" action="{{ route('admin.index') }}">
                                        <input type="hidden" name="search" value="{{ request('search') }}">
                                        <select name="category" class="form-select" style="width: 150px;" onchange="this.form.submit()">
                                            <option value="">Tất cả</option>
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->theloai_ct }}" 
                                                    {{ request('category') == $cat->theloai_ct ? 'selected' : '' }}>
                                                    {{ $cat->theloai_ct }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                </div>
                            </div>
                            <a href="{{ route('admin.create') }}" class="btn btn-primary">Thêm game mới</a>
                        </div>
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <th>Ảnh</th>
                                <th>Tên game</th>
                                <th>Thể loại</th>
                                <th>Giá</th>
                                <th>Trạng thái</th>
                                <th>Ngày thêm</th>
                                <th>Số lượng</th>
                                <th>Mô tả</th>
                                <th>Cấu hình tối thiểu</th>
                                <th>Cấu hình đề xuất</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($product as $pr)
                                <tr>
                                    <td>
                                        <img src="{{ asset('images/' . $pr->anh) }}" 
                                             alt="{{ $pr->name }}" 
                                             style="width: 48px; height: 48px; object-fit: cover; border-radius: 6px;">
                                    </td>
                                    <td>{{ $pr->name }}</td>
                                    <td>{{ $pr->category }}</td>
                                    <td>
                                        @if($pr->gia == 0)
                                            Free
                                        @else
                                            {{ is_numeric($pr->gia) ? number_format($pr->gia, 0, ',', '.') . ' VNĐ' : $pr->gia }}
                                        @endif
                                    </td>
                                    <td>
                                        <span class="status {{ $pr->soluong > 0 ? 'status-success' : 'status-error' }}">
                                            {{ $pr->soluong > 0 ? 'Active' : 'Sold' }}
                                        </span>
                                    </td>
                                    <td>{{ $pr->created_at ? $pr->created_at->format('d/m/Y') : $pr->date }}</td>
                                    <td>{{ $pr->soluong }}</td>
                                    <td>{{ $pr->mota }}</td>
                                    <td>{{ $pr->cauhinhtt }}</td>
                                    <td>{{ $pr->cauhinhdx }}</td>
                                    <td>
                                        <div class="flex gap-2">
                                            <a href="{{ route('admin.show', $pr->id) }}" class="btn btn-secondary btn-sm"><i class="fa-solid fa-eye"></i></a>
                                            <a href="{{ route('admin.edit', $pr->id) }}" class="btn btn-warning btn-sm"><i class="fa-solid fa-pen-to-square"></i></a>
                                            <form action="{{ route('admin.destroy', $pr->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa game này?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-error btn-sm"><i class="fa-solid fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center">Không tìm thấy sản phẩm nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="pagination">
                        @if($product->hasPages())
                            <button class="pagination-btn {{ $product->onFirstPage() ? 'disabled' : '' }}"
                                    onclick="{{ $product->onFirstPage() ? '' : 'window.location.href=\'' . $product->previousPageUrl() . '\'' }}">◀</button>
                            @foreach($product->links()->elements[0] as $page => $url)
                                <button class="pagination-btn {{ $product->currentPage() == $page ? 'active' : '' }}"
                                        onclick="window.location.href='{{ $url }}'">{{ $page }}</button>
                            @endforeach
                            <button class="pagination-btn {{ $product->hasMorePages() ? '' : 'disabled' }}"
                                    onclick="{{ $product->hasMorePages() ? 'window.location.href=\'' . $product->nextPageUrl() . '\'' : '' }}">▶</button>
                        @endif
                    </div>
                </div>
            </div>
@endsection