<?php

namespace Beam\Worm\Enums;

use MyCLabs\Enum\Enum;

class UserStatus extends Enum
{
    const PENDING = 'pending';

    const APPROVED = 'approved';

    const REJECTED = 'rejected';
}
