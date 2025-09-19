@extends('layouts.app')

@section('content')
<main class="main">
    <div class="container">
        <h1 class="page-title">Thanh toán</h1>
        
        <div class="checkout-layout">
            <!-- Form thanh toán -->
            <div class="checkout-form">
                <form action="{{ route('checkout.store') }}" method="POST">
                    @csrf
                    <div class="checkout-section">
                        <h3>Thông tin thanh toán</h3>
                        
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email"
                                   value="{{ $user->email }}" readonly required>
                        </div>
                        
                        <div class="form-group">
                            <label for="fullName">Họ và tên</label>
                            <input type="text" id="fullName" name="fullName"
                                   value="{{ $user->name }}" readonly required>
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">Số điện thoại</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required>
                            @error('phone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="checkout-section">
                        <h3>Phương thức thanh toán</h3>
                        <div class="payment-methods">
                            <label class="payment-option">
                                <input type="radio" name="paymentMethod" value="zalo" checked>
                                <div class="payment-card">
                                    <img src="https://cdn.pnj.io/images/image-update/2021/hotline/zalo.svg" alt="zalo"></i>
                                    <span>Zalopay</span>
                                </div>
                            </label>
                            
                            <label class="payment-option">
                                <input type="radio" name="paymentMethod" value="banking">
                                <div class="payment-card">
                                    <img src="https://cdn-icons-png.flaticon.com/128/2830/2830284.png" alt="bank" width="34">
                                    <span>Chuyển khoản ngân hàng (developing)</span>
                                </div>
                            </label>
                            
                            <label class="payment-option">
                                <input type="radio" name="paymentMethod" value="visa">
                                <div class="payment-card">
                                    <img src="https://cdn-icons-png.flaticon.com/128/8983/8983163.png" alt="paypal" width="32">
                                    <span>Thẻ tín dụng/Visa (developing)</span>
                                </div>
                            </label>
                        </div>
                        @error('paymentMethod')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <button type="submit" class="btn btn-primary checkout-submit">Hoàn tất thanh toán</button>
                </form>
            </div>
            
            <!-- Tóm tắt đơn hàng -->
            <div class="checkout-summary">
                <h3>Đơn hàng của bạn</h3>
                <div id="checkoutItems">
                    @forelse ($cartItems as $item)
                        <div class="summary-item game-item">
                            <img src="{{ asset('images/' . $item['image']) }}" 
                                 alt="{{ $item['name'] }}" width="80">
                            <div>
                                <h4>{{ $item['name'] }}</h4>
                                <span class="price">{{ number_format($item['price'], 0, ',', '.') }}đ</span>
                                <p>Số lượng: {{ $item['quantity'] }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="empty-state">Giỏ hàng trống.</p>
                    @endforelse
                </div>
                
                <div class="summary-totals">
                    <div class="summary-row">
                        <span>Tạm tính:</span>
                        <span id="checkoutSubtotal">{{ number_format($total, 0, ',', '.') }}đ</span>
                    </div>
                    <div class="summary-row">
                        <span>Phí xử lý:</span>
                        <span>0đ</span>
                    </div>
                    <div class="summary-row total">
                        <span>Tổng cộng:</span>
                        <span id="checkoutTotal">{{ number_format($total, 0, ',', '.') }}đ</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
