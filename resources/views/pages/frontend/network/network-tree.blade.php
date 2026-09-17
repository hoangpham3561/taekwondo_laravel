@extends('layout.frontend.office')
@section('content')
<section class="office-content-wrapper">
    <div class="office">
        <div class="office-main-wrapper container mt-5 mt-lg-0">
            <div class="office-main">
                <h3 class="office-title px-2 d-flex justify-content-between align-items-center">
                    NETWORK
                    <div class="office-mobile-toggle d-lg-none">
                        <button class="btn btn-outline-light" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-controls="mobileSidebar">
                            <img src="{{ asset('client/images/launchpad/arrow-down.svg') }}" alt="icon">
                        </button>
                    </div>
                </h3>

                <div class="office-card mt-3">
                    <div class="network-tree-container p-3" style="overflow-x: auto;">
                        <ul class="network-tree" id="networkTree">
                            @if(!empty($treeHtml))
                            {!! $treeHtml !!}
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection