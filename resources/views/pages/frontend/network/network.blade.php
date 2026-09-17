@extends('layout.frontend.office')
@section('content')
<section class="office-content-wrapper">
    <div class="office">
        <div class="office-main-wrapper container mt-5 mt-lg-0">
            <div class="office-main">
                <h3 class="office-title px-2 d-flex justify-content-between align-items-center">
                    Chia sẻ bạn bè
                    <div class="office-mobile-toggle d-lg-none">
                        <button class="btn btn-outline-light" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-controls="mobileSidebar">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                </h3>
                <div class="row g-4 mx-0 mt-1">
                    <div class="col-12">
                        <div class="office-card share-card mb-3">
                            <div class="share-header">
                                <h4 class="share-card-title">CHIA SẺ VỚI BẠN BÈ</h4>
                                <p class="share-card-subtitle">CÙNG NHAU THAM GIA & NHẬN THƯỞNG</p>
                            </div>
                            <div class="share-content">
                                <div class="share-link-wrapper">
                                    <div class="share-link-input-wrapper">
                                        <input class="form-control share-link-input" id="inputRef" type="text" value="{{ $referralLink }}" readonly>
                                    </div>
                                    <div class="share-copy-btn-wrapper">
                                        <button class="btn share-copy-btn" type="button" onclick="copyRef()">Copy Link</button>
                                    </div>
                                    <div class="share-social-icons-wrapper">
                                        <div class="share-social-icons">
                                            <a class="share-social-icon d-inline-flex align-items-center justify-content-center" href="#" target="_blank" aria-label="Facebook">
                                                <i class="fab fa-facebook-f"></i>
                                            </a>
                                            <a class="share-social-icon d-inline-flex align-items-center justify-content-center" href="#" target="_blank" aria-label="Instagram">
                                                <i class="fab fa-instagram"></i>
                                            </a>
                                            <a class="share-social-icon d-inline-flex align-items-center justify-content-center" href="#" target="_blank" aria-label="Twitter">
                                                <i class="fab fa-twitter"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row my-3">
                            <div class="col-12 col-md-6 mb-2 mb-md-0">
                                <div class="office-card d-flex flex-column align-items-center py-3 h-100">
                                    <div class="text-uppercase text-muted fw-bold mb-2" style="font-size:14px;">PA CÁ NHÂN</div>
                                    <div class="fs-3 fw-bold" style="color: #E95019;">
                                        {{ number_format($paPersonal ?? 0, 0, ',', '.') }} PA
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="office-card d-flex flex-column align-items-center py-3 h-100">
                                    <div class="text-uppercase text-muted fw-bold mb-2" style="font-size:14px;">PA HỆ THỐNG</div>
                                    <div class="fs-3 fw-bold" style="color: #107069;">
                                        {{ number_format($paSystem ?? 0, 0, ',', '.') }} PA
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="office-card share-list-card">
                            <div class="share-list-header">
                                <h4 class="share-list-title">Danh sách</h4>
                                <form method="GET" action="{{ route($userPrefix . '.network') }}" id="filterForm" class="d-inline">
                                    <select class="form-select share-filter-select" id="filterSelect" name="indirect_id" style="max-width: 200px; height: 38px;" onchange="this.form.submit()">
                                        <option value="1" {{ request('indirect_id', '1') == '1' ? 'selected' : '' }}>F1</option>
                                        <option value="2" {{ request('indirect_id') == '2' ? 'selected' : '' }}>F2</option>
                                        <option value="3" {{ request('indirect_id') == '3' ? 'selected' : '' }}>F3</option>
                                    </select>
                                </form>
                            </div>
                            <div class="share-table-wrapper">
                                <table class="table share-table">
                                    <thead>
                                        <tr>
                                            <th class="share-table-header">STT</th>
                                            <th class="share-table-header">TÊN ĐĂNG NHẬP</th>
                                            <th class="share-table-header">HỌ TÊN</th>
                                            <th class="share-table-header">SỐ TIỀN</th>
                                            <th class="share-table-header">NGÀY</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($members ?? [] as $index => $member)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $member->UserName ?? 'N/A' }}</td>
                                            <td>{{ $member->FullName ?? 'N/A' }}</td>
                                            <td>{{ number_format($member->TotalDeposit ?? 0, 0, ',', '.') }} ₫</td>
                                            <td>{{ $member->DateReg ?? 'N/A' }}</td>
                                        </tr>
                                        @empty
                                        <tr class="share-table-empty">
                                            <td colspan="5">
                                                <div class="share-empty-state">
                                                    <i class="fas fa-folder share-empty-icon"></i>
                                                    <p class="share-empty-text">CHƯA CÓ DỮ LIỆU</p>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if($members && $members->count() > 0)
                            <!-- <div class="share-pagination-wrapper">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination share-pagination">
                                        <li class="pages-item">
                                            <a class="pages-link share-pagination-link" href="#" aria-label="Previous">
                                                <i class="fas fa-chevron-left"></i>
                                            </a>
                                        </li>
                                        <li class="pages-item">
                                            <a class="pages-link share-pagination-link active" href="#">1</a>
                                        </li>
                                        <li class="pages-item">
                                            <a class="pages-link share-pagination-link" href="#" aria-label="Next">
                                                <i class="fas fa-chevron-right"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div> -->
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    function copyRef() {
        var copyText = document.getElementById("inputRef");
        copyText.select();
        copyText.setSelectionRange(0, 99999); // For mobile devices

        try {
            navigator.clipboard.writeText(copyText.value);
            // Show success message (optional)
            alert('Đã copy link!');
        } catch (err) {
            document.execCommand("copy");
            alert('Đã copy link!');
        }
    }
</script>
@endpush
@endsection
