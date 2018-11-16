<?php

namespace Rebing\Timber\Tests\Requests\Contexts;

use Rebing\Timber\Requests\Contexts\UserContext;
use Rebing\Timber\Tests\TestCase;

class UserContextTest extends TestCase
{
    /**
     * @test
     */
    public function testCreatesANewRequestWithoutAUserAndRetrievesNull()
    {
        $context = new UserContext();

        $this->assertEmpty($context->getData());
    }
}