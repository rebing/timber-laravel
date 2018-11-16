<?php

namespace Rebing\Timber\Tests\Requests\Contexts;

use Illuminate\Support\Facades\Session;
use Rebing\Timber\Requests\Contexts\HttpContext;
use Rebing\Timber\Requests\RequestIdTrait;
use Rebing\Timber\Tests\TestCase;

class HttpContextTest extends TestCase
{
    /**
     * @test
     */
    public function testCreatesANewRequestEventAndGetsContextData()
    {
        $context = new HttpContext();

        $contextData = $context->getData();
        $expectedData = [
            'http'   => [
                'method'      => 'GET',
                'path'        => '/',
                'remote_addr' => request()->ip(),
                'request_id'  => Session::get(RequestIdTrait::getRequestSessionKey()),
            ],
        ];
        $this->assertEquals($expectedData, $contextData);
    }
}