@extends('layouts.admin')

@section('admin-content')
<div class="content fade-in">

    <!-- Main Content -->
    <div class="order-detail-grid">
        <!-- Order Items -->
        <div class="order-items">
            <div class="table-header">
                <h3 class="table-title"><i class="fa-solid fa-box"></i> Sản phẩm đã mua</h3>
            </div>

            @if (!empty($order->products) && is_array($order->products))
                @foreach ($order->products as $product)
                    <div class="order-item">
                        <div class="item-info">
                            <div class="item-image">
                                <img src="{{ asset('images/' . $product['image']) }}" 
                                     alt="{{ $product['name'] }}"
                                     style="width: 100%; height: 100%; object-fit: cover; border-radius: 4px;">
                            </div>
                            <div class="item-details">
                                <h4>{{ $product['name'] }}</h4>
                                <p>Số lượng: {{ $product['quantity'] }}</p>
                                <p>Đơn giá: {{ number_format($product['price']) }} đ</p>
                            </div>
                        </div>
                        <div class="item-price">{{ number_format($product['price'] * $product['quantity']) }} đ</div>
                    </div>
                @endforeach

                <div style="padding: 20px; border-top: 1px solid var(--border); background: var(--surface-2);">
                    <div class="flex justify-between mb-2">
                        <span>Tạm tính:</span>
                        <span>{{ number_format($order->total_amount) }} đ</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span>Key:</span>
                        <span>50,000 đ</span>
                    </div>
                    <div class="flex justify-between font-semibold text-lg">
                        <span>Tổng cộng:</span>
                        <span>{{ number_format($order->total_amount + 50000) }} đ</span>
                    </div>
                </div>
                <div class="order-item">
                    <div class="header-actions">
                        <button class="btn btn-warning" onclick="openModal('updateStatusModal')">
                            Cập nhật trạng thái
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="window.location.href='{{ route('orders.index') }}'">
                            Quay lại
                        </button>
                    </div>
                </div>
            @else
                <p>Không có sản phẩm nào.</p>
            @endif
        </div>

        <!-- Order Info -->
        <div class="card">
            <h3 class="mb-4 font-semibold">Thông tin đơn hàng</h3>

            <div class="meta-item">
                <span class="meta-label">Khách hàng:</span>
                <span class="meta-value">{{ $order->full_name }}</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Email:</span>
                <span class="meta-value">{{ $order->email }}</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Số điện thoại:</span>
                <span class="meta-value">{{ $order->phone }}</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Trạng thái:</span>
                <span class="status status-{{ $order->trangthai }}">{{ ucfirst($order->trangthai) }}</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Ngày đặt:</span>
                <span class="meta-value">{{ $order->created_at }}</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Phương thức thanh toán:</span>
                <span class="meta-value">{{ $order->payment_method }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div id="updateStatusModal" class="modal-overlay">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Cập nhật trạng thái đơn hàng</h3>
        </div>
        
        <form method="POST" action="{{ route('orders.updateStatus', $order->id) }}">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label class="form-label">Trạng thái hiện tại</label>
                <input type="text" class="form-input" value="{{ ucfirst($order->trangthai) }}" readonly>
            </div>

            <div class="form-group">
                <label class="form-label">Trạng thái mới</label>
                <select name="trangthai" class="form-select" required>
                    <option value="">Chọn trạng thái</option>
                    <option value="pending" {{ $order->trangthai == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="completed" {{ $order->trangthai == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ $order->trangthai == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            
            <div class="flex justify-end gap-2 mt-4">
                <button type="button" class="btn btn-secondary" onclick="closeModal('updateStatusModal')">Hủy</button>
                <button type="submit" class="btn btn-primary">Cập nhật</button>
            </div>
        </form>
    </div>
</div>
@endsection
