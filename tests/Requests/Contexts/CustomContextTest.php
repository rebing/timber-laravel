<?php

namespace Rebing\Timber\Tests\Requests\Contexts;

use Rebing\Timber\Requests\Contexts\CustomContext;
use Rebing\Timber\Tests\TestCase;

class CustomContextTest extends TestCase
{
    /**
     * @test
     */
    public function testCreatesANewCustomContextAndRetrievesData()
    {
        $key     = str_random();
        $data    = [
            'key' => 'value',
        ];
        $context = new CustomContext($key, $data);

        $expectedData = [
            'custom' => [
                $key => $data,
            ],
        ];
        $contextData  = $context->getData();
        $this->assertEquals($expectedData, $contextData);
    }
}