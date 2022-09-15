<?php

namespace Kanata\Forklift\Interfaces;

interface AssetRepositoryInterface
{
    public static function changeCurrentLocation(
        int $moved_asset_id,
        ?int $location_id,
        int $page = 1
    ): array;

    public static function moveAsset(
        int $moved_asset_id,
        ?int $location_id,
    ): bool;

    public static function findLocation(?int $location_id): ?array;
}
