<?php

namespace Kanata\Forklift\Events;

use Kanata\Forklift\Events\Abstractions\ForkliftEvent;

class AssetMoveFailed extends ForkliftEvent
{
    const EVENT_NAME = 'asset-move-failed';
}
