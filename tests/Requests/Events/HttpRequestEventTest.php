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

        $event = new HttpRequestEvent($request, HttpEvent::DIRECTION_OUT);

        $message = "Sent GET /";
        $this->assertEquals($message, $event->getMessage());
    }

    /**
     * @test
     */
    public function testCreatesANewIncomingRequestEventAndGetsTheMessage()
    {
        $request = new Request();

        $event = new HttpRequestEvent($request, HttpEvent::DIRECTION_IN);

        $message = "Received GET /";
        $this->assertEquals($message, $event->getMessage());
    }

    /**
     * @test
     */
    public function testCreatesANewRequestEventAndGetsEventData()
    {
        $request = new Request();
        $direction = HttpRequestEvent::DIRECTION_OUT;

        $event = new HttpRequestEvent($request, $direction);

        $eventData = $event->getEvent();
        $expectedData = [
            'http_request' => [
                'method'       => 'GET',
                'path'         => '/',
                'scheme'       => 'http',
                'request_id'   => Session::get(HttpEvent::SESSION_REQUEST_KEY),
                'direction'    => $direction,
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

        $event = new HttpRequestEvent($request, HttpEvent::DIRECTION_OUT);

        $contextData = $event->getContext();
        $expectedData = [
            'http'   => [
                'method'      => 'GET',
                'path'        => '/',
                'remote_addr' => request()->ip(),
                'request_id'  => Session::get(HttpEvent::SESSION_REQUEST_KEY),
            ],
            'system' => [
                'hostname' => gethostname(),
                'ip'       => gethostbyname(gethostname()),
                'pid'      => getmypid(),
            ],
        ];
        $this->assertEquals($expectedData, $contextData);
    }
}