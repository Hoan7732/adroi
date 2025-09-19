@extends('layouts.app')

@section('content')
<main class="main">
    <div class="container">
        <h1 class="page-title">Giỏ hàng của bạn</h1>
        
        <div class="cart-layout">
            <!-- Danh sách sản phẩm -->
            <div class="cart-items">
                <div class="cart-header">
                    <span>Sản phẩm</span>
                    <span>Giá</span>
                    <span>Thao tác</span>
                </div>
                
                <div id="cartItemsList">
                    @forelse($cart_items as $item)
                        <div class="cart-item" data-id="{{ $item['id'] }}">
                            <!-- Cột sản phẩm -->
                            <div class="item-info">
                                <img src="{{ asset('images/' . $item['image']) }}" 
                                     alt="{{ $item['name'] }}" 
                                     class="item-image">
                                <div class="item-details">
                                    <h4>{{ $item['name'] }}</h4>
                                    <p>Số lượng: {{ $item['quantity'] }}</p>
                                </div>
                            </div>

                            <!-- Cột giá -->
                            <div class="item-price">
                                {{ number_format($item['price'], 0, ',', '.') }}đ
                            </div>

                            <!-- Cột thao tác -->
                            <div class="item-actions">
                                <!-- Nút tăng giảm số lượng -->
                                <form action="{{ route('cart.update') }}" method="POST" style="display: flex; gap: 0.5rem;">
                                    @csrf
                                    <input type="hidden" name="game_id" value="{{ $item['id'] }}">
                                    <button type="submit" name="action" value="decrease" class="btn btn-secondary btn-small">-</button>
                                    <button type="submit" name="action" value="increase" class="btn btn-secondary btn-small">+</button>
                                </form>
                                
                                <!-- Nút xóa sản phẩm -->
                                <form action="{{ route('cart.remove') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="game_id" value="{{ $item['id'] }}">
                                    <button type="submit" class="btn btn-danger btn-small"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="empty-state">Giỏ hàng trống.</p>
                    @endforelse
                </div>
            </div>
            
            <!-- Tóm tắt đơn hàng -->
            <div class="cart-summary">
                <h3>Tóm tắt đơn hàng</h3>
                <div class="summary-row">
                    <span>Tạm tính:</span>
                    <span id="subtotal">{{ number_format($total, 0, ',', '.') }} VNĐ</span>
                </div>
                <div class="summary-row">
                    <span>Giảm giá:</span>
                    <span id="discount">0 VNĐ</span>
                </div>
                <div class="summary-row total">
                    <span>Tổng cộng:</span>
                    <span id="total">{{ number_format($total, 0, ',', '.') }} VNĐ</span>
                </div>
                
                <div class="coupon-section">
                    <input type="text" id="couponCode" placeholder="Mã giảm giá">
                    <button class="btn btn-secondary" type="button" onclick="applyCoupon()">Áp dụng</button>
                </div>
                
                <div class="cart-actions">
                    <form action="{{ route('checkout.index') }}" method="GET">
                        <button type="submit" class="btn btn-primary checkout-btn">Thanh toán</button>
                    </form>
                    <a href="{{ route('guest.index') }}" class="btn btn-secondary">Tiếp tục mua sắm</a>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
