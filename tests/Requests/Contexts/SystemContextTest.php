<?php

namespace Rebing\Timber\Tests\Requests\Contexts;

use Rebing\Timber\Requests\Contexts\SystemContext;
use Rebing\Timber\Tests\TestCase;

class SystemContextTest extends TestCase
{
    /**
     * @test
     */
    public function testCreatesANewRequestEventAndGetsContextData()
    {
        $context = new SystemContext();

        $contextData = $context->getData();
        $expectedData = [
            'system' => [
                'hostname' => gethostname(),
                'ip'       => gethostbyname(gethostname()),
                'pid'      => getmypid(),
            ],
        ];
        $this->assertEquals($expectedData, $contextData);
    }
}