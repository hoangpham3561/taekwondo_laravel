@if(session('success') || session('error') || session('warning') || session('info') || $errors->any())
<style>
    .toast-container {
        animation: slideInRight 0.3s ease-out;
    }

    .toast {
        min-width: 300px;
        max-width: 400px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 2px 4px rgba(0, 0, 0, 0.06);
        transition: all 0.3s ease-in-out;
    }

    .toast.showing {
        opacity: 0;
        transform: translateX(100%);
    }

    .toast.show:not(.hide) {
        opacity: 1;
        transform: translateX(0);
    }

    .toast.hiding {
        opacity: 0;
        transform: translateX(100%);
    }

    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }

        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
</style>

<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999; margin-top: 70px;">
    @if(session('success'))
    <div class="toast mb-2" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="5000">
        <div class="toast-header bg-success text-white">
            <i class="fas fa-check-circle me-2"></i>
            <strong class="me-auto">Thành công</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body bg-light">
            {{ session('success') }}
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="toast mb-2" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="5000">
        <div class="toast-header bg-danger text-white">
            <i class="fas fa-exclamation-circle me-2"></i>
            <strong class="me-auto">Lỗi</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body bg-light">
            {{ session('error') }}
        </div>
    </div>
    @endif

    @if(session('warning'))
    <div class="toast mb-2" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="5000">
        <div class="toast-header bg-warning text-dark">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong class="me-auto">Cảnh báo</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body bg-light">
            {{ session('warning') }}
        </div>
    </div>
    @endif

    @if(session('info'))
    <div class="toast mb-2" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="5000">
        <div class="toast-header bg-info text-white">
            <i class="fas fa-info-circle me-2"></i>
            <strong class="me-auto">Thông tin</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body bg-light">
            {{ session('info') }}
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="toast mb-2" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="7000">
        <div class="toast-header bg-danger text-white">
            <i class="fas fa-exclamation-circle me-2"></i>
            <strong class="me-auto">Lỗi validation</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body bg-light">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif
</div>

<script>
    // Khởi tạo và tự động hiển thị toast với animation mượt
    document.addEventListener('DOMContentLoaded', function() {
        const toastElements = document.querySelectorAll('.toast');
        toastElements.forEach(function(toastEl, index) {
            // Thêm delay nhỏ cho mỗi toast để hiển thị lần lượt
            setTimeout(function() {
                const toast = new bootstrap.Toast(toastEl, {
                    autohide: true,
                    delay: parseInt(toastEl.getAttribute('data-bs-delay')) || 5000,
                    animation: true
                });

                // Thêm event listener để xử lý animation
                toastEl.addEventListener('show.bs.toast', function() {
                    toastEl.style.opacity = '0';
                    toastEl.style.transform = 'translateX(100%)';
                    setTimeout(function() {
                        toastEl.style.transition = 'all 0.3s ease-out';
                        toastEl.style.opacity = '1';
                        toastEl.style.transform = 'translateX(0)';
                    }, 10);
                });

                toastEl.addEventListener('hide.bs.toast', function() {
                    toastEl.style.transition = 'all 0.3s ease-in';
                    toastEl.style.opacity = '0';
                    toastEl.style.transform = 'translateX(100%)';
                });

                toast.show();
            }, index * 100); // Delay 100ms cho mỗi toast tiếp theo
        });
    });
</script>
@endif