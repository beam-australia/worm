<?php

namespace Beam\Worm\Enums;

use MyCLabs\Enum\Enum;

class PostStatus extends Enum
{
    const PENDING = 'pending';

    const PUBLISHED = 'publish';

    const DRAFT = 'draft';
}
