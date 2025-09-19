<header class="header">
        <nav class="nav">
            <div class="nav-left">
                <div class="logo">
                    <span class="logo-text">GameStore</span>
                </div>
            </div>

            <div class="nav-center">
                <div class="search-container">
                    <form action="{{ route('search') }}" method="GET" class="search-form">
                        <input type="text" name="query" class="search-input" placeholder="Tìm kiếm game..." aria-label="Search games">
                        <button type="submit" class="search-btn"><i class="fa-solid fa-magnifying-glass"></i></button>
                    </form>
                </div>
            </div>

            <div class="nav-right">
                <a href="{{ route('guest.index') }}" class="nav-link {{ Route::currentRouteName() == 'guest.index' ? 'active' : '' }}"><i class="fa-solid fa-house"></i></a>
                <a href="{{ route('cart.index') }}" class="nav-link">
                    <span><i class="fa-solid fa-cart-shopping"></i></span>
                    <span class="cart-count">{{ count(session('cart', [])) }}</span>
                </a>
                <a href="{{ route('wishlist.index') }}" class="nav-link">
                    <span><i class="fa-solid fa-heart"></i></span>
                    <span class="wishlist-count">{{ count(session('wishlist', [])) }}</span>
                </a>
                <a href="{{ route('guest.orders') }}" class="nav-link"><i class="fa-solid fa-boxes-stacked"></i></a>
                @auth
                    <div class="user-dropdown">
                        <img src="{{ auth()->user()->avatar ? asset(auth()->user()->avatar) : asset('images/default-avatar.png') }}" alt="Avatar" class="avatar">
                        <div class="dropdown-content">
                            @if(auth()->user()->role === 'admin')
                                <a href="{{ route('admin.home') }}">Tài khoản</a>
                            @else
                                <a href="{{ route('guest.account') }}">Tài khoản</a>
                            @endif
                            <a href="{{ route('guest.settings') }}">Cài đặt</a>
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Đăng xuất</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="nav-link">Đăng nhập</a>
                @endauth
            </div>
        </nav>
    </header>
