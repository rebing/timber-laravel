<?php

namespace Rebing\Timber\Tests;

use Rebing\Timber\Facades\Timber;
use Rebing\Timber\TimberServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    const TEST_API_KEY = '5709_a436ccec0cb1c2ee:36de282b49f1e918caa5e84c8f87c41049e9764863565f48887f5ee896e6a83d';

    public function setUp()
    {
        parent::setUp();

        config()->set('timber.api_key', self::TEST_API_KEY);
    }

    protected function getPackageProviders($app)
    {
        return [
            TimberServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Timber' => Timber::class,
        ];
    }
}