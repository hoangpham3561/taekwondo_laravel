@extends('layout.system.backend')
@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-2">
                        User Edit
                    </h1>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="javascript:void(0)">App</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            User
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            Edit
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="content">
        @include('layout.system.partials.message')
        <div class="block block-rounded p-3">
            <div class="block-header block-header-default">
                <h3 class="block-title">User KYC Detail</h3>
            </div>
            <div class="block-content block-content-full">
                <div class="row">
                    <div class="col-lg-8 space-y-5">
                        <form action="{{ route('admin.users.saveKycDetail', ['id' => $data['id']]) }}" method="POST">
                            @csrf
                            <div class="row push">
                                <div class="col-lg-4">

                                </div>
                                <div class="col-lg-8 col-xl-5">
                                    <div class="mb-4">
                                        <label class="form-label" for="example-text-input">Username</label><br/>
                                        @if(!empty($data['user_info']['identity_card_img']))
                                            <img width="300" src="{{ asset('storage/upload/' . $data['user_info']['identity_card_img']) }}" />
                                        @else
                                            <img src="{{ asset('frontend/images/kyc/kyc-1.png') }}" />
                                        @endif
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="example-email-input">Email</label><br/>
                                        @if(!empty($data['user_info']['identity_card_back_img']))
                                            <img width="300" src="{{ asset('storage/upload/' . $data['user_info']['identity_card_back_img']) }}" />
                                        @else
                                            <img src="{{ asset('frontend/images/kyc/kyc-2.png') }}" />
                                        @endif
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="example-text-input">Fullname</label><br/>
                                        @if(!empty($data['user_info']['identity_card_selfie_img']))
                                            <img width="300" src="{{ asset('storage/upload/' . $data['user_info']['identity_card_selfie_img']) }}" />
                                        @else
                                            <img src="{{ asset('frontend/images/kyc/kyc-3.png') }}" />
                                        @endif
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="example-select">Status</label>
                                        @php
                                            $statusName = '';
                                        @endphp
                                        @if(!empty($kycStatus))
                                            <select class="form-select" id="example-select" name="kyc_status">
                                                <option selected>Please select</option>
                                                @foreach($kycStatus as $key => $status)
                                                    <option value="{{ $status }}" @if($status === $data['kyc_status']) selected @endif>
                                                        @if($status == \App\Enums\KycStatusEnum::APPROVED) {{ $statusName = 'Approve' }} @endif
                                                        @if($status == \App\Enums\KycStatusEnum::CANCEL) {{ $statusName = 'Cancel' }} @endif
                                                        @if($status == \App\Enums\KycStatusEnum::DECLINE) {{ $statusName = 'Decline' }} @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="example-text-input">Decline reason</label>
                                        <textarea class="form-control" id="example-text-input" name="decline_reason" rows="5">{{ $data['decline_reason'] }}</textarea>
                                    </div>
                                    <div class="mb-4">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
