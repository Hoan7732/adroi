@extends('layouts.admin')

@section('admin-content')

    <!-- Content -->
    <div class="content fade-in">
      <div class="game-detail-grid">
        <!-- Game Info -->
        <div class="card">
          <h2 style="margin-bottom: 20px; font-size: 24px; font-weight: 700;">
            {{ $product->name }}
          </h2>
          
          <div class="game-images">
            @if($product->anh)
              <div class="game-image">
                <img src="{{ asset('images/'.$product->anh) }}" alt="Game screenshot">
              </div>
            @endif

            @if($product->anhphu)
              @foreach(json_decode($product->anhphu, true) as $subImage)
                <div class="game-image">
                  <img src="{{ asset('images/'.$subImage) }}" alt="Game screenshot">
                </div>
              @endforeach
            @endif
          </div>

          <div style="margin-bottom: 24px;">
            <h3 style="margin-bottom: 12px; font-weight: 600;">Mô tả</h3>
            <p style="line-height: 1.6; color: var(--text-secondary);">
              {{ $product->mota }}
            </p>
          </div>

          <div style="margin-bottom: 24px;">
            <h3 style="margin-bottom: 12px; font-weight: 600;">Yêu cầu hệ thống</h3>
            <div style="background: var(--surface-2); padding: 16px; border-radius: 8px;">
              <p style="line-height: 1.6; color: var(--text-secondary); font-size: 13px;">
                <strong>Tối thiểu:</strong><br>
                {!! nl2br(e($product->cauhinhtt)) !!}
              </p>
              <p style="margin-top: 12px; line-height: 1.6; color: var(--text-secondary); font-size: 13px;">
                <strong>Đề xuất:</strong><br>
                {!! nl2br(e($product->cauhinhdx)) !!}
              </p>
            </div>
          </div>
        </div>

        <!-- Game Meta -->
        <div class="game-meta">
          <h3 style="margin-bottom: 20px; font-weight: 600;">Thông tin</h3>
          
          <div class="meta-item">
            <span class="meta-label">Thể loại:</span>
            <span class="meta-value">{{ $product->category }}</span>
          </div>
          <div class="meta-item">
            <span class="meta-label">Giá gốc:</span>
            <span class="meta-value">
              {{ $product->gia == 0 ? 'Free' : number_format($product->gia, 0, ',', '.') . ' VNĐ' }}
            </span>
          </div>
          @if(!empty($product->giakhuyenmai))
          <div class="meta-item">
            <span class="meta-label">Giá khuyến mãi:</span>
            <span class="meta-value" style="color: var(--success);">
              {{ number_format($product->giakhuyenmai, 0, ',', '.') }}₫
            </span>
          </div>
          @endif
          <div class="meta-item">
            <span class="meta-label">Ngày phát hành:</span>
            <span class="meta-value">{{ \Carbon\Carbon::parse($product->date)->format('d/m/Y') }}</span>
          </div>
          <div class="meta-item">
            <span class="meta-label">Trạng thái:</span>
            <span class="status {{ $product->soluong > 0 ? 'status-success' : 'status-error' }}">
              {{ $product->soluong > 0 ? 'Active' : 'Sold' }}
            </span>
          </div>
          <div class="meta-item">
            <span class="meta-label">Số lượng:</span>
            <span class="meta-value">{{ $product->soluong }}</span>
          </div>

          <div style="margin-top: 24px; display: flex; flex-direction: column; gap: 12px;">
            <a href="{{ route('admin.edit', $product->id) }}" class="btn btn-primary w-full text-center">
              Sửa thông tin
            </a>
            <form action="{{ route('admin.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa game này?')">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-error w-full">Xóa game</button>
            </form>
            <button type="button" class="btn btn-secondary" onclick="window.location.href='{{ route('admin.index') }}'">
              Quay lại
            </button>
          </div>
        </div>
      </div>
    </div>
@endsection
