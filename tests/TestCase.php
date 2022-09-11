<?php

namespace Kanata\Forklift\Tests;

use Kanata\Forklift\ForkliftServiceProvider;
use CreateDirectoriesTable;
use CreateDocumentsTable;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            ForkliftServiceProvider::class,
            LivewireServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        include_once __DIR__ . '/Samples/database/migrations/2022_08_24_032608_create_directories_table.php';
        include_once __DIR__ . '/Samples/database/migrations/2022_08_24_032609_create_documents_table.php';
        (new CreateDirectoriesTable)->up();
        (new CreateDocumentsTable)->up();
    }
}
