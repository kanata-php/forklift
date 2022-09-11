<?php

namespace Kanata\Forklift\Events;

use Kanata\Forklift\Events\Abstractions\ForkliftEvent;

class AssetMoved extends ForkliftEvent
{
    const EVENT_NAME = 'asset-moved';
}
