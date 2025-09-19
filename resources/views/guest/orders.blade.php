@extends('layouts.app')

@section('content')
<main class="main">
    <div class="container">
        <h1 class="page-title">Đơn hàng của tôi</h1>

        <!-- Filter buttons -->
        <div class="order-filters" style="margin-bottom:1.25rem;">
            <button class="filter-btn active" onclick="filterOrders('all', event)">Tất cả</button>
            <button class="filter-btn" onclick="filterOrders('pending', event)">Chờ xử lý</button>
            <button class="filter-btn" onclick="filterOrders('completed', event)">Hoàn thành</button>
            <button class="filter-btn" onclick="filterOrders('cancelled', event)">Đã hủy</button>
        </div>

        <!-- Orders list -->
        <div class="orders-list" id="ordersList">
            @if(isset($orders) && $orders->isNotEmpty())
                @foreach($orders as $order)
                    <div class="order-card" data-status="{{ $order->trangthai }}">
                        {{-- Header --}}
                        <div class="order-header">
                            <div>
                                <h3 class="order-id">Đơn hàng</h3>
                                <p class="order-date">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <span class="status-badge 
                                @if($order->trangthai === 'pending') status-pending 
                                @elseif($order->trangthai === 'completed') status-completed 
                                @elseif($order->trangthai === 'cancelled') status-cancelled 
                                @endif">
                                @if($order->trangthai === 'pending') Chờ xử lý
                                @elseif($order->trangthai === 'completed') Hoàn thành
                                @elseif($order->trangthai === 'cancelled') Đã hủy
                                @endif
                            </span>
                        </div>

                        {{-- Items --}}
                        <div class="order-items">
                            @if(!empty($order->items))
                                @foreach($order->items as $item)
                                    <div class="order-item">
                                        <img src="{{ asset('images/' . ($item['game']['image'] ?? 'no-image.png')) }}" 
                                             alt="{{ $item['game']['title'] ?? 'Không có tên' }}" 
                                             class="order-item-image">
                                        <div class="order-item-info">
                                            <h5>{{ $item['game']['title'] ?? 'Sản phẩm' }}</h5>
                                            <p>Digital Download</p>
                                        </div>
                                        <div class="order-item-price">
                                            {{ number_format($item['price'] ?? 0, 0, ',', '.') }}đ
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p class="empty-item">Đơn hàng này chưa có sản phẩm.</p>
                            @endif
                        </div>

                        {{-- Tổng tiền --}}
                        <div class="order-total">
                            Tổng: {{ number_format($order->total_amount ?? 0, 0, ',', '.') }}đ
                        </div>

                        {{-- Hành động --}}
                        <div class="order-actions">
                            <a href="{{ route('order.details', $order->id) }}" class="btn btn-secondary btn-small">Xem chi tiết</a>

                            @if($order->trangthai === 'completed')
                                <a href="#" class="btn btn-primary btn-small">Tải về (developing)</a>
                            @elseif($order->trangthai === 'pending')
                                <button class="btn btn-danger btn-small cancel-order-btn" data-order-id="{{ $order->id }}">Hủy đơn</button>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <div class="empty-icon">📦</div>
                    <h3>Chưa có đơn hàng nào</h3>
                    <p>Bắt đầu mua sắm để xem đơn hàng tại đây</p>
                    <a href="{{ route('home') }}" class="btn btn-primary">Khám phá game</a>
                </div>
            @endif
        </div>

        <div class="custom-pagination">
            @if ($orders->hasPages())
                <ul class="pagination">
                    <li class="{{ $orders->currentPage() == 1 ? 'disabled' : '' }}">
                        <a href="{{ $orders->previousPageUrl() }}" class="page-link">&lt;</a>
                    </li>
                    @foreach ($orders->getUrlRange(1, $orders->lastPage()) as $page => $url)
                        <li class="{{ $orders->currentPage() == $page ? 'active' : '' }}">
                            <a href="{{ $url }}" class="page-link">{{ $page }}</a>
                        </li>
                    @endforeach
                    <li class="{{ $orders->currentPage() == $orders->lastPage() ? 'disabled' : '' }}">
                        <a href="{{ $orders->nextPageUrl() }}" class="page-link">&gt;</a>
                    </li>
                </ul>
            @endif
        </div>
    </div>
</main>

<script>
function filterOrders(status) {
        document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
        event.target.classList.add('active');

        document.querySelectorAll('.order-card').forEach(card => {
            if (status === 'all' || card.dataset.status === status) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

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
                        location.reload(); // Reload to update order status
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