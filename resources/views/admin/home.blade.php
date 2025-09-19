@extends('layouts.admin')

@section('admin-content')
  <div class="content fade-in">
    <!-- Statistics -->
    <div class="stat-grid">
      <div class="stat-card">
        <h3>Tổng doanh thu</h3>
        <div class="value">{{ $formattedRevenue }}</div>
      </div>

      <div class="stat-card">
        <h3>Đơn hàng hôm nay</h3>
        <div class="value">{{ $todayOrders }}</div>
      </div>

      <div class="stat-card">
        <h3>Tổng số game</h3>
        <div class="value">{{ $totalGames }}</div>
      </div>

      <div class="stat-card">
        <h3>Người dùng đăng ký</h3>
        <div class="value">{{ $totalUsers }}</div>
      </div>
    </div>

    <!-- Recent orders -->
    <div class="card">
      <h2 class="mb-4 font-semibold text-lg">Đơn hàng gần đây</h2>
      <div class="table-container">
        <table>
          <thead>
            <tr>
              <th>Khách hàng</th>
              <th>Game</th>
              <th>Tổng tiền</th>
              <th>Trạng thái</th>
              <th>Ngày đặt</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($recentOrders as $order)
              @php
                $names = $order->product_names ?? [];
                $status = $order->trangthai ?? '';
                $statusClass = 'status-info';
                $t = mb_strtolower(trim($status));
                if (mb_stripos($t, 'hoàn') !== false || mb_stripos($t, 'complete') !== false || mb_stripos($t, 'paid') !== false || mb_stripos($t, 'thanh') !== false) {
                    $statusClass = 'status-success';
                } elseif (mb_stripos($t, 'hủy') !== false || mb_stripos($t, 'cancel') !== false) {
                    $statusClass = 'status-error';
                } elseif (mb_stripos($t, 'đang') !== false || mb_stripos($t, 'pending') !== false) {
                    $statusClass = 'status-pending';
                }
              @endphp
              <tr>
                <td>{{ $order->full_name }}</td>
                <td>
                  {{ is_array($names) ? implode(', ', $names) : ($names ?: 'Unknown') }}
                </td>
                <td>{{ number_format((int)$order->total_amount, 0, ',', '.') }} VNĐ</td>
                <td><span class="status {{ $statusClass }}">{{ $order->trangthai }}</span></td>
                <td>{{ optional($order->created_at)->format('d/m/Y') }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center">Chưa có đơn hàng nào</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- Charts -->
    <div class="chart-grid">
      <div class="chart-card">
        <div class="chart-header">
          <h3 class="chart-title">Doanh thu theo tháng</h3>
          <p class="chart-subtitle">12 tháng gần đây</p>
        </div>
        <div style="height: 300px;"><canvas id="revenueChart"></canvas></div>
      </div>

      <div class="chart-card">
        <div class="chart-header">
          <h3 class="chart-title">Top game bán chạy</h3>
          <p class="chart-subtitle">Tuần này</p>
        </div>
        <div style="height: 300px;"><canvas id="topGamesChart"></canvas></div>
      </div>
    </div>
  </div>

  {{-- Chart.js CDN --}}
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      fetch("{{ route('admin.api.revenueChart') }}")
        .then(r => r.json())
        .then(data => {
          const ctx = document.getElementById('revenueChart').getContext('2d');
          new Chart(ctx, { type: 'line', data: data, options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } } } });
        })
        .catch(e => { console.error('Lỗi tải revenueChart', e); });

      fetch("{{ route('admin.api.topGamesChart') }}")
        .then(r => r.json())
        .then(data => {
          if (!data.labels || data.labels.length === 0) {
            data.labels = ['Không có dữ liệu'];
            data.datasets = [{ label: 'Số lượng bán', data: [0] }];
          }
          const ctx2 = document.getElementById('topGamesChart').getContext('2d');
          new Chart(ctx2, { type: 'bar', data: data, options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } } } });
        })
        .catch(e => { console.error('Lỗi tải topGamesChart', e); });
    });
  </script>
@endsection
