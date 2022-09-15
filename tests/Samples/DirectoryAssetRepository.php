<?php

namespace Kanata\Forklift\Tests\Samples;

use Kanata\Forklift\Interfaces\AssetRepositoryInterface;

class DirectoryAssetRepository implements AssetRepositoryInterface
{
    /**
     * @param int|null $moved_asset_id The directory of this form.
     * @param int|null $location_id The directory we are moving to.
     * @param int $page
     * @return array
     */
    public static function changeCurrentLocation(
        ?int $moved_asset_id,
        ?int $location_id,
        int $page = 1
    ): array {
        return self::changeCurrentDirectory(
            $moved_asset_id,
            $location_id,
            $page,
        );
    }

    /**
     * @param int $moved_asset_id
     * @param ?int $location_id
     * @return bool
     */
    public static function moveAsset(
        int $moved_asset_id,
        ?int $location_id,
    ): bool {
        $asset = DirectoryModelSample::find($moved_asset_id);
        $asset->parent = $location_id;
        $asset->parent = $location_id;
        return $asset->save();
    }

    public static function findLocation(?int $location_id): ?array
    {
        if (null === $location_id) {
            return null;
        }

        return DirectoryModelSample::find($location_id)?->toArray();
    }

    /**
     * @param int|null $moved_asset_id
     * @param int|null $location_id
     * @param int $page
     * @return array
     */
    protected static function changeCurrentDirectory(
        ?int $moved_asset_id,
        ?int $location_id,
        int $page = 1
    ): array {
        return DirectoryModelSample::query()
            ->where('parent', $location_id)
            ->whereNotIn('directories.id', [$moved_asset_id])
            ->paginate(5, ['id', 'title'], 'page', $page)
            ->toArray();
    }
}
