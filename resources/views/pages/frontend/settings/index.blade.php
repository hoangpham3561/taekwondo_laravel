@extends('layouts.frontend.frontend')
@section('content')
    <div class="row">
        @include('layouts.frontend.partials.message')
        <div class="col-lg-12">
            <div class="accordion accordion-main block-settings" id="accordionExample">
                <div class="card">
                    <div class="card-header" id="headingOne">
                        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            <div class="title">
                                <div class="icon">
                                    <img src="{{ asset('frontend/images/icon/12-1.svg') }}" alt="">
                                </div>
                                <div class="text">
                                    Profile
                                </div>
                            </div>
                        </button>
                    </div>

                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <form action="{{ route('.changeAvatar') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="block-acc-main text-center form-lg">
                                            <div class="avatar">
                                                @if(! empty(Auth::guard('web')->user()->userInfo->avatar))
                                                    <a href="#"><img src="{{ asset('storage/upload/' . Auth::guard('web')->user()->userInfo->avatar) }}" alt=""></a>
                                                @else
                                                    <a href="#"><img src="{{ asset('frontend/images/icon/avatar-lg.jpg') }}" alt=""></a>
                                                @endif
                                                    <br/><br/><input type="file" class="avatar" id="example-email-input" name="images">
                                            </div>
                                            <div class="acc-box">
                                                <div class="username text-capitalize">
                                                    <div class="username">{{ Auth::guard('web')->user()->username }}</div>
                                                    <div class="id">ID: {{ Auth::guard('web')->user()->id }}</div>
                                                </div>
                                            </div>
                                            <div class="btn-bottom">
                                                <button class="btn btn-bg-yellow text-uppercase" style="min-width: 105px;">update</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-lg-6">
                                    <form action="{{ route('.changePassword') }}" method="POST">
                                        @csrf
                                        <div class="text-center title-form">
                                            Profile
                                        </div>
                                        <div style="max-width: 480px;" class="m-auto">
                                                <div class="row marg-box-10">
                                                    <div class="col-lg-6 padd-5">
                                                        <div class="form-group">
                                                            <div class="group-s-control active">
                                                                <label for="" class="label-top">Email</label>
                                                                <div class="l-form-hori">
                                                                    <input type="text" readonly class="form-control" placeholder="" name="email" value="{{ Auth::guard('web')->user()->email }}">
                                                                    <div class="icon">
                                                                        <img src="{{ asset('frontend/images/form/1-1-balance-scale.svg') }}" alt="" class="icon-basic">
                                                                        <img src="{{ asset('frontend/images/form/1-2-balance-scale.svg') }}" alt="" class="icon-active">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 padd-5">
                                                        <div class="form-group">
                                                            <div class="group-s-control">
                                                                <label for="" class="label-top">Enter sponsor</label>
                                                                <div class="l-form-hori">
                                                                    <input type="text" class="form-control" placeholder="" name="ref_id">
                                                                    <div class="icon">
                                                                        <img src="{{ asset('frontend/images/login/3-1-bxs-user.svg') }}" alt="" class="icon-basic">
                                                                        <img src="{{ asset('frontend/images/login/3-2-bxs-user.svg') }}" alt="" class="icon-active">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 padd-5">
                                                        <div class="form-group">
                                                            <div class="group-s-control">
                                                                <label for="" class="label-top">Password</label>
                                                                <div class="l-form-hori">
                                                                    <input type="password" class="form-control" placeholder="" name="password">
                                                                    <div class="icon">
                                                                        <img src="{{ asset('frontend/images/login/4-1-lock-fill.svg') }}" alt="" class="icon-basic">
                                                                        <img src="{{ asset('frontend/images/login/4-2-lock-fill.svg') }}" alt="" class="icon-active">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 padd-5">
                                                        <div class="form-group">
                                                            <div class="group-s-control">
                                                                <label for="" class="label-top">Password confirmation</label>
                                                                <div class="l-form-hori">
                                                                    <input type="password" class="form-control" placeholder="" name="password_confirmation">
                                                                    <div class="icon">
                                                                        <img src="{{ asset('frontend/images/login/4-1-lock-fill.svg') }}" alt="" class="icon-basic">
                                                                        <img src="{{ asset('frontend/images/login/4-2-lock-fill.svg') }}" alt="" class="icon-active">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="row d-flex justify-content-end">
                                                    <div class="col-lg-6">
                                                        <button class="btn btn-bg-yellow d-block sm text-uppercase w-100">update</button>
                                                    </div>
                                                </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingThree">
                        <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            <div class="title">
                                <div class="icon">
                                    <img src="{{ asset('frontend/images/icon/12-3.svg') }}" alt="">
                                </div>
                                <div class="text">
                                    KYC Account
                                </div>
                            </div>
                        </button>
                    </div>
                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample" style="">
                        <div class="card-body">
                            <form action="{{ route('.kycAccount') }}" class="form-lg" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6">

                                        <div class="text-center title-form">
                                            INFORMATION
                                        </div>
                                        <div style="max-width: 480px;" class="m-auto">
                                            <div class="row marg-box-10">
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <div class="group-s-control active">
                                                            <label for="" class="label-top">Full Name</label>
                                                            <div class="l-form-hori">
                                                                <input type="text" class="form-control" placeholder="" name="name" value="{{ Auth::guard('web')->user()->name }}">
                                                                <div class="icon">
                                                                    <img src="{{ asset('frontend/images/form/1-1-balance-scale.svg') }}" alt="" class="icon-basic">
                                                                    <img src="{{ asset('frontend/images/form/1-2-balance-scale.svg') }}" alt="" class="icon-active">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <div class="group-s-control">
                                                            <label for="" class="label-top">ID/Passport Number</label>
                                                            <div class="l-form-hori">
                                                                @php
                                                                    $identityID = Auth::guard('web')->user()->userInfo->identity_card ?? null;
                                                                @endphp
                                                                <input type="text" class="form-control" placeholder="" name="identity_card" value="{{ $identityID }}">
                                                                <div class="icon">
                                                                    <img src="{{ asset('frontend/images/login/3-1-bxs-user.svg') }}" alt="" class="icon-basic">
                                                                    <img src="{{ asset('frontend/images/login/3-2-bxs-user.svg') }}" alt="" class="icon-active">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-lg-6">
                                        <div class="text-center title-form">
                                            Front ID/Passport with Selfier
                                        </div>
                                        <div class="upload">
                                            <div class="banner selectImg">
                                                @if(! empty(Auth::guard('web')->user()->userInfo->identity_card_selfie_img))
                                                    <img src="{{ asset('storage/upload/' . Auth::guard('web')->user()->userInfo->identity_card_selfie_img) }}" id="target_imgInp_3" alt="">
                                                @else
                                                    <img src="{{ asset('frontend/images/kyc/kyc-3.png') }}" id="target_imgInp_3" alt="">
                                                @endif
                                                <input type="file" name="identity_card_selfie_img" class="form-control" id="imgInp_3" style="display: none;" accept="image/gif, image/jpeg, image/png, image/jpg">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row text-center">
                                    <div class="col-lg-6">
                                        <div class="text-center title-form">
                                            Front ID/Passport Image
                                        </div>
                                        <div class="upload">
                                            <div class="banner selectImg">
                                                @if(! empty(Auth::guard('web')->user()->userInfo->identity_card_img))
                                                    <img src="{{ asset('storage/upload/' . Auth::guard('web')->user()->userInfo->identity_card_img) }}" id="target_imgInp_1" alt="">
                                                @else
                                                    <img src="{{ asset('frontend/images/kyc/kyc-1.png') }}" id="target_imgInp_1" alt="">
                                                @endif
                                                <input type="file" name="identity_card_img" class="form-control" id="imgInp_1" style="display: none;" accept="image/gif, image/jpeg, image/png, image/jpg">
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-lg-6">
                                        <div class="text-center title-form">
                                            Back ID/Passport Image
                                        </div>

                                        <div class="upload">
                                            <div class="banner selectImg">
                                                @if(! empty(Auth::guard('web')->user()->userInfo->identity_card_back_img))
                                                    <img src="{{ asset('storage/upload/' . Auth::guard('web')->user()->userInfo->identity_card_back_img) }}" id="target_imgInp_2" alt="">
                                                @else
                                                    <img src="{{ asset('frontend/images/kyc/kyc-2.png') }}" id="target_imgInp_2" alt="">
                                                @endif
                                                <input type="file" name="identity_card_back_img" class="form-control" id="imgInp_2" style="display: none;" accept="image/gif, image/jpeg, image/png, image/jpg">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="padding-top: 30px">
                                    <div class="col-lg-4"></div>

                                    <div class="col-lg-6 d-flex justify-content-end">
                                        @if(Auth::guard('web')->user()->kyc_status !== \App\Enums\KycStatusEnum::DECLINE)
                                            <button class="btn btn-bg-yellow d-block sm text-uppercase w-100">update</button>
                                        @else
                                            Please contact admin to support KYC.
                                        @endif
                                    </div>
                                    <div class="col-lg-4"></div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingTwo">
                        <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            <div class="title">
                                <div class="icon">
                                    <img src="{{ asset('frontend/images/icon/12-2.svg') }}" alt="">
                                </div>
                                <div class="text">
                                    2FA
                                </div>
                            </div>
                        </button>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="banner"><a href="#"><img src="{{ asset('frontend/images/icon/13-1.png') }}" alt=""></a></div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="m-auto" style="max-width: 470px;">
                                        <div class="text-center">
                                            <div class="title-form">
                                                Two-Factor Authentication
                                            </div>
                                            <div style="margin-bottom: 25px;">
                                                Two-factor authentication increases the security of your account
                                                All you need is a compatible app on your smartphone, for example:
                                                + Two-factor authentication
                                                + Authy
                                            </div>
                                        </div>
                                        <div class="block-qr-2fa">
                                            <div class="qr-img">
                                                <img src="{{ $inlineUrl }}">
                                            </div>
                                            <div class="qr-content">
                                                <label for="" class="label-main">or enter this secret key Into your device:</label>
                                                <div class="form-group">
                                                    <div class="control-icon-custom">
                                                        <input type="text" name="" class="form-control" value="{{ Auth::guard('web')->user()->secret_2fa }}" id="inputRef_2">
                                                    </div>
                                                </div>
                                                <div class="form-lg">
                                                    <button class="btn btn-bg-yellow d-block sm text-uppercase w-100" type="button" onclick="copyRef_2()">copy</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <form action="{{ route('.enable2FA') }}" method="POST">
                                                @csrf
                                                <div class="form-group">
                                                    <div class="group-s-control">
                                                        <label for="" class="label-top">Enter 2FA</label>
                                                        <div class="l-form-hori">
                                                            <input type="text" class="form-control" placeholder="" name="secret_2fa">
                                                            <div class="icon">
                                                                <img src="{{ asset('frontend/images/form/2-1.svg') }}" alt="" class="icon-basic">
                                                                <img src="{{ asset('frontend/images/form/2-2.svg') }}" alt="" class="icon-active">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="text-center">
                                                    @if (Auth::guard('web')->user()->is_lock_2fa)
                                                        <button class="btn btn-bg-yellow d-block sm text-uppercase w-100">Enable</button>
                                                    @else
                                                        <button class="btn btn-bg-yellow d-block sm text-uppercase w-100">Disable</button>
                                                    @endif
                                                </div>
                                            </form>
                                        </div>
                                        <div>
                                            <form action="{{ route('.forgot2FA') }}" method="POST">
                                                @csrf
                                                <div class="form-group">
                                                    <div class="group-s-control">
                                                        <label for="" class="label-top">Enter email</label>
                                                        <div class="l-form-hori">
                                                            <input type="text" class="form-control" placeholder="" name="email">
                                                            <div class="icon">
                                                                <img src="{{ asset('frontend/images/form/2-1.svg') }}" alt="" class="icon-basic">
                                                                <img src="{{ asset('frontend/images/form/2-2.svg') }}" alt="" class="icon-active">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="text-center">
                                                    <button class="btn btn-bg-yellow d-block sm text-uppercase w-100">Forgot 2FA</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingThree">
                        <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            <div class="title">
                                <div class="icon">
                                    <img src="{{ asset('frontend/images/icon/12-3.svg') }}" alt="">
                                </div>
                                <div class="text">
                                    Change Pass
                                </div>
                            </div>
                        </button>
                    </div>
                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                        <div class="card-body">
                                <div class="row"><script>
                                        $('.selectImg').on('click', function(e) {
                                            let input = $(this).children('input');
                                            let elementID = $(input).attr('id');
                                            $('#' + elementID)[0].click();
                                        })

                                        $('input[id^="imgInp_"]').change(function() {
                                            readPath(this)
                                        })
                                    </script>
                                    <div class="col-lg-6">
                                        <div class="banner"><a href="#"><img src="{{ asset('frontend/images/icon/form/14-1.png') }}" alt=""></a></div>
                                    </div>
                                    <div class="col-lg-6">
                                        <form action="{{ route('.changePassword') }}" method="POST">
                                            @csrf
                                        <div class="text-center title-form">
                                            Change Pass
                                        </div>
                                        <div style="max-width: 480px;" class="m-auto">
                                            <div class="row marg-box-10">
                                                <div class="col-lg-6 padd-5">
                                                    <div class="form-group">
                                                        <div class="group-s-control active">
                                                            <label for="" class="label-top">Email</label>
                                                            <div class="l-form-hori">
                                                                <input type="text" readonly class="form-control" placeholder="" name="email" value="{{ Auth::guard('web')->user()->email }}">
                                                                <div class="icon">
                                                                    <img src="{{ asset('frontend/images/form/1-1-balance-scale.svg') }}" alt="" class="icon-basic">
                                                                    <img src="{{ asset('frontend/images/form/1-2-balance-scale.svg') }}" alt="" class="icon-active">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 padd-5">
                                                    <div class="form-group">
                                                        <div class="group-s-control">
                                                            <label for="" class="label-top">Enter sponsor</label>
                                                            <div class="l-form-hori">
                                                                <input type="text" class="form-control" placeholder="" name="ref_id">
                                                                <div class="icon">
                                                                    <img src="{{ asset('frontend/images/login/3-1-bxs-user.svg') }}" alt="" class="icon-basic">
                                                                    <img src="{{ asset('frontend/images/login/3-2-bxs-user.svg') }}" alt="" class="icon-active">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 padd-5">
                                                    <div class="form-group">
                                                        <div class="group-s-control">
                                                            <label for="" class="label-top">Password</label>
                                                            <div class="l-form-hori">
                                                                <input type="password" class="form-control" placeholder="" name="password">
                                                                <div class="icon">
                                                                    <img src="{{ asset('frontend/images/login/4-1-lock-fill.svg') }}" alt="" class="icon-basic">
                                                                    <img src="{{ asset('frontend/images/login/4-2-lock-fill.svg') }}" alt="" class="icon-active">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 padd-5">
                                                    <div class="form-group">
                                                        <div class="group-s-control">
                                                            <label for="" class="label-top">Password confirmation</label>
                                                            <div class="l-form-hori">
                                                                <input type="password" class="form-control" placeholder="" name="password_confirmation">
                                                                <div class="icon">
                                                                    <img src="{{ asset('frontend/images/login/4-1-lock-fill.svg') }}" alt="" class="icon-basic">
                                                                    <img src="{{ asset('frontend/images/login/4-2-lock-fill.svg') }}" alt="" class="icon-active">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row d-flex justify-content-end">
                                                <div class="col-lg-6">
                                                    <button class="btn btn-bg-yellow d-block sm text-uppercase w-100">update</button>
                                                </div>
                                            </div>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom_js')

@endsection
