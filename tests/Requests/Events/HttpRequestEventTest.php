<?php

namespace Rebing\Timber\Tests\Requests\Events;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Rebing\Timber\Requests\Events\HttpRequestEvent;
use Rebing\Timber\Requests\RequestIdTrait;
use Rebing\Timber\Tests\TestCase;

class HttpRequestEventTest extends TestCase
{
    /**
     * @test
     */
    public function testCreatesANewOutgoingRequestEventAndGetsTheMessage()
    {
        $request     = new Request();
        $serviceName = str_random();

        $event = new HttpRequestEvent($request, true, $serviceName);

        $message = "Sent GET / to $serviceName";
        $this->assertEquals($message, $event->getMessage());
    }

    /**
     * @test
     */
    public function testCreatesANewIncomingRequestEventAndGetsTheMessage()
    {
        $request = new Request();

        $event = new HttpRequestEvent($request, false);

        $message = "Received GET /";
        $this->assertEquals($message, $event->getMessage());
    }

    /**
     * @test
     */
    public function testCreatesANewRequestEventAndGetsEventData()
    {
        $request = new Request();
        $body    = ['key' => 'value'];
        $request->merge($body);
        $direction = HttpRequestEvent::DIRECTION_OUT;

        $event = new HttpRequestEvent($request, true);

        $eventData    = $event->getEvent();
        $reqId        = Session::get(RequestIdTrait::getRequestSessionKey());
        $expectedData = [
            'http_request' => [
                'method'     => 'GET',
                'path'       => '/',
                'scheme'     => 'http',
                'request_id' => $reqId,
                'direction'  => $direction,
                'body'       => json_encode($body),
                'headers'    => [
                    'x-request-id' => [
                        $reqId,
                    ],
                ],
            ],
        ];
        $this->assertEquals($expectedData, $eventData);
    }

    /**
     * @test
     */
    public function testCreatesANewRequestEventAndGetsContextData()
    {
        $request = new Request();

        $event = new HttpRequestEvent($request, true);

        $contextData  = $event->getContext();
        $expectedData = [
            'http'   => [
                'method'      => 'GET',
                'path'        => '/',
                'remote_addr' => request()->ip(),
                'request_id'  => Session::get(RequestIdTrait::getRequestSessionKey()),
            ],
            'system' => [
                'hostname' => gethostname(),
                'ip'       => gethostbyname(gethostname()),
                'pid'      => getmypid(),
            ],
        ];
        $this->assertEquals($expectedData, $contextData);
    }

    /**
     * @test
     */
    public function testCreatesANewRequestEventAndGetsTheRequestId()
    {
        $request = new Request();

        $event = new HttpRequestEvent($request, true);

        $requestId = $event->getRequestId();
        $this->assertNotNull($requestId);
    }
}