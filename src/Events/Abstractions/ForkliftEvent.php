<?php

namespace Kanata\Forklift\Events\Abstractions;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * @codeCoverageIgnore
 */
abstract class ForkliftEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public string $asset_type,
        public int $moved_asset_id,
        public string $location_type,
        public ?int $location_id,
    ) { }

    public function toArray(): array
    {
        return [
            'asset_type' => $this->asset_type,
            'moved_asset_id' => $this->moved_asset_id,
            'location_type' => $this->location_type,
            'location_id' => $this->location_id,
        ];
    }
}
