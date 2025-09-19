@extends('layouts.app')

@section('content')
<main class="main">
    <div class="container">
        <div class="order-detail-header">
            <a href="{{ route('guest.orders') }}" class="back-btn"><i class="fa-solid fa-arrow-left"></i></a>
            <h1 class="page-title">Chi tiết đơn hàng</h1>
        </div>
        
        <div class="order-detail-container">
            <!-- Thông tin đơn hàng -->
            <div class="order-info">
                <div class="order-status">
                    <span class="status-badge status-{{ $order->trangthai }}">
                        {{ ucfirst($order->trangthai) }}
                    </span>
                    <span class="order-date">
                        Đặt ngày: {{ $order->created_at->format('d/m/Y H:i') }}
                    </span>
                </div>
                
                <div class="order-timeline">
                    <div class="timeline-item completed">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <h4>Đặt hàng thành công</h4>
                            <p>{{ $order->created_at->format('d/m/Y - H:i') }}</p>
                        </div>
                    </div>
                    
                    @if($order->trangthai === 'completed' || $order->trangthai === 'shipped')
                        <div class="timeline-item completed">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <h4>Thanh toán thành công</h4>
                                <p>{{ $order->created_at->addMinutes(2)->format('d/m/Y - H:i') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($order->trangthai === 'shipped')
                        <div class="timeline-item completed">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <h4>Giao hàng</h4>
                                <p>{{ $order->created_at->addMinutes(5)->format('d/m/Y - H:i') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Sản phẩm -->
            <div class="order-items">
                <h3>Sản phẩm đã mua</h3>
                <div id="orderItemsList">
                    @forelse ($orderItems as $item)
                        <div class="order-item">
                            <img src="{{ asset('images/' . ($item['game']['image'] ?? 'default.jpg')) }}" 
                                alt="{{ $item['game']['title'] ?? 'Sản phẩm không tên' }}" 
                                class="order-item-image">
                            <div class="order-item-info">
                                <h5>{{ $item['game']['title'] ?? 'Sản phẩm' }}</h5>
                                <p>Số lượng: {{ $item['quantity'] ?? 0 }}</p>
                            </div>
                            <div class="order-item-price">
                                {{ number_format($item['price'] ?? 0, 0, ',', '.') }}đ
                            </div>
                        </div>
                    @empty
                        <p class="empty-state">Không có sản phẩm.</p>
                    @endforelse
                </div>
            </div>
            
            <!-- Tóm tắt -->
            <div class="order-summary">
                <h3>Tóm tắt thanh toán</h3>
                <div class="summary-details">
                    <div class="summary-row">
                        <span>Tạm tính:</span>
                        <span>{{ number_format($total, 0, ',', '.') }}đ</span>
                    </div>
                    <div class="summary-row">
                        <span>Thuế (10%):</span>
                        <span>{{ number_format($tax, 0, ',', '.') }}đ</span>
                    </div>
                    <div class="summary-row total">
                        <span>Tổng cộng:</span>
                        <span>{{ number_format($grandTotal, 0, ',', '.') }}đ</span>
                    </div>
                </div>
                
                <div class="payment-info">
                    <h4>Phương thức thanh toán</h4>
                    <p>{{ ucfirst($order->payment_method) ?? 'Chưa xác định' }}</p>
                </div>

                @if($order->trangthai === 'pending')
                    <button class="btn btn-danger cancel-order-btn" data-order-id="{{ $order->id }}">Hủy đơn</button>
                @endif
            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.cancel-order-btn').forEach(button => {
        button.addEventListener('click', function () {
            const orderId = this.getAttribute('data-order-id');
            if (confirm('Bạn có chắc muốn hủy đơn hàng này?')) {
                fetch(`/checkout/refund/${orderId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload(); // Tải lại trang để cập nhật trạng thái
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    alert('Đã xảy ra lỗi: ' + error.message);
                });
            }
        });
    });
});
</script>
@endsection