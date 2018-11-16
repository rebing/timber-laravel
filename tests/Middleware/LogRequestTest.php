<?php

namespace Rebing\Timber\Tests\Middleware;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Rebing\Timber\Middleware\LogRequest;
use Rebing\Timber\Requests\Events\HttpRequestEvent;
use Rebing\Timber\Requests\Events\HttpResponseEvent;
use Rebing\Timber\Tests\TestCase;

class LogRequestTest extends TestCase
{
    /**
     * @test
     */
    public function testSendsANewLogToTimberWIthRequest()
    {
        $request = new Request();
        $data = [
            'key' => 'value',
        ];
        $request->setMethod('POST');
        $request->merge($data);

        $this->expectsJobs([HttpRequestEvent::class]);
        $middleware = new LogRequest();
        $middleware->handle($request, function($req) {
            return new Response();
        });
    }

    /**
     * @group testing
     * @test
     */
    public function testSendsANewLogToTimberAndLogsResponse()
    {
        $request = new Request();
        $response = new Response();

        $this->expectsJobs([HttpRequestEvent::class, HttpResponseEvent::class]);
        $middleware = new LogRequest();
        $middleware->handle($request, function($req) {
            return new Response();
        });
        $middleware->terminate($request, $response);
    }
}