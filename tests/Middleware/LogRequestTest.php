<?php

namespace Rebing\Timber\Tests\Middleware;

use Illuminate\Http\Request;
use Rebing\Timber\Middleware\LogRequest;
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
        $request->merge($data);

        $middleware = new LogRequest();
        $middleware->handle($request, function($req) {});
    }
}