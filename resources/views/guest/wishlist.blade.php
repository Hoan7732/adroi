@extends('layouts.app')

@section('content')
<main class="main">
    <div class="container">
        <h1 class="page-title">Danh sách yêu thích</h1>

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if(session('info'))
            <div class="alert alert-info">{{ session('info') }}</div>
        @endif

        <div class="wishlist-actions" style="margin-bottom:16px;">
            <form action="{{ route('wishlist.clear') }}" method="POST" style="display:inline;">
                @csrf
                <button class="btn btn-secondary" type="submit" onclick="return confirm('Bạn có chắc muốn xóa tất cả?')">Xóa tất cả</button>
            </form>

            <form action="{{ route('wishlist.addAllToCart') }}" method="POST" style="display:inline; margin-left:8px;">
                @csrf
                <button class="btn btn-primary" type="submit">Thêm tất cả vào giỏ</button>
            </form>
        </div>

        @if($games->isEmpty())
            <div class="empty-state">
                <div class="empty-icon">❤️</div>
                <h3>Danh sách yêu thích trống</h3>
                <p>Thêm các game yêu thích để xem chúng tại đây</p>
                <a href="{{ route('guest.index') }}" class="btn btn-primary">Khám phá game</a>
            </div>
        @else
            <div class="games-grid">
                @foreach($games as $game)
                    <div class="game-card">
                        <img src="{{ asset('images/' . $game->anh) }}" alt="{{ $game->name }}" class="game-image">
                        <div class="game-info">
                            <h3 class="game-title">{{ $game->name }}</h3>
                            <span class="game-genre">{{ $game->category }}</span>
                            <div class="game-price">
                                @if($game->gia == 0)
                                    <span class="current-price">Free</span>
                                @else
                                    <span class="current-price">{{ number_format($game->gia, 0, ',', '.') }} VNĐ</span>
                                    @if(!empty($game->old_price))
                                        <span class="old-price">{{ number_format($game->old_price, 0, ',', '.') }} VNĐ</span>
                                        <span class="discount">{{ round((($game->old_price - $game->gia) / $game->old_price) * 100) }}%</span>
                                    @endif
                                @endif
                            </div>

                            <div class="game-actions" style="margin-top:8px;">
                                {{-- Thêm vào giỏ (gửi tới CartController hiện có) --}}
                                <form action="{{ route('cart.add') }}" method="POST" style="display:inline;">
                                    @csrf
                                    <input type="hidden" name="game_id" value="{{ $game->id }}">
                                    <button type="submit" class="btn btn-primary btn-small"><i class="fa-solid fa-cart-plus"></i></button>
                                </form>

                                {{-- Xóa khỏi wishlist --}}
                                <form action="{{ route('wishlist.remove') }}" method="POST" style="display:inline; margin-left:6px;">
                                    @csrf
                                    <input type="hidden" name="game_id" value="{{ $game->id }}">
                                    <button type="submit" class="btn btn-danger btn-small" onclick="return confirm('Xóa game khỏi danh sách yêu thích?')"><i class="fa-solid fa-trash"></i></button>
                                </form>

                                {{-- Xem chi tiết --}}
                                <a href="{{ route('guest.show', $game->id) }}" class="btn btn-secondary btn-small" style="margin-left:6px;"><i class="fa-solid fa-eye"></i></a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</main>
@endsection
