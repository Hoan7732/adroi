
@extends('layouts.app')

@section('content')
<main class="main">
    <div class="container">
        <div class="game-detail-container">
            <div class="game-hero">
                {{-- Hình ảnh chính và thumbnails --}}
                <div class="game-media">
                    <img id="gameMainImage" src="{{ asset('images/' . $product->anh) }}" 
                         alt="{{ $product->name }}" class="game-main-img">
                    <div class="game-thumbnails">
                        @if(!empty($product->thumbnails))
                            @foreach($product->thumbnails as $index => $thumb)
                                <img src="{{ asset('images/' . $thumb) }}" 
                                     alt="Screenshot {{ $index + 1 }}" 
                                     class="thumbnail {{ $index === 0 ? 'active' : '' }}" 
                                     onclick="changeMainImage(this)">
                            @endforeach
                        @else
                            <img src="{{ asset('images/' . $product->anh) }}" 
                                 alt="Screenshot" class="thumbnail active" 
                                 onclick="changeMainImage(this)">
                        @endif
                    </div>
                </div>

                {{-- Thông tin game --}}
                <div class="game-info">
                    <h1 id="gameTitle" class="game-title">{{ $product->name }}</h1>
                    <div class="game-meta">
                        <span class="game-genre" id="gameGenre">{{ $product->category }}</span>
                        <span class="game-release-date">Ngày ra mắt: {{ $product->date }}</span>
                    </div>

                    {{-- Giá và nút thêm vào giỏ --}}
                    <div class="game-price">
                        <span class="current-price">
                            @if($product->gia == 0)
                                Free
                            @else
                                {{ is_numeric($product->gia) ? number_format($product->gia, 0, ',', '.') . ' VNĐ' : $product->gia }}
                            @endif
                        </span>
                    </div>
                    <form action="{{ route('cart.add') }}" method="POST" class="game-actions">
                        @csrf
                        <input type="hidden" name="game_id" value="{{ $product->id }}">
                        <button type="submit" class="btn btn-primary add-to-cart">Thêm vào giỏ hàng</button>
                    </form>
                    <form action="{{ route('wishlist.add', ['game_id' => $product->id]) }}" method="POST" onclick="event.stopPropagation();">
                        @csrf
                        <button type="submit" class="btn btn-secondary wishlist-btn btn-item-show">
                            <i class="fa-solid fa-heart"></i>
                        </button>
                    </form>

                    @if(session('success'))
                        <div class="success" style="color: green;">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="error" style="color: red;">{{ session('error') }}</div>
                    @endif

                    {{-- Thông số kỹ thuật --}}
                    <div class="game-specs">
                        <h4>Thông số kỹ thuật</h4>
                        <ul>
                            <li>
                                <strong>Yêu cầu tối thiểu:</strong>
                                {!! nl2br(e($product->cauhinhtt)) !!}
                            </li>
                            <li>
                                <strong>Yêu cầu đề xuất:</strong> 
                                {!! nl2br(e($product->cauhinhdx)) !!}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Mô tả game --}}
            <div class="game-description">
                <h3>Mô tả game</h3>
                <p id="gameDescription">{{ $product->mota }}</p>
            </div>
        </div>
    </div>
</main>

{{-- JS để thay đổi ảnh chính --}}
<script>
function changeMainImage(el) {
    document.getElementById('gameMainImage').src = el.src;
    document.querySelectorAll('.thumbnail').forEach(img => img.classList.remove('active'));
    el.classList.add('active');
}

function toggleWishlist() {
    alert('Đã thêm vào danh sách yêu thích!');
}
</script>
@endsection
