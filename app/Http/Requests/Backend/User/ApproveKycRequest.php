<?php

namespace App\Http\Requests\Backend\User;

use App\Enums\KycStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApproveKycRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'kyc_status' => [
                'required',
                Rule::in(KycStatusEnum::getValues())
            ],
            'decline_reason' => 'required_unless:kyc_status,' . KycStatusEnum::APPROVED
        ];
    }
}
