<?php

namespace Rebing\Timber\Tests;

use Rebing\Timber\Timber;

class TimberTest extends TestCase
{

    /**
     * @expectedException Rebing\Timber\Exceptions\TimberException
     */
    public function testEmptyApiKey()
    {
        config()->set('timber.api_key', null);

        $timber = new Timber();
    }

}