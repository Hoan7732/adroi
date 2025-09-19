@extends('layouts.admin')

@section('admin-content')
            <!-- Content -->
            <div class="content fade-in">
                @if(session('success'))
                    <p class="success-message" style="color: green;">{{ session('success') }}</p>
                @endif
                <div class="table-container">
                    <div class="table-header">
                        <h2 class="table-title">Danh sách thể loại</h2>
                        <div class="table-actions">
                            <form method="GET" action="{{ route('category.index') }}" class="search-box">
                                <input type="text" name="search" class="search-input" placeholder="Tìm kiếm thể loại..." value="{{ request('search') }}">
                                <span class="search-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                            </form>
                            <a href="{{ route('category.create') }}" class="btn btn-primary">Thêm thể loại mới</a>
                        </div>
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <th>Tên thể loại</th>
                                <th>Mô tả</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $cat)
                            <tr>
                                <td>{{ $cat->theloai_ct }}</td>
                                <td>{{ $cat->mota_ct }}</td>
                                <td>
                                    <div class="flex gap-2">
                                        <a href="{{ route('category.edit', $cat->id_ct) }}" class="btn btn-secondary btn-sm"><i class="fa-solid fa-pen-to-square"></i></a>
                                        <form action="{{ route('category.destroy', $cat->id_ct) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa thể loại này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-error btn-sm"><i class="fa-solid fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="pagination">
                        @if($categories->hasPages())
                            <button class="pagination-btn {{ $categories->onFirstPage() ? 'disabled' : '' }}" 
                                    onclick="{{ $categories->onFirstPage() ? '' : 'window.location.href=\'' . $categories->previousPageUrl() . '\'' }}">◀</button>
                            
                            @foreach($categories->links()->elements[0] as $page => $url)
                                <button class="pagination-btn {{ $categories->currentPage() == $page ? 'active' : '' }}"
                                        onclick="window.location.href='{{ $url }}'">{{ $page }}</button>
                            @endforeach
                            
                            <button class="pagination-btn {{ $categories->hasMorePages() ? '' : 'disabled' }}"
                                    onclick="{{ $categories->hasMorePages() ? 'window.location.href=\'' . $categories->nextPageUrl() . '\'' : '' }}">▶</button>
                        @endif
                    </div>
                </div>
            </div>
@endsection