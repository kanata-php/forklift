<?php

namespace Kanata\Forklift\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\View;
use Kanata\Forklift\Events\AssetMoved;
use Kanata\Forklift\ForkliftDropdown;
use Kanata\Forklift\Tests\Samples\AssetRepository;
use Kanata\Forklift\Tests\Samples\DirectoryModelSample;
use Kanata\Forklift\Tests\Samples\DocumentModelSample;
use Kanata\Forklift\Tests\TestCase;
use Livewire\Livewire;

class ForkliftDropdownTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected DirectoryModelSample|null $directory = null;
    protected DocumentModelSample|null $document = null;

    public function setUp(): void
    {
        parent::setUp();

        $this->directory = DirectoryModelSample::create([
            'title' => $this->faker->words(2, true),
            'parent' => null,
        ]);

        $this->document = DocumentModelSample::create([
            'title' => $this->faker->words(2, true),
            'content' => $this->faker->words(4, true),
            'directory_id' => null,
        ]);
    }

    /**
     * @covers \Kanata\Forklift\ForkliftDropdown
     */
    public function test_initial_location_id()
    {
        $currentLocationId = Livewire::test(ForkliftDropdown::class, [
            'currentLocationId' => null,
            'locationType' => DirectoryModelSample::class,
            'assetId' => $this->document->id,
            'assetType' => DocumentModelSample::class,
            'assetRepository' => AssetRepository::class,
        ])->get('currentLocationId');

        $this->assertNull($currentLocationId);
    }

    /**
     * @covers \Kanata\Forklift\ForkliftDropdown
     */
    public function test_change_location()
    {
        $currentLocationId = Livewire::test(ForkliftDropdown::class, [
            'currentLocationId' => null,
            'locationType' => DirectoryModelSample::class,
            'assetId' => $this->document->id,
            'assetType' => DocumentModelSample::class,
            'assetRepository' => AssetRepository::class,
        ])
            ->call('changeCurrentNavigation', $this->directory->id)
            ->get('currentLocationId');

        $this->assertEquals($this->directory->id, $currentLocationId);
    }

    /**
     * @covers \Kanata\Forklift\ForkliftDropdown
     * @covers \Kanata\Forklift\ForkliftDropdown::moveAsset
     */
    public function test_move_directory_asset()
    {
        $directory2 = DirectoryModelSample::create([
            'title' => $this->faker->words(2, true),
            'parent' => null,
        ]);

        $this->assertDatabaseMissing('directories', [
            'id' => $directory2->id,
            'parent' => $this->directory->id,
        ]);

        Livewire::test(ForkliftDropdown::class, [
            'currentLocationId' => null,
            'locationType' => DirectoryModelSample::class,
            'assetId' => $directory2->id,
            'assetType' => DirectoryModelSample::class,
            'assetRepository' => AssetRepository::class,
        ])
            ->call('changeCurrentNavigation', $this->directory->id)
            ->call('moveAsset', $this->directory->id)
            ->assertEmitted(AssetMoved::EVENT_NAME, [
                'asset_type' => DirectoryModelSample::class,
                'moved_asset_id' => $directory2->id,
                'location_type' => DirectoryModelSample::class,
                'location_id' => $this->directory->id,
            ]);

        $this->assertDatabaseHas('directories', [
            'id' => $directory2->id,
            'parent' => $this->directory->id,
        ]);
    }

    /**
     * @covers \Kanata\Forklift\ForkliftDropdown
     * @covers \Kanata\Forklift\ForkliftDropdown::moveAsset
     */
    public function test_move_document_asset()
    {
        $this->assertDatabaseMissing('documents', [
            'id' => $this->document->id,
            'directory_id' => $this->directory->id,
        ]);

        Livewire::test(ForkliftDropdown::class, [
            'currentLocationId' => null,
            'locationType' => DirectoryModelSample::class,
            'assetId' => $this->document->id,
            'assetType' => DocumentModelSample::class,
            'assetRepository' => AssetRepository::class,
            'parentField' => 'directory_id',
        ])
            ->call('changeCurrentNavigation', $this->directory->id)
            ->call('moveAsset', $this->directory->id)
            ->assertEmitted(AssetMoved::EVENT_NAME, [
                'asset_type' => DocumentModelSample::class,
                'moved_asset_id' => $this->document->id,
                'location_type' => DirectoryModelSample::class,
                'location_id' => $this->directory->id,
            ]);

        $this->assertDatabaseHas('documents', [
            'id' => $this->document->id,
            'directory_id' => $this->directory->id,
        ]);
    }

    /**
     * @covers \Kanata\Forklift\ForkliftDropdown
     */
    public function test_custom_template()
    {
        View::addNamespace('samples', 'tests/Samples/Views');

        Livewire::test(ForkliftDropdown::class, [
            'currentLocationId' => null,
            'locationType' => DirectoryModelSample::class,
            'assetId' => $this->document->id,
            'assetType' => DocumentModelSample::class,
            'assetRepository' => AssetRepository::class,
            'parentField' => 'directory_id',
            'template' => 'samples::test',
        ])->assertSee('test-template');
    }
}
