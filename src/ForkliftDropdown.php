<?php

namespace Kanata\Forklift;

use Kanata\Forklift\Events\AssetMoved;
use Kanata\Forklift\Events\AssetMoveFailed;
use Livewire\Component;

class ForkliftDropdown extends Component
{
    /**
     * @var int|null
     */
    public ?int $assetBeingMoved = null;

    /**
     * Id of the Asset that hold assets that we are moving around.
     *
     * @var ?int
     */
    public ?int $currentLocationId = null;

    /**
     * Class name of the asset type.
     *
     * @var string|null
     */
    public ?string $assetType = null;

    /**
     * Class name of the location type.
     *
     * @var string|null
     */
    public ?string $locationType = null;

    /**
     * @var string AssetRepositoryInterface
     */
    public string $assetRepository;

    /**
     * Template name to be loaded for the presentation.
     *
     * @var string
     */
    public string $template;

    /**
     * @important Locations are expected to have "$location->id" as record id.
     * @important Locations are expected to have "$location->title" as the record name.
     * @var array
     */
    public array $locations = [];
    public int $page = 1;

    /**
     * Location before navigation
     *
     * @var ?string
     */
    public ?string $previousLocation;

    /**
     * @param int|null $currentLocationId Location where the asset is now.
     * @param string $locationType Location model type (class name).
     * @param int $assetId Asset being moved id.
     * @param string $assetType Asset model type (class name).
     * @param string $assetRepository Repository to run procedures.
     * @param string $template Template's name to be loaded.
     * @return void
     */
    public function mount(
        ?int $currentLocationId,
        string $locationType,
        int $assetId,
        string $assetType,
        string $assetRepository,
        string $template = 'default',
    ) {
        $this->assetRepository = $assetRepository;
        $this->template = $template;

        // TODO: if this fail, show on UI that the component is not functional
        //       right now we are throwing an exception.
        $this->checkLocationType($locationType);
        $this->locationType = $locationType;
        $previousLocation = $this->assetRepository::findLocation($this->currentLocationId);
        $this->previousLocation = $previousLocation['title'] ?? null;

        // TODO: if this fail, show on UI that the component is not functional
        //       right now we are throwing an exception.
        $this->checkAssetType($assetType);
        $this->assetType = $assetType;

        $this->assetBeingMoved = $assetId;
        $this->currentLocationId = $currentLocationId;
        $this->changeCurrentNavigation($this->currentLocationId);
    }

    protected function checkAssetType(string $assetType)
    {
        // TODO: check if assetType has necessary interface:
        // - find
    }

    protected function checkLocationType(string $locationType)
    {
        // TODO: check if assetType has necessary interface:
        // - find
    }

    /**
     * Navigate to a new directory.
     *
     * @param ?int $locationId
     * @param int $page
     * @return void
     */
    public function changeCurrentNavigation(
        ?int $locationId,
        int $page = 1
    ): void {
        $this->currentLocationId = $locationId;

        /**
         * @important $location expected to have "$location->id" as the id of the element.
         * @important $location expected to have "$location->parent" as the parent element.
         * @important $location expected to have "$location->title" as the name of the element.
         */
        $this->locations = $this->assetRepository::changeCurrentLocation(
            moved_asset_id: $this->assetBeingMoved,
            location_id: $this->currentLocationId,
            page: $page,
        );
    }

    /**
     * Move element to the new place.
     *
     * @return void
     */
    public function moveAsset() {
        $result = $this->assetRepository::moveAsset(
            moved_asset_id: $this->assetBeingMoved,
            location_id: $this->currentLocationId,
        );

        if ($result) {
            $previousLocation = $this->assetRepository::findLocation($this->currentLocationId);
            $this->previousLocation = $previousLocation['title'] ?? null;
            $this->dispatchForkliftEvent(AssetMoved::class);
            return;
        }

        $this->dispatchForkliftEvent(AssetMoveFailed::class);
    }

    private function dispatchForkliftEvent(string $event)
    {
        // laravel event
        $event::dispatch(
            $this->assetType,
            $this->assetBeingMoved,
            $this->locationType,
            $this->currentLocationId,
        );

        // livewire events
        $eventData = [
            'asset_type' => $this->assetType,
            'moved_asset_id' => $this->assetBeingMoved,
            'location_type' => $this->locationType,
            'location_id' => $this->currentLocationId,
        ];
        $this->emit($event::EVENT_NAME, $eventData);
        $this->dispatchBrowserEvent($event::EVENT_NAME, $eventData);
    }

    public function render()
    {
        /**
         * @important $asset expected to have "$asset->id" as the id of the element.
         * @important $asset expected to have "$asset->parent" as the parent element.
         * @important $asset expected to have "$asset->title" as the name of the element.
         */
        $location = $this->assetRepository::findLocation($this->currentLocationId);

        return view('default' === $this->template ? 'forklift::forklift-dropdown' : $this->template, [
            'location' => $location,
        ]);
    }
}
