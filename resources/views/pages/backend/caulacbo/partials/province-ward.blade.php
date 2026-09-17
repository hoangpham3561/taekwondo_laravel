{{--
  Tỉnh/Thành + Phường/Xã — dữ liệu qua proxy backend (cùng domain), tránh lỗi CORS / chặn gọi API ngoài.
--}}
@php
    use App\Helpers\BaseHelper;
    $provinceCode = $provinceCode ?? null;
    $wardCode = $wardCode ?? null;
    $adminPx = BaseHelper::getAdminPrefix();
    $clbPx = config('core.routes.cau_lac_bo.prefix');
    $provincesProxyUrl = route($adminPx . '.' . $clbPx . '.provinces-open-api-v2');
@endphp

<div
    class="row cau-lac-bo-admin-region"
    data-provinces-proxy="{{ $provincesProxyUrl }}"
    data-initial-province="{{ $provinceCode !== null && $provinceCode !== '' ? (int) $provinceCode : '' }}"
    data-initial-ward="{{ $wardCode !== null && $wardCode !== '' ? (int) $wardCode : '' }}"
>
    <div class="col-md-6 mb-4">
        <label class="form-label" for="province_code">
            Tỉnh / Thành phố
        </label>
        <select class="form-select @error('province_code') is-invalid @enderror" id="province_code" name="province_code">
            <option value="">-- Chọn tỉnh/thành phố --</option>
        </select>
        @error('province_code')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-4">
        <label class="form-label" for="ward_code">
            Phường / Xã
        </label>
        <select class="form-select @error('ward_code') is-invalid @enderror" id="ward_code" name="ward_code" disabled>
            <option value="">-- Chọn phường/xã --</option>
        </select>
        <span class="form-text text-muted small">Chọn tỉnh/thành trước; dữ liệu theo phân cấp hành chính v2.</span>
        @error('ward_code')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

@push('js_after')
    <script>
        (function () {
            var roots = document.querySelectorAll('.cau-lac-bo-admin-region');
            if (!roots.length) return;

            function fetchJson(url) {
                return fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                }).then(function (r) {
                    return r.text().then(function (text) {
                        var body;
                        try {
                            body = text ? JSON.parse(text) : null;
                        } catch (e) {
                            throw new Error('Phản hồi không phải JSON');
                        }
                        if (!r.ok) {
                            var msg = (body && body.message) ? body.message : ('HTTP ' + r.status);
                            throw new Error(msg);
                        }
                        return body;
                    });
                });
            }

            function localeVi(a, b) {
                return String(a).localeCompare(String(b), 'vi', { sensitivity: 'base' });
            }

            function fillProvinces(selProvince, list) {
                selProvince.innerHTML = '<option value="">-- Chọn tỉnh/thành phố --</option>';
                if (!Array.isArray(list)) {
                    throw new Error('Danh sách tỉnh không hợp lệ');
                }
                list.slice().sort(function (a, b) { return localeVi(a.name, b.name); }).forEach(function (p) {
                    var opt = document.createElement('option');
                    opt.value = p.code;
                    opt.textContent = p.name;
                    selProvince.appendChild(opt);
                });
            }

            function fillWards(selWard, provincePayload) {
                var wards = (provincePayload && provincePayload.wards) ? provincePayload.wards : [];
                selWard.innerHTML = '';
                var o0 = document.createElement('option');
                o0.value = '';
                o0.textContent = '-- Chọn phường/xã --';
                selWard.appendChild(o0);
                wards.slice().sort(function (a, b) { return localeVi(a.name, b.name); }).forEach(function (w) {
                    var opt = document.createElement('option');
                    opt.value = w.code;
                    opt.textContent = w.name;
                    selWard.appendChild(opt);
                });
                selWard.disabled = selWard.options.length <= 1;
            }

            function optionDisplayText(sel) {
                if (!sel || !sel.value) return '';
                var o = sel.options[sel.selectedIndex];
                if (!o || !o.textContent) return '';
                var t = o.textContent.trim();
                if (t.indexOf('--') === 0) return '';
                return t;
            }

            function syncAddressFromRegion(wrap, opts) {
                opts = opts || {};
                var addr = document.getElementById('address');
                if (!addr) return;
                var pSel = wrap.querySelector('#province_code');
                var wSel = wrap.querySelector('#ward_code');
                if (!pSel || !wSel) return;
                var pText = optionDisplayText(pSel);
                var wText = optionDisplayText(wSel);
                if (wText && pText) {
                    addr.value = wText + ', ' + pText;
                } else if (pText) {
                    addr.value = pText;
                } else if (opts.clearIfEmpty) {
                    addr.value = '';
                }
            }

            function wireRegion(wrap) {
                var proxy = wrap.getAttribute('data-provinces-proxy');
                if (!proxy) return;

                var selProvince = wrap.querySelector('#province_code');
                var selWard = wrap.querySelector('#ward_code');
                if (!selProvince || !selWard) return;

                var initialProvince = wrap.getAttribute('data-initial-province') || '';
                var initialWard = wrap.getAttribute('data-initial-ward') || '';

                var listUrl = proxy + (proxy.indexOf('?') >= 0 ? '&' : '?') + 'action=list';

                fetchJson(listUrl)
                    .then(function (provinces) {
                        fillProvinces(selProvince, provinces);
                        if (initialProvince) {
                            selProvince.value = initialProvince;
                            var detailUrl = proxy + (proxy.indexOf('?') >= 0 ? '&' : '?') + 'action=province&code=' + encodeURIComponent(initialProvince);
                            return fetchJson(detailUrl)
                                .then(function (data) {
                                    fillWards(selWard, data);
                                    if (initialWard) selWard.value = initialWard;
                                    syncAddressFromRegion(wrap, { clearIfEmpty: false });
                                });
                        }
                    })
                    .catch(function (err) {
                        selProvince.innerHTML = '<option value="">Lỗi tải tỉnh/thành: ' + String(err.message || err) + '</option>';
                    });

                selProvince.addEventListener('change', function () {
                    var code = selProvince.value;
                    selWard.innerHTML = '<option value="">Đang tải…</option>';
                    selWard.disabled = true;
                    if (!code) {
                        fillWards(selWard, null);
                        syncAddressFromRegion(wrap, { clearIfEmpty: true });
                        return;
                    }
                    var detailUrl = proxy + (proxy.indexOf('?') >= 0 ? '&' : '?') + 'action=province&code=' + encodeURIComponent(code);
                    fetchJson(detailUrl)
                        .then(function (data) {
                            fillWards(selWard, data);
                            syncAddressFromRegion(wrap, { clearIfEmpty: false });
                        })
                        .catch(function () {
                            selWard.innerHTML = '<option value="">Lỗi tải phường/xã</option>';
                            selWard.disabled = true;
                        });
                });

                selWard.addEventListener('change', function () {
                    syncAddressFromRegion(wrap, { clearIfEmpty: false });
                });
            }

            roots.forEach(wireRegion);
        })();
    </script>
@endpush
