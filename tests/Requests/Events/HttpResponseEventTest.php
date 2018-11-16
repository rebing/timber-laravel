<?php

namespace Rebing\Timber\Tests\Requests\Events;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use function number_format;
use Rebing\Timber\Requests\Events\HttpEvent;
use Rebing\Timber\Requests\Events\HttpRequestEvent;
use Rebing\Timber\Requests\Events\HttpResponseEvent;
use Rebing\Timber\Tests\TestCase;

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
        $elapsedTime = mt_rand(10,100);

        $event = new HttpResponseEvent($response, HttpEvent::DIRECTION_OUT, $this->serviceName, $elapsedTime);

        $timeMs = number_format($elapsedTime, 2);
        $message = "Sent 200 response in {$timeMs}ms";
        $this->assertEquals($message, $event->getMessage());
    }

    /**
     * @test
     */
    public function testCreatesANewIncomingResponseEventAndGetsTheMessage()
    {
        $response = new Response();
        $elapsedTime = mt_rand(10,100);

        $event = new HttpResponseEvent($response, HttpEvent::DIRECTION_IN, $this->serviceName, $elapsedTime);

        $timeMs = number_format($elapsedTime, 2);
        $message = "Received 200 response from $this->serviceName in {$timeMs}ms";
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
}