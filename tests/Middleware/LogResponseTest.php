<?php

namespace Rebing\Timber\Tests\Middleware;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Rebing\Timber\Middleware\LogResponse;
use Rebing\Timber\Requests\Events\HttpResponseEvent;
use Rebing\Timber\Tests\TestCase;

class LogResponseTest extends TestCase
{
    /**
     * @test
     */
    public function testSendsANewLogToTimberWIthRequest()
    {
        $request = new Request();

        $this->expectsJobs(HttpResponseEvent::class);
        $middleware = new LogResponse();
        $middleware->handle($request, function($req) {
            return new Response();
        });
    }
}