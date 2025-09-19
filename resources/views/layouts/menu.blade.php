<!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <span>GameStore Admin</span>
                </div>
            </div>
            <nav class="sidebar-menu">
                <div class="menu-group">
                    <div class="menu-title">Dashboard</div>
                    <a href="{{ route('admin.home') }}" class="menu-item"><i class="fa-solid fa-house"></i> Trang chủ</a>
                    <a href="{{ route('admin.account') }}" class="menu-item"><i class="fa-solid fa-user"></i> Hồ sơ</a>
                </div>
                <div class="menu-group">
                    <div class="menu-title">Quản lý</div>
                    <a href="{{ route('admin.index') }}" class="menu-item"><i class="fa-solid fa-gamepad"></i> Quản lý Game</a>
                    <a href="{{ route('category.index') }}" class="menu-item"><i class="fa-solid fa-folder"></i> Quản lý Thể loại</a>
                    <a href="{{ route('orders.index') }}" class="menu-item"><i class="fa-solid fa-boxes-stacked"></i> Quản lý Đơn hàng</a>
                    <a href="{{ route('admin.users.index') }}" class="menu-item"><i class="fa-solid fa-users"></i> Quản lý Người dùng</a>
                </div>
                <div class="menu-group">
                    <div class="menu-title">Thêm mới</div>
                    <a href="{{ route('admin.create') }}" class="menu-item"><i class="fa-solid fa-plus"></i> Thêm Game</a>
                    <a href="{{ route('category.create') }}" class="menu-item"><i class="fa-solid fa-plus"></i> Thêm Thể loại</a>
                </div>
                <div class="menu-group">
                    <div class="menu-title">Khác</div>
                    <a href="{{ route('guest.index') }}" class="menu-item"><i class="fa-solid fa-right-from-bracket"></i> Trang người dùng</a>
                    <a href="{{ route('logout') }}" class="menu-item"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a>
                </div>
            </nav>
        </aside>