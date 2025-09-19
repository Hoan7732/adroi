@extends('layouts.app')

@section('content')

    <main class="main">
        <!-- Banner Section -->
        <section class="banner">
            <div class="banner-content">
                <h1 class="banner-title">Kết quả tìm kiếm</h1>
                <p class="banner-subtitle">Từ khóa: "{{ $query }}"</p>
            </div>
            <div class="banner-image">
                <img src="{{ asset('images/gaming-banner.jpg') }}" alt="Search Banner">
            </div>
        </section>

        <!-- Search Results -->
        <section class="games-section">
            <div class="container">
                <h2 class="section-title">Kết quả tìm kiếm</h2>
                    @if(session('success'))
                        <div class="success" style="color: green;">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="error" style="color: red;">{{ session('error') }}</div>
                    @endif

                @if ($games->isEmpty())
                    <p>Không tìm thấy sản phẩm nào cho từ khóa "<strong>{{ $query }}</strong>".</p>
                @else
                    <div class="games-grid">
                        @foreach ($games as $game)
                            <div class="game-card" onclick="viewGameDetail({{ $game->id }})">
                                <img src="{{ asset('images/' . $game->anh) }}" alt="{{ $game->name }}" class="game-image">
                                <div class="game-info">
                                    <h3 class="game-title">{{ $game->name }}</h3>
                                    <span class="game-genre">{{ $game->category }}</span>
                                    <div class="game-price"> 
                                        @if($game->gia == 0)
                                            <span class="current-price">Free</span>
                                        @else
                                            <span class="current-price">{{ number_format($game->gia, 0, ',', '.') }} VNĐ</span> 
                                            @if($game->old_price) 
                                                <span class="old-price">{{ number_format($game->old_price, 0, ',', '.') }} VNĐ</span> 
                                                <span class="discount">{{ round((($game->old_price - $game->gia) / $game->old_price) * 100) }}%</span> 
                                            @endif 
                                        @endif
                                    </div>
                                    <div class="game-actions">
                                        <form action="{{ route('cart.add') }}" method="POST" style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="game_id" value="{{ $game->id }}">
                                            <button type="submit" class="btn btn-primary btn-small" onclick="event.stopPropagation();">Thêm vào giỏ</button>
                                        </form>
                                        <form action="{{ route('wishlist.add') }}" method="POST" style="display:inline;" onclick="event.stopPropagation();">
                                            @csrf
                                            <input type="hidden" name="game_id" value="{{ $game->id }}">
                                            <button type="submit" class="btn btn-secondary btn-small btn-item"><i class="fa-solid fa-heart"></i></button>
                                        </form> 
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
    </main>

    <script>
        function viewGameDetail(gameId) {
            localStorage.setItem('selectedGameId', gameId);
            window.location.href = '{{ route('guest.show', ':id') }}'.replace(':id', gameId);
        }
    </script>
@endsection
