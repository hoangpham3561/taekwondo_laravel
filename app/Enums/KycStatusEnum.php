<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class KycStatusEnum extends Enum
{
    // Kyc email status
    const SENT = 'sent';
    const ACTIVATED = 'activated';
    const EXPIRED = 'expired';

    // Kyc status
    const APPROVED = 'Y';
    const NOT_KYC = 'N';
    const CANCEL = 'C';
    const DECLINE = 'D';

}
