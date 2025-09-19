@extends('layouts.admin')

@section('admin-content')
        <!-- Content -->
        <div class="content fade-in">
            @if(session('success'))
                <p class="success-message" style="color: green;">{{ session('success') }}</p>
            @endif
            <div class="table-container">
                <div class="table-header">
                    <h2 class="table-title">Danh sách đơn hàng</h2>
                    <div class="table-actions">
                        <div class="search-box">
                            <form method="GET" action="{{ route('orders.index') }}" class="search-box">
                                <input type="text" name="search" class="search-input" placeholder="Tìm kiếm tên hoặc email khách hàng" value="{{ request('search') }}">
                                <span class="search-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                            </form>
                        </div>
                        <div class="filters">
                            <div class="filter-group">
                                <label class="filter-label">Trạng thái:</label>
                                <form method="GET" action="{{ route('orders.index') }}">
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                    <select name="status" class="form-select" onchange="this.form.submit()">
                                        <option value="">Tất cả</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Đang xử lý</option>
                                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                    </select>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Khách hàng</th>
                            <th>Email</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Ngày đặt</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $o)
                        <tr>
                            <td>{{ $o->full_name }}</td>
                            <td>{{ $o->email }}</td>
                            <td>{{ number_format($o->total_amount) }} VNĐ</td>
                            <td>
                                <span class="status 
                                    @if($o->trangthai == 'completed') status-completed
                                    @elseif($o->trangthai == 'pending') status-pending
                                    @elseif($o->trangthai == 'cancelled') status-cancelled
                                    @else status-info @endif">
                                    {{ $o->trangthai }}
                                </span>
                            </td>
                            <td>{{ $o->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('orders.show', $o->id) }}" class="btn btn-primary btn-sm"><i class="fa-solid fa-eye"></i></a>
                                <form action="{{ route('orders.destroy', $o->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-error btn-sm" onclick="return confirm('Xóa đơn hàng này?')"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" style="text-align:center;">Không có đơn hàng nào</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="pagination">
                    @if($orders->hasPages())
                        <button class="pagination-btn {{ $orders->onFirstPage() ? 'disabled' : '' }}"
                                onclick="{{ $orders->onFirstPage() ? '' : 'window.location.href=\'' . $orders->previousPageUrl() . '\'' }}">◀</button>
                        @foreach($orders->links()->elements[0] as $page => $url)
                            <button class="pagination-btn {{ $orders->currentPage() == $page ? 'active' : '' }}"
                                    onclick="window.location.href='{{ $url }}'">{{ $page }}</button>
                        @endforeach
                        <button class="pagination-btn {{ $orders->hasMorePages() ? '' : 'disabled' }}"
                                onclick="{{ $orders->hasMorePages() ? 'window.location.href=\'' . $orders->nextPageUrl() . '\'' : '' }}">▶</button>
                    @endif
                </div>
            </div>
        </div>
@endsection