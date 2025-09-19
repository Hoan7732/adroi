@extends('layouts.app')

@section('content')

    <main class="main">
        <!-- Banner Section -->
        <section class="banner">
            <div class="banner-content">
                <h1 class="banner-title">Khám phá thế giới game tuyệt vời</h1>
                <p class="banner-subtitle">Hàng nghìn game chất lượng cao đang chờ bạn</p>
                <a href="#all-games" class="cta-btn">Khám phá ngay</a>
            </div>
            <div class="banner-image">
                <img src="{{ asset('images/gaming-banner.jpg') }}" alt="Gaming Banner">
            </div>
        </section>

        <!-- Recommended Games -->
        <section class="games-section">
            <div class="container">
                    @if(session('success'))
                        <div class="success" style="color: green;">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="error" style="color: red;">{{ session('error') }}</div>
                    @endif
                <h2 class="section-title">Game đề xuất</h2>
                <div class="games-grid" id="recommendedGames">
                    @foreach ($random as $rd)
                        <div class="game-card" onclick="viewGameDetail({{ $rd->id }})">
                            <img src="{{ asset('images/' . $rd->anh) }}" alt="{{ $rd->name }}" class="game-image">
                            <div class="game-info">
                                <h3 class="game-title">{{ $rd->name }}</h3>
                                <span class="game-genre">{{ $rd->category }}</span>
                                <div class="game-price"> 
                                    @if($rd->gia == 0)
                                        <span class="current-price">Free</span>
                                    @else
                                        <span class="current-price">{{ number_format($rd->gia, 0, ',', '.') }} VNĐ</span> 
                                        @if($rd->old_price) 
                                            <span class="old-price">{{ number_format($rd->old_price, 0, ',', '.') }} VNĐ</span> 
                                            <span class="discount">{{ round((($rd->old_price - $rd->gia) / $rd->old_price) * 100) }}%</span> 
                                        @endif 
                                    @endif
                                </div>
                                <div class="game-actions">
                                    <form action="{{ route('cart.add') }}" method="POST" style="display: inline;">
                                        @csrf
                                        <input type="hidden" name="game_id" value="{{ $rd->id }}">
                                        <button type="submit" class="btn btn-primary btn-small" onclick="event.stopPropagation();">Thêm vào giỏ</button>
                                    </form>
                                    <form action="{{ route('wishlist.add') }}" method="POST" style="display:inline;" onclick="event.stopPropagation();">
                                        @csrf
                                        <input type="hidden" name="game_id" value="{{ $rd->id }}">
                                        <button type="submit" class="btn btn-secondary btn-small btn-item"><i class="fa-solid fa-heart"></i></button>
                                    </form>                                    
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- New Games -->
        <section class="games-section">
            <div class="container">
                <h2 class="section-title">Game mới ra</h2>
                <div class="games-grid" id="newGames">
                    @foreach ($moi as $moi)
                        <div class="game-card" onclick="viewGameDetail({{ $moi->id }})">
                            <img src="{{ asset('images/' . $moi->anh) }}" alt="{{ $moi->name }}" class="game-image">
                            <div class="game-info">
                                <h3 class="game-title">{{ $moi->name }}</h3>
                                <span class="game-genre">{{ $moi->category }}</span>
                                <div class="game-price"> 
                                        @if($moi->gia == 0)
                                            <span class="current-price">Free</span>
                                        @else
                                            <span class="current-price">{{ number_format($moi->gia, 0, ',', '.') }} VNĐ</span> 
                                            @if($moi->old_price) 
                                                <span class="old-price">{{ number_format($moi->old_price, 0, ',', '.') }} VNĐ</span> 
                                                <span class="discount">{{ round((($moi->old_price - $moi->gia) / $moi->old_price) * 100) }}%</span> 
                                            @endif 
                                        @endif
                                    </div>
                                <div class="game-actions">
                                    <form action="{{ route('cart.add') }}" method="POST" style="display: inline;">
                                        @csrf
                                        <input type="hidden" name="game_id" value="{{ $moi->id }}">
                                        <button type="submit" class="btn btn-primary btn-small" onclick="event.stopPropagation();">Thêm vào giỏ</button>
                                    </form>
                                    <form action="{{ route('wishlist.add') }}" method="POST" style="display:inline;" onclick="event.stopPropagation();">
                                        @csrf
                                        <input type="hidden" name="game_id" value="{{ $moi->id }}">
                                        <button type="submit" class="btn btn-secondary btn-small btn-item"><i class="fa-solid fa-heart"></i></button>
                                    </form>    
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Filters Section -->
        <section class="filters-section">
            <div class="container">
                <div class="filters">
                    <div class="filter-group">
                        <label>Thể loại:</label>
                        <select id="genreFilter" name="category">
                            <option value="">Tất cả</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->theloai_ct }}" {{ request('category') == $category->theloai_ct ? 'selected' : '' }}>
                                    {{ $category->theloai_ct }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Giá:</label>
                        <select id="priceFilter" name="price_range">
                            <option value="">Tất cả</option>
                            <option value="0-100000" {{ request('price_range') == '0-100000' ? 'selected' : '' }}>Dưới 100.000đ</option>
                            <option value="100000-300000" {{ request('price_range') == '100000-300000' ? 'selected' : '' }}>100.000đ - 300.000đ</option>
                            <option value="300000-500000" {{ request('price_range') == '300000-500000' ? 'selected' : '' }}>300.000đ - 500.000đ</option>
                            <option value="500000-1000000" {{ request('price_range') == '500000-1000000' ? 'selected' : '' }}>500.000đ - 1.000.000đ</option>
                            <option value="1000000+" {{ request('price_range') == '1000000+' ? 'selected' : '' }}>Trên 1.000.000đ</option>
                        </select>
                    </div>
                    <button class="filter-clear" onclick="clearFilters()">Xóa bộ lọc</button>
                </div>
            </div>
        </section>

        <!-- All Games -->
        <section class="games-section" id="all-games">
            <div class="container">
                <h2 class="section-title">Tất cả game</h2>
                <div class="games-grid" id="allGames">
                    @if ($product->isEmpty())
                        <p>Không có sản phẩm nào trong thể loại này.</p>
                    @else
                        @foreach ($product as $pr)
                            <div class="game-card" onclick="viewGameDetail({{ $pr->id }})">
                                <img src="{{ asset('images/' . $pr->anh) }}" alt="{{ $pr->name }}" class="game-image">
                                <div class="game-info">
                                    <h3 class="game-title">{{ $pr->name }}</h3>
                                    <span class="game-genre">{{ $pr->category }}</span>
                                    <div class="game-price"> 
                                        @if($pr->gia == 0)
                                            <span class="current-price">Free</span>
                                        @else
                                            <span class="current-price">{{ number_format($pr->gia, 0, ',', '.') }} VNĐ</span> 
                                            @if($pr->old_price) 
                                                <span class="old-price">{{ number_format($pr->old_price, 0, ',', '.') }} VNĐ</span> 
                                                <span class="discount">{{ round((($pr->old_price - $pr->gia) / $pr->old_price) * 100) }}%</span> 
                                            @endif 
                                        @endif
                                    </div>
                                    <div class="game-actions">
                                        <form action="{{ route('cart.add') }}" method="POST" style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="game_id" value="{{ $pr->id }}">
                                            <button type="submit" class="btn btn-primary btn-small" onclick="event.stopPropagation();">Thêm vào giỏ</button>
                                        </form>
                                        <form action="{{ route('wishlist.add') }}" method="POST" style="display:inline;" onclick="event.stopPropagation();">
                                            @csrf
                                            <input type="hidden" name="game_id" value="{{ $pr->id }}">
                                            <button type="submit" class="btn btn-secondary btn-small btn-item"><i class="fa-solid fa-heart"></i></button>
                                        </form>    
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <!-- Phân trang -->
                <div class="custom-pagination">
                    @if ($product->hasPages())
                        <ul class="pagination">
                            <li class="{{ $product->currentPage() == 1 ? 'disabled' : '' }}">
                                <a href="{{ $product->previousPageUrl() }}" class="page-link">&lt;</a>
                            </li>
                            @foreach ($product->getUrlRange(1, $product->lastPage()) as $page => $url)
                                <li class="{{ $product->currentPage() == $page ? 'active' : '' }}">
                                    <a href="{{ $url }}" class="page-link">{{ $page }}</a>
                                </li>
                            @endforeach
                            <li class="{{ $product->currentPage() == $product->lastPage() ? 'disabled' : '' }}">
                                <a href="{{ $product->nextPageUrl() }}" class="page-link">&gt;</a>
                            </li>
                        </ul>
                    @endif
                </div>
            </div>
        </section>
    </main>

    <script>
        function viewGameDetail(gameId) {
            localStorage.setItem('selectedGameId', gameId);
            window.location.href = '{{ route('guest.show', ':id') }}'.replace(':id', gameId);
        }

        function toggleWishlist(gameId) {
            let wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
            const index = wishlist.indexOf(gameId);

            if (index > -1) {
                wishlist.splice(index, 1);
                showNotification('Đã xóa khỏi danh sách yêu thích');
            } else {
                wishlist.push(gameId);
                showNotification('Đã thêm vào danh sách yêu thích');
            }

            localStorage.setItem('wishlist', JSON.stringify(wishlist));
        }

        function showNotification(message) {
            const notification = document.createElement('div');
            notification.className = 'notification';
            notification.textContent = message;
            document.body.appendChild(notification);
            setTimeout(() => notification.remove(), 3000);
        }

        function clearFilters() {
            document.getElementById('genreFilter').value = '';
            document.getElementById('priceFilter').value = '';
            window.location.href = '{{ route('guest.index') }}';
        }

        document.addEventListener('DOMContentLoaded', function() {
            const genreFilter = document.getElementById('genreFilter');
            const priceFilter = document.getElementById('priceFilter');

            function filterGames() {
                const selectedGenre = genreFilter.value;
                const selectedPriceRange = priceFilter.value;
                let url = '{{ route('guest.index') }}';

                const params = new URLSearchParams();
                if (selectedGenre) params.append('category', selectedGenre);
                if (selectedPriceRange) params.append('price_range', selectedPriceRange);

                if (selectedGenre || selectedPriceRange) {
                    window.location.href = url + '?' + params.toString();
                }
            }

            if (genreFilter) genreFilter.addEventListener('change', filterGames);
            if (priceFilter) priceFilter.addEventListener('change', filterGames);

            const searchQuery = localStorage.getItem('searchQuery');
            if (searchQuery) {
                const searchInput = document.querySelector('.search-input');
                if (searchInput) {
                    searchInput.value = searchQuery;
                }
                localStorage.removeItem('searchQuery');
            }
        });
    </script>
@endsection