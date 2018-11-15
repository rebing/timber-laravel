<?php

namespace Rebing\Timber\Tests\Requests\Events;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Rebing\Timber\Requests\Events\HttpEvent;
use Rebing\Timber\Requests\Events\HttpRequestEvent;
use Rebing\Timber\Tests\TestCase;

class HttpRequestEventTest extends TestCase
{
    /**
     * @test
     */
    public function testCreatesANewOutgoingRequestEventAndGetsTheMessage()
    {
        $request = new Request();

        $event = new HttpRequestEvent($request, HttpRequestEvent::DIRECTION_OUT);

        $message = "Sent GET /";
        $this->assertEquals($message, $event->getMessage());
    }

    /**
     * @test
     */
    public function testCreatesANewIncomingRequestEventAndGetsTheMessage()
    {
        $request = new Request();

        $event = new HttpRequestEvent($request, HttpRequestEvent::DIRECTION_IN);

        $message = "Received GET /";
        $this->assertEquals($message, $event->getMessage());
    }

    /**
     * @test
     */
    public function testCreatesANewRequestEventAndGetsEventData()
    {
        $request = new Request();

        $event = new HttpRequestEvent($request, HttpRequestEvent::DIRECTION_OUT);

        $eventData    = $event->getEvent();
        $expectedData = [
            'http_request' => [
                'headers'      => [],
                'host'         => '',
                'method'       => 'GET',
                'path'         => '/',
                'port'         => null,
                'query_string' => null,
                'scheme'       => 'http',
                'request_id'   => Session::get(HttpEvent::SESSION_REQUEST_KEY),
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

        $event = new HttpRequestEvent($request, HttpRequestEvent::DIRECTION_OUT);

        $contextData  = $event->getContext();
        $expectedData = [
            'http'   => [
                'method'      => 'GET',
                'path'        => '/',
                'remote_addr' => null,
                'request_id'  => Session::get(HttpEvent::SESSION_REQUEST_KEY),
            ],
            'system' => [
                'hostname'     => gethostname(),
                'ip'           => gethostbyname(gethostname()),
                'pid'          => getmypid(),
            ],
            'user'   => [],
        ];
        $this->assertEquals($expectedData, $contextData);
    }
}