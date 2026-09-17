<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * Trạng thái tin liên hệ — khớp cột enum bảng tin_nhan_lien_he.
 */
final class TicketStatusEnum extends Enum
{
    const NEW = 'new';

    const READ = 'read';

    const REPLIED = 'replied';

    const CLOSED = 'closed';
}
