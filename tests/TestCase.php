<?php

namespace Rebing\Timber\Tests;

use Rebing\Timber\Facades\Timber;
use Rebing\Timber\TimberServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [TimberServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Timber' => Timber::class,
        ];
    }
}