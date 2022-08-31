<?php

namespace Kanata\Forklift\Interfaces;

interface AssetRepositoryInterface
{
    public static function changeCurrentLocation(
        string $location_type,
        int $moved_asset_id,
        ?int $location_id,
        int $page = 1
    ): array;

    public static function moveAsset(
        string $asset_type,
        int $moved_asset_id,
        ?int $location_id,
    ): bool;
}
