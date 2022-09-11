<?php

namespace Kanata\Forklift\Tests\Samples;

use Kanata\Forklift\Interfaces\AssetRepositoryInterface;

class AssetRepository implements AssetRepositoryInterface
{
    /**
     * @param string $location_type Model class name.
     * @param int|null $moved_asset_id The directory of this form.
     * @param int|null $location_id The directory we are moving to.
     * @param int $page
     * @return array
     */
    public static function changeCurrentLocation(
        string $location_type,
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
     * @param string $asset_type
     * @param int $moved_asset_id
     * @param ?int $location_id
     * @param string $parentField
     * @return bool
     */
    public static function moveAsset(
        string $asset_type,
        int $moved_asset_id,
        ?int $location_id,
        string $parent_field = 'parent',
    ): bool {
        $asset = $asset_type::find($moved_asset_id);
        $asset->$parent_field = $location_id;
        return $asset->save();
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
