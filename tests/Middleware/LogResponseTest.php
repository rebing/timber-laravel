<?php

namespace Rebing\Timber\Tests\Middleware;

use Illuminate\Http\Request;
use Rebing\Timber\Middleware\LogResponse;
use Rebing\Timber\Tests\TestCase;

class LogResponseTest extends TestCase
{
    /**
     * @group testing
     * @test
     */
    public function testSendsANewLogToTimberWIthRequest()
    {
        $request = new Request();

        $middleware = new LogResponse();
        $response = $middleware->handle($request, function($req) {});

        $this->assertNull($response);
    }
}