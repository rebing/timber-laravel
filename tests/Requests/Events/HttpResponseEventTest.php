<?php

namespace Rebing\Timber\Tests\Requests\Events;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Rebing\Timber\Requests\Events\HttpEvent;
use Rebing\Timber\Requests\Events\HttpRequestEvent;
use Rebing\Timber\Requests\Events\HttpResponseEvent;
use Rebing\Timber\Tests\TestCase;
use function str_random;

class HttpResponseEventTest extends TestCase
{
    /** @var HttpRequestEvent */
    private $requestEvent;
    private $serviceName;

    public function setUp()
    {
        parent::setUp();

        $this->serviceName = str_random();
        // A response must always have a preceding request
        $request = new Request();
        $this->requestEvent = new HttpRequestEvent($request, HttpEvent::DIRECTION_IN, $this->serviceName);
    }

    /**
     * @test
     */
    public function testCreatesANewOutgoingResponseEventAndGetsTheMessage()
    {
        $response = new Response();

        $event = new HttpResponseEvent($response, HttpEvent::DIRECTION_OUT, $this->serviceName);

        $message = "Sent 200 response in ";
        $this->assertStringStartsWith($message, $event->getMessage());
    }

    /**
     * @test
     */
    public function testCreatesANewIncomingResponseEventAndGetsTheMessage()
    {
        $response = new Response();

        $event = new HttpResponseEvent($response, HttpEvent::DIRECTION_IN, $this->serviceName);

        $message = "Received 200 response from $this->serviceName in ";
        $this->assertStringStartsWith($message, $event->getMessage());
    }

    /**
     * @group testing
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
}