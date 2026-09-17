@extends('layouts.frontend.frontend')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="block-manual">

                <div class="list-item-total-main">
                    <div class="row">
                        <div class="col-lg-6 col-sm-6">
                            <div class="item-total-main">
                                <div class="position-relative">
                                    <div class="title-top">
                                        <div class="title text-uppercase">Ai Trade</div>
                                        <div class="icon">
                                            <img src="{{ asset('frontend/images/icon/2-1.svg') }}" alt="">
                                        </div>
                                    </div>
                                    <div class="content">
                                        <div class="value">100.00</div>
                                        <div>
                                            26,4%
                                            <span><img src="{{ asset('frontend/images/icon/5-2-down.svg') }}" alt=""></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-inner"><img src="{{ asset('frontend/images/icon/5-1-bg.jpg') }}" alt=""></div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6">
                            <div class="item-total-main">
                                <div class="position-relative">
                                    <div class="title-top">
                                        <div class="title text-uppercase">Manual Trade</div>
                                        <div class="icon">
                                            <img src="{{ asset('frontend/images/icon/3-1.svg') }}" alt="">
                                        </div>
                                    </div>
                                    <div class="content">

                                        <div class="value">100.00</div>
                                    </div>
                                </div>
                                <div class="bg-inner"><img src="{{ asset('frontend/images/icon/6-1-bg.jpg') }}" alt=""></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="list-nav-block">
                    <a href="#" class="link-icon">
                        <div class="item">
                            <div class="icon-thumb-icon">
                                <div class="basice"><img src="{{ asset('frontend/images/icon/9-1.svg') }}" alt=""></div>
                                <div class="hover"><img src="{{ asset('frontend/images/icon/9-2.svg') }}" alt=""></div>
                            </div>
                            <div class="text"> Demo Account</div>
                        </div>
                    </a>
                    <a href="#" class="link-icon active">
                        <div class="item">
                            <div class="icon-thumb-icon">
                                <div class="basice"><img src="{{ asset('frontend/images/icon/9-1.svg') }}" alt=""></div>
                                <div class="hover"><img src="{{ asset('frontend/images/icon/9-2.svg') }}" alt=""></div>
                            </div>
                            <div class="text"> Live Account</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="block-bg-sm block-wallet custom-nav" style="min-height: 425px;">
                <div class="block-wallet-main">
                    <div class="w-nav-left">
                        <div class="row marg-box-10">
                            <div class="col-4 padd-5">
                                <button type="button" class="btn-check-show active" name="id2deposit">
                                    <div class="item">
                                        <div class="icon-thumb-icon">
                                            <div class="basice"><img src="{{ asset('frontend/images/icon/7-1.svg') }}" alt=""></div>
                                            <div class="hover"><img src="{{ asset('frontend/images/icon/7-1-2.svg') }}" alt=""></div>
                                        </div>
                                        <div class="text">Deposit</div>
                                    </div>
                                </button>
                            </div>
                            <div class="col-4 padd-5">
                                <button type="button" class="btn-check-show" name="id2withdraw">
                                    <div class="item">
                                        <div class="icon-thumb-icon">
                                            <div class="basice"><img src="{{ asset('frontend/images/icon/7-3.svg') }}" alt=""></div>
                                            <div class="hover"><img src="{{ asset('frontend/images/icon/7-3-2.svg') }}" alt=""></div>
                                        </div>
                                        <div class="text">Withdraw</div>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="w-content">
                        <div class="card-action-form" id="card-id2deposit" style="display: block;">
                            <form action="" class="form-lg">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="banner-main">
                                            <a href="#"><img src="{{ asset('frontend/images/icon/8-5.png') }}" alt=""></a>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="text-center title-form">
                                            DEPOSIT TO LIVE ACCOUNT
                                        </div>
                                        <div style="max-width: 480px;" class="m-auto">
                                            <div class="row marg-box-10">
                                                <div class="col-lg-6 padd-5">
                                                    <div class="form-group">
                                                        <div class="group-s-control active">
                                                            <label for="" class="label-top">E-wallet Payment</label>
                                                            <div class="l-form-hori">
                                                                <input type="text" class="form-control" placeholder="">
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
                                                            <label for="" class="label-top">Amount</label>
                                                            <div class="l-form-hori">
                                                                <input type="text" class="form-control" placeholder="">
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
                                                            <label for="" class="label-top">Account receiver</label>
                                                            <div class="l-form-hori">
                                                                <input type="password" class="form-control" placeholder="">
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
                                                        <div class="group-s-control group-captcha">
                                                            <label for="" class="label-top">CAPTCHA</label>
                                                            <div class="l-form-hori">
                                                                <input type="password" class="form-control" placeholder="">
                                                                <div class="text">
                                                                    EXLAI
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row d-flex justify-content-end">
                                                <div class="col-lg-6">
                                                    <button class="btn btn-bg-yellow d-block sm text-uppercase w-100">WITHDRAW</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-action-form" id="card-id2withdraw" style="display: none;">
                            <form action="" class="form-lg">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="banner-main">
                                            <a href="#"><img src="{{ asset('frontend/images/icon/8-3.png') }}" alt=""></a>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="text-center title-form">
                                            WITHDRAW TO E-WALLET
                                        </div>
                                        <div style="max-width: 480px;" class="m-auto">
                                            <div class="row marg-box-10">
                                                <div class="col-lg-6 padd-5">
                                                    <div class="form-group">
                                                        <div class="group-s-control active">
                                                            <label for="" class="label-top">Amount</label>
                                                            <div class="l-form-hori">
                                                                <input type="text" class="form-control" placeholder="">
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
                                                            <label for="" class="label-top">E-wallet receive</label>
                                                            <div class="l-form-hori">
                                                                <input type="text" class="form-control" placeholder="">
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
                                                        <div class="group-s-control group-captcha">
                                                            <label for="" class="label-top">CAPTCHA</label>
                                                            <div class="l-form-hori">
                                                                <input type="password" class="form-control" placeholder="">
                                                                <div class="text">
                                                                    EXLAI
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 padd-5">
                                                    <div class="lable-none">
                                                        <label for="">Button</label>
                                                    </div>
                                                    <button class="btn btn-bg-yellow d-block sm text-uppercase w-100">WITHDRAW</button>
                                                </div>
                                            </div>
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
    <div class="row">
        <div class="col-lg-12">
            <div class="block-bg-sm card-history-form" id="history-id2deposit" style="display: block;">
                <div class="b-title">
                    <div class="icon"><img src="{{ asset('frontend/images/icon/6-1-history.svg') }}" alt=""></div>
                    <div class="text">History Deposit</div>
                </div>
                <div class="table-responsive">
                    <table class="table table-separate table-striped ">
                        <thead class="">
                        <tr class="">
                            <th>Order ID</th>
                            <th>Datetime</th>
                            <th>Coins</th>
                            <th>Option Type</th>
                            <th>Open</th>
                            <th>Close</th>
                            <th>Trade Amount</th>
                            <th>Payout</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>011111#</td>
                            <td>04-08-2021</td>
                            <td>BTC</td>
                            <td>30.000$</td>
                            <td>Open</td>
                            <td>Close</td>
                            <td>40.0</td>
                            <td>Payout</td>
                        </tr>
                        <tr>
                            <td>011111#</td>
                            <td>04-08-2021</td>
                            <td>BTC</td>
                            <td>30.000$</td>
                            <td>Open</td>
                            <td>Close</td>
                            <td>40.0</td>
                            <td>Payout</td>
                        </tr>
                        <tr>
                            <td>011111#</td>
                            <td>04-08-2021</td>
                            <td>BTC</td>
                            <td>30.000$</td>
                            <td>Open</td>
                            <td>Close</td>
                            <td>40.0</td>
                            <td>Payout</td>
                        </tr>
                        <tr>
                            <td>011111#</td>
                            <td>04-08-2021</td>
                            <td>BTC</td>
                            <td>30.000$</td>
                            <td>Open</td>
                            <td>Close</td>
                            <td>40.0</td>
                            <td>Payout</td>
                        </tr>
                        <tr>
                            <td>011111#</td>
                            <td>04-08-2021</td>
                            <td>BTC</td>
                            <td>30.000$</td>
                            <td>Open</td>
                            <td>Close</td>
                            <td>40.0</td>
                            <td>Payout</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="block-bg-sm card-history-form" id="history-id2transfer" style="display: none;">
                <div class="b-title">
                    <div class="icon"><img src="{{ asset('frontend/images/icon/6-1-history.svg') }}" alt=""></div>
                    <div class="text">History Transfer</div>
                </div>
                <div class="table-responsive">
                    <table class="table table-separate table-striped ">
                        <thead class="">
                        <tr class="">
                            <th>Order ID</th>
                            <th>Datetime</th>
                            <th>Coins</th>
                            <th>Option Type</th>
                            <th>Open</th>
                            <th>Close</th>
                            <th>Trade Amount</th>
                            <th>Payout</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>011111#</td>
                            <td>04-08-2021</td>
                            <td>BTC</td>
                            <td>30.000$</td>
                            <td>Open</td>
                            <td>Close</td>
                            <td>40.0</td>
                            <td>Payout</td>
                        </tr>
                        <tr>
                            <td>011111#</td>
                            <td>04-08-2021</td>
                            <td>BTC</td>
                            <td>30.000$</td>
                            <td>Open</td>
                            <td>Close</td>
                            <td>40.0</td>
                            <td>Payout</td>
                        </tr>
                        <tr>
                            <td>011111#</td>
                            <td>04-08-2021</td>
                            <td>BTC</td>
                            <td>30.000$</td>
                            <td>Open</td>
                            <td>Close</td>
                            <td>40.0</td>
                            <td>Payout</td>
                        </tr>
                        <tr>
                            <td>011111#</td>
                            <td>04-08-2021</td>
                            <td>BTC</td>
                            <td>30.000$</td>
                            <td>Open</td>
                            <td>Close</td>
                            <td>40.0</td>
                            <td>Payout</td>
                        </tr>
                        <tr>
                            <td>011111#</td>
                            <td>04-08-2021</td>
                            <td>BTC</td>
                            <td>30.000$</td>
                            <td>Open</td>
                            <td>Close</td>
                            <td>40.0</td>
                            <td>Payout</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="block-bg-sm card-history-form" id="history-id2withdraw" style="display: none;">
                <div class="b-title">
                    <div class="icon"><img src="{{ asset('frontend/images/icon/6-1-history.svg') }}" alt=""></div>
                    <div class="text">History Withdraw</div>
                </div>
                <div class="table-responsive">
                    <table class="table table-separate table-striped ">
                        <thead class="">
                        <tr class="">
                            <th>Order ID</th>
                            <th>Datetime</th>
                            <th>Coins</th>
                            <th>Option Type</th>
                            <th>Open</th>
                            <th>Close</th>
                            <th>Trade Amount</th>
                            <th>Payout</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>011111#</td>
                            <td>04-08-2021</td>
                            <td>BTC</td>
                            <td>30.000$</td>
                            <td>Open</td>
                            <td>Close</td>
                            <td>40.0</td>
                            <td>Payout</td>
                        </tr>
                        <tr>
                            <td>011111#</td>
                            <td>04-08-2021</td>
                            <td>BTC</td>
                            <td>30.000$</td>
                            <td>Open</td>
                            <td>Close</td>
                            <td>40.0</td>
                            <td>Payout</td>
                        </tr>
                        <tr>
                            <td>011111#</td>
                            <td>04-08-2021</td>
                            <td>BTC</td>
                            <td>30.000$</td>
                            <td>Open</td>
                            <td>Close</td>
                            <td>40.0</td>
                            <td>Payout</td>
                        </tr>
                        <tr>
                            <td>011111#</td>
                            <td>04-08-2021</td>
                            <td>BTC</td>
                            <td>30.000$</td>
                            <td>Open</td>
                            <td>Close</td>
                            <td>40.0</td>
                            <td>Payout</td>
                        </tr>
                        <tr>
                            <td>011111#</td>
                            <td>04-08-2021</td>
                            <td>BTC</td>
                            <td>30.000$</td>
                            <td>Open</td>
                            <td>Close</td>
                            <td>40.0</td>
                            <td>Payout</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom_js')
    <script>

        $(document).ready(function (e) {
            $(".block-language select.form-control").msDropdown({
                visibleRows: 3
            });
        });

        //  ---- 2
        $(".btn-check-show").click(function(){
            $('.btn-check-show').removeClass('active');
            $(this).addClass('active');
        });
        $(document).ready(function(){
            $(".btn-check-show").on('click', function(e) {
                var optionValue_2 = $(this).attr("name");
                if(optionValue_2){
                    $(".card-action-form").not("." + optionValue_2).hide();
                    $("#card-" + optionValue_2).show();
                    $(".card-history-form").not("." + optionValue_2).hide();
                    $("#history-" + optionValue_2).show();
                    console.log();
                } else{
                    $(".card-action-form").hide();
                    $(".card-history-form").hide();
                }
            }).change();
        });

    </script>
@endsection
