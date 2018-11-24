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
        $request = new Request();
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
        $body = ['key' => 'value'];
        $request->merge($body);
        $direction = HttpRequestEvent::DIRECTION_OUT;

        $event = new HttpRequestEvent($request, true);

        $eventData = $event->getEvent();
        $expectedData = [
            'http_request' => [
                'method'     => 'GET',
                'path'       => '/',
                'scheme'     => 'http',
                'request_id' => Session::getId(),
                'direction'  => $direction,
                'body'       => json_encode($body),
            ],
        ];
        $this->assertEquals($expectedData, $eventData);
    }

    /**
     * @test
     */
    public function testCreatesANewOutgoingGuzzleRequestAndGetsEventData()
    {
        $body = ['key' => 'value'];
        $method = 'POST';
        $request = new \GuzzleHttp\Psr7\ServerRequest($method, 'http://some.url/', [], json_encode($body));
        $direction = HttpRequestEvent::DIRECTION_OUT;

        $event = new HttpRequestEvent($request, true);

        $eventData = $event->getEvent();
        $expectedData = [
            'http_request' => [
                'method'         => $method,
                'host'           => 'some.url',
                'path'           => '/',
                'scheme'         => 'http',
                'request_id'     => Session::getId(),
                'direction'      => $direction,
                'body'           => json_encode($body),
                'headers'        => [
                    'Host' => 'some.url',
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

        $contextData = $event->getContext();
        $expectedData = [
            'http'   => [
                'method'      => 'GET',
                'path'        => '/',
                'remote_addr' => request()->ip(),
                'request_id'  => Session::getId(),
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