@if($errors->any())
<div class="alert alert-danger alert-dismissible js-flash-alert" role="alert">
    <h3 class="alert-heading h4 my-2">Error</h3>
    <p class="mb-0">
        {!! $errors->first() !!}
    </p>
    <button type="button" class="btn-close js-flash-close" aria-label="Close"></button>
</div>
@endif
@if (session('error'))
    <div class="alert alert-danger alert-dismissible js-flash-alert" role="alert">
        <h3 class="alert-heading h4 my-2">Error</h3>
        {!! session('error') !!}
        <button type="button" class="btn-close js-flash-close" aria-label="Close"></button>
    </div>
@endif
@if(session('success'))
    <div class="alert alert-success alert-dismissible js-flash-alert js-auto-dismiss" role="alert">
        <h3 class="alert-heading h4 my-2">Success</h3>
        <p class="mb-0">
            {!! session('success') !!}
        </p>
        <button type="button" class="btn-close js-flash-close" aria-label="Close"></button>
    </div>
@endif
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.js-flash-close').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const alert = btn.closest('.js-flash-alert');
                if (alert) {
                    alert.remove();
                }
            });
        });

        document.querySelectorAll('.js-auto-dismiss').forEach(function (alert) {
            setTimeout(function () {
                if (document.body.contains(alert)) {
                    alert.remove();
                }
            }, 2500);
        });
    });
</script>
