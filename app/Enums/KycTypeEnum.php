<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class KycTypeEnum extends Enum
{
    const EMAIL_SIGN_UP = 'email_sign_up';
    const EMAIL_FORGOT_PASSWORD = 'email_forgot_password';
    const LOST_PASS = 'lost_pass';
    const LOST_2FA = 'lost_2fa';
    const WALLET = 'wallet';
}
