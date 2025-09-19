@extends('layouts.admin')

@section('admin-content')
    <!-- Content -->
    <div class="content fade-in">
        <!-- Session Messages -->
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Search and Filters -->
        <div class="card mb-6">
            <div class="table-actions">
                <form method="GET" action="{{ route('admin.users.index') }}" class="search-box">
                    <input type="text" name="search" class="search-input" 
                           placeholder="Tìm kiếm tên hoặc email..." value="{{ request('search') }}">
                    <span class="search-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                </form>
                <div class="filters">
                    <div class="filter-group">
                        <label class="filter-label">Quyền:</label>
                        <form method="GET" action="{{ route('admin.users.index') }}">
                            <input type="hidden" name="search" value="{{ request('search') }}">
                            <input type="hidden" name="status" value="{{ request('status') }}">
                            <select name="role" class="form-select" style="width: 120px;" onchange="this.form.submit()">
                                <option value="" {{ !request('role') ? 'selected' : '' }}>Tất cả</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                            </select>
                        </form>
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">Trạng thái:</label>
                        <form method="GET" action="{{ route('admin.users.index') }}">
                            <input type="hidden" name="search" value="{{ request('search') }}">
                            <input type="hidden" name="role" value="{{ request('role') }}">
                            <select name="status" class="form-select" style="width: 120px;" onchange="this.form.submit()">
                                <option value="" {{ !request('status') ? 'selected' : '' }}>Tất cả</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                <option value="banned" {{ request('status') == 'banned' ? 'selected' : '' }}>Bị cấm</option>
                            </select>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Cards -->
        <div class="user-grid">
            @forelse ($users as $user)
                <div class="user-card">
                    <div class="user-card-header">
                        <div class="user-avatar-large">
                            @if($user->avatar)
                                <img src="{{ asset($user->avatar) }}" alt="Avatar" class="user-current-avatar" >
                            @else
                                <img src="{{ asset('images/default-avatar.png') }}" alt="Default Avatar" class="user-current-avatar">
                            @endif
                        </div>
                        <div class="user-info">
                            <h3>{{ $user->name }}</h3>
                            <div class="user-email">{{ $user->email }}</div>
                        </div>
                    </div>
                    <div class="user-meta">
                        <div class="meta-item">Quyền: 
                            <span class="status {{ $user->role == 'admin' ? 'status-info' : 'status-success' }}">
                                {{ $user->role == 'admin' ? 'Admin' : 'User' }}
                            </span>
                        </div>
                        <div class="meta-item">Trạng thái: 
                            <span class="status {{ $user->status == 'active' ? 'status-success' : 'status-error' }}">
                                {{ $user->status == 'active' ? 'Hoạt động' : 'Bị cấm' }}
                            </span>
                        </div>
                        <div class="meta-item">Ngày tham gia: {{ $user->created_at->format('d/m/Y') }}</div>
                        <div class="meta-item">Tổng đơn hàng: {{ $user->orders_count ?? 0 }}</div>
                    </div>
                    <div class="user-actions">
                        @if ($user->role == 'user' && $user->status == 'active')
                            <form action="{{ route('admin.users.upgrade-to-admin', $user->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-primary btn-sm" 
                                        onclick="return confirm('Bạn có chắc muốn nâng cấp người dùng này thành admin?')">
                                    Nâng Admin
                                </button>
                            </form>
                            <form action="{{ route('admin.users.update-status', $user->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="banned">
                                <button type="submit" class="btn btn-error btn-sm" 
                                        onclick="return confirm('Bạn có chắc muốn cấm người dùng này?')">
                                    Cấm
                                </button>
                            </form>
                        @elseif ($user->role == 'admin')
                            <form action="{{ route('admin.users.downgrade-to-user', $user->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-secondary btn-sm" 
                                        onclick="return confirm('Bạn có chắc muốn hủy quyền admin của người dùng này?')">
                                    Hủy Admin
                                </button>
                            </form>
                            <form action="{{ route('admin.users.update-status', $user->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="banned">
                                <button type="submit" class="btn btn-error btn-sm" 
                                        onclick="return confirm('Bạn có chắc muốn cấm người dùng này?')">
                                    Cấm
                                </button>
                            </form>
                        @elseif ($user->status == 'banned')
                            <form action="{{ route('admin.users.update-status', $user->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="active">
                                <button type="submit" class="btn btn-success btn-sm" 
                                        onclick="return confirm('Bạn có chắc muốn bỏ cấm người dùng này?')">
                                    Bỏ cấm
                                </button>
                            </form>
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-error btn-sm" 
                                        onclick="return confirm('Bạn có chắc muốn xóa vĩnh viễn người dùng này?')">
                                    Xóa
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center">Không tìm thấy người dùng nào.</div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div style="margin-top: 32px;">
            <div class="pagination">
                @if($users->hasPages())
                    <button class="pagination-btn {{ $users->onFirstPage() ? 'disabled' : '' }}"
                            onclick="{{ $users->onFirstPage() ? '' : 'window.location.href=\'' . $users->previousPageUrl() . '\'' }}">◀</button>
                    @foreach($users->links()->elements[0] as $page => $url)
                        <button class="pagination-btn {{ $users->currentPage() == $page ? 'active' : '' }}"
                                onclick="window.location.href='{{ $url }}'">{{ $page }}</button>
                    @endforeach
                    <button class="pagination-btn {{ $users->hasMorePages() ? '' : 'disabled' }}"
                            onclick="{{ $users->hasMorePages() ? 'window.location.href=\'' . $users->nextPageUrl() . '\'' : '' }}">▶</button>
                @endif
            </div>
        </div>
    </div>

@endsection