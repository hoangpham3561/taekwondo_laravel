<nav id="sidebar" aria-label="Main Navigation">
    <div class="content-header">
        <a class="font-semibold text-dual" href="/">
            <span class="smini-visible">
                <i class="fa fa-circle-notch text-primary"></i>
            </span>
            <span class="smini-hide fs-5 tracking-wider">Admin<span class="fw-normal">UI</span></span>
        </a>

        <div>
            <a class="btn btn-sm btn-alt-secondary" data-toggle="layout" data-action="dark_mode_toggle" href="javascript:void(0)">
                <i class="far fa-moon"></i>
            </a>
            <div class="dropdown d-inline-block ms-1">
                <a class="btn btn-sm btn-alt-secondary" id="sidebar-themes-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="#">
                    <i class="far fa-circle"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-end fs-sm smini-hide border-0" aria-labelledby="sidebar-themes-dropdown">
                    <a class="dropdown-item d-flex align-items-center justify-content-between font-medium" data-toggle="theme" data-theme="default" href="#">
                        <span>Default</span>
                        <i class="fa fa-circle text-default"></i>
                    </a>
                    <span class="dropdown-item text-muted">Đã đồng bộ 1 bộ giao diện CSS</span>
                    <!-- END Color Themes -->

                    <div class="dropdown-divider"></div>

                    <!-- Sidebar Styles -->
                    <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                    <a class="dropdown-item fw-medium" data-toggle="layout" data-action="sidebar_style_light" href="javascript:void(0)">
                        <span>Sidebar Light</span>
                    </a>
                    <a class="dropdown-item fw-medium" data-toggle="layout" data-action="sidebar_style_dark" href="javascript:void(0)">
                        <span>Sidebar Dark</span>
                    </a>
                    <!-- END Sidebar Styles -->

                    <div class="dropdown-divider"></div>

                    <!-- Header Styles -->
                    <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                    <a class="dropdown-item fw-medium" data-toggle="layout" data-action="header_style_light" href="javascript:void(0)">
                        <span>Header Light</span>
                    </a>
                    <a class="dropdown-item fw-medium" data-toggle="layout" data-action="header_style_dark" href="javascript:void(0)">
                        <span>Header Dark</span>
                    </a>
                    <!-- END Header Styles -->
                </div>
            </div>
            <!-- END Options -->

            <!-- Close Sidebar, Visible only on mobile screens -->
            <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
            <a class="d-lg-none btn btn-sm btn-alt-secondary ms-1" data-toggle="layout" data-action="sidebar_close" href="javascript:void(0)">
                <i class="mdi mdi-delete"></i>
            </a>
            <!-- END Close Sidebar -->
        </div>
        <!-- END Extra -->
    </div>
    <!-- END Side Header -->

    <div class="js-sidebar-scroll">
        <div class="content-side">
            <ul class="nav-main">
                @php
                    $admin = auth()->guard('admin')->user();
                    $roleName = $admin && $admin->role ? $admin->role->name : null;
                @endphp

                {{-- Menu cho KẾ TOÁN - Chỉ hiển thị Quản lý đơn hàng --}}
                @if($roleName === 'admin_ketoan')

                    <li class="nav-main-heading">Quản lý nạp - rút</li>
                    <li class="nav-main-item open">
                        <a class="nav-main-link{{ request()->is('withdraws') ? ' active' : '' }}" href="{{ route('admin.withdraws.index') }}">
                            <i class="nav-main-link-icon si si-wallet"></i>
                            <span class="nav-main-link-name">Quản lý rút tiền</span>
                        </a>
                    </li>

                    <li class="nav-main-heading">Quản lý mua hàng</li>
                    <li class="nav-main-item open">
                        <a class="nav-main-link{{ request()->is('admin/orders') && !request()->is('admin/orders/*') ? ' active' : '' }}" href="{{ route('admin.orders.index') }}">
                            <i class="nav-main-link-icon si si-bag"></i>
                            <span class="nav-main-link-name">Tất cả đơn hàng</span>
                        </a>
                    </li>

                    <li class="nav-main-item open">
                        <a class="nav-main-link{{ request()->is('admin/orders/wholesale') ? ' active' : '' }}" href="{{ route('admin.orders.wholesale') }}">
                            <i class="nav-main-link-icon si si-bag"></i>
                            <span class="nav-main-link-name">Đơn hàng sỉ</span>
                        </a>
                    </li>

                    <li class="nav-main-item open">
                        <a class="nav-main-link{{ request()->is('admin/orders/agency') ? ' active' : '' }}" href="{{ route('admin.orders.agency') }}">
                            <i class="nav-main-link-icon si si-bag"></i>
                            <span class="nav-main-link-name">Đơn hàng đại lý</span>
                        </a>
                    </li>

                    <li class="nav-main-item open">
                        <a class="nav-main-link{{ request()->is('admin/orders/retail') ? ' active' : '' }}" href="{{ route('admin.orders.retail') }}">
                            <i class="nav-main-link-icon si si-bag"></i>
                            <span class="nav-main-link-name">Đơn hàng vãng lai</span>
                        </a>
                    </li>
                @else
                    <li class="nav-main-item">
                        <a class="nav-main-link{{ request()->is('admin.dashboard') ? ' active' : '' }}" href="{{ route('admin.dashboard') }}">
                            <i class="nav-main-link-icon si si-cursor"></i>
                            <span class="nav-main-link-name">Dashboard</span>
                        </a>
                    </li>

                    {{-- Menu cho SUPER ADMIN - Hiển thị tất cả --}}
                    <li class="nav-main-heading">Quản lý người dùng</li>
                    <li class="nav-main-item open">
                        <a class="nav-main-link{{ request()->is('user') && !request()->is('user/inactive') ? ' active' : '' }}" href="{{ route('admin.users.index') }}">
                            <i class="nav-main-link-icon si si-user"></i>
                            <span class="nav-main-link-name">Người dùng đã kích hoạt</span>
                        </a>
                        <li class="nav-main-item open">
                            <a class="nav-main-link{{ request()->is('user/inactive') ? ' active' : '' }}" href="{{ route('admin.users.inactive') }}">
                                <i class="nav-main-link-icon si si-user"></i>
                                <span class="nav-main-link-name">Người dùng chưa kích hoạt</span>
                            </a>
                        </li>
                        <a class="nav-main-link{{ request()->is('tree-down') ? ' active' : '' }}" href="{{ route('admin.view-tree-down.index') }}">
                            <i class="nav-main-link-icon si si-user"></i>
                            <span class="nav-main-link-name">Danh sách Tree DownLogs</span>
                        </a>
                    </li>

                    <li class="nav-main-heading">Quản lý hoa hồng</li>
                    <li class="nav-main-item open">
                        <a class="nav-main-link{{ request()->is('commissions') ? ' active' : '' }}" href="{{ route('admin.commissions.index') }}">
                            <i class="nav-main-link-icon si si-wallet"></i>
                            <span class="nav-main-link-name">Quản lý hoa hồng</span>
                        </a>
                    </li>
                    <li class="nav-main-item open">
                        <a class="nav-main-link{{ request()->is('transaction-logs') ? ' active' : '' }}" href="{{ route('admin.transaction-logs.index') }}">
                            <i class="nav-main-link-icon si si-wallet"></i>
                            <span class="nav-main-link-name">Quản lý Transaction Logs</span>
                        </a>
                    </li>

                    <li class="nav-main-heading">Quản lý nạp - rút - chuyển khoản</li>
                    <!-- <li class="nav-main-item open">
                        <a class="nav-main-link{{ request()->is('deposits') ? ' active' : '' }}" href="{{ route('admin.deposits.index') }}">
                            <i class="nav-main-link-icon si si-credit-card"></i>
                            <span class="nav-main-link-name">Quản lý nạp tiêu dùng</span>
                        </a>
                    </li> -->
                    <!-- <li class="nav-main-item open">
                        <a class="nav-main-link{{ request()->is('transfers') ? ' active' : '' }}" href="{{ route('admin.transfers.index') }}">
                            <i class="nav-main-link-icon si si-action-redo"></i>
                            <span class="nav-main-link-name">Quản lý chuyển khoản</span>
                        </a>
                    </li> -->
                    <li class="nav-main-item open">
                        <a class="nav-main-link{{ request()->is('withdraws') ? ' active' : '' }}" href="{{ route('admin.withdraws.index') }}">
                            <i class="nav-main-link-icon si si-wallet"></i>
                            <span class="nav-main-link-name">Quản lý rút tiền</span>
                        </a>
                    </li>

                    <!-- <li class="nav-main-item open">
                        <a class="nav-main-link{{ request()->is('user/kyc') ? ' active' : '' }}" href="{{ route('admin.users.kycList') }}">
                            <i class="nav-main-link-icon si si-globe"></i>
                            <span class="nav-main-link-name">Quản lý KYC</span>
                        </a>
                    </li> -->

                    <li class="nav-main-heading">Quản lý mua hàng</li>
                    <li class="nav-main-item open">
                        <a class="nav-main-link{{ request()->is('admin/orders') && !request()->is('admin/orders/*') ? ' active' : '' }}" href="{{ route('admin.orders.index') }}">
                            <i class="nav-main-link-icon si si-bag"></i>
                            <span class="nav-main-link-name">Tất cả đơn hàng</span>
                        </a>
                    </li>

                    <li class="nav-main-item open">
                        <a class="nav-main-link{{ request()->is('admin/orders/wholesale') ? ' active' : '' }}" href="{{ route('admin.orders.wholesale') }}">
                            <i class="nav-main-link-icon si si-bag"></i>
                            <span class="nav-main-link-name">Đơn hàng sỉ</span>
                        </a>
                    </li>

                    <li class="nav-main-item open">
                        <a class="nav-main-link{{ request()->is('admin/orders/agency') ? ' active' : '' }}" href="{{ route('admin.orders.agency') }}">
                            <i class="nav-main-link-icon si si-bag"></i>
                            <span class="nav-main-link-name">Đơn hàng đại lý</span>
                        </a>
                    </li>

                    <li class="nav-main-item open">
                        <a class="nav-main-link{{ request()->is('admin/orders/retail') ? ' active' : '' }}" href="{{ route('admin.orders.retail') }}">
                            <i class="nav-main-link-icon si si-bag"></i>
                            <span class="nav-main-link-name">Đơn hàng vãng lai</span>
                        </a>
                    </li>

                    <li class="nav-main-heading">Quản lý sản phẩm</li>
                    <li class="nav-main-item open">
                        <a class="nav-main-link{{ request()->is('products*') ? ' active' : '' }}" href="{{ route('admin.products.index') }}">
                            <i class="nav-main-link-icon si si-basket"></i>
                            <span class="nav-main-link-name">Quản lý sản phẩm</span>
                        </a>
                    </li>
                    <li class="nav-main-item open">
                        <a class="nav-main-link{{ request()->is('product-categories*') ? ' active' : '' }}" href="{{ route('admin.product-categories.index') }}">
                            <i class="nav-main-link-icon si si-list"></i>
                            <span class="nav-main-link-name">Quản lý danh mục sản phẩm</span>
                        </a>
                    </li>

                    <li class="nav-main-item open">
                        <a class="nav-main-link{{ request()->is('product-countries*') ? ' active' : '' }}" href="{{ route('admin.product-countries.index') }}">
                            <i class="nav-main-link-icon si si-globe"></i>
                            <span class="nav-main-link-name">Quản lý quốc gia</span>
                        </a>
                    </li>

                    <li class="nav-main-heading">Quản lý hệ thống</li>
                    <li class="nav-main-item open">
                        <a class="nav-main-link{{ request()->is('log_activities_adm/*') ? ' active' : '' }}" href="{{ route('admin.log_activities_adm.index') }}">
                            <i class="nav-main-link-icon si si-globe"></i>
                            <span class="nav-main-link-name">Activity log</span>
                        </a>
                    </li>
                    <!-- <li class="nav-main-item open">
                        <a class="nav-main-link{{ request()->is('telegram/*') ? ' active' : '' }}" href="{{ route('admin.telegram.form') }}">
                            <i class="nav-main-link-icon si si-globe"></i>
                            <span class="nav-main-link-name">Telegram</span>
                        </a>
                    </li> -->
                @endif
            </ul>
        </div>
    </div>
</nav>