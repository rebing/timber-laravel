<?php

namespace Rebing\Timber\Tests\Requests\Events;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Rebing\Timber\Requests\Events\HttpEvent;
use Rebing\Timber\Requests\Events\HttpRequestEvent;
use Rebing\Timber\Requests\Events\HttpResponseEvent;
use Rebing\Timber\Requests\RequestIdTrait;
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
        $request            = new Request();
        $this->requestEvent = new HttpRequestEvent($request, HttpEvent::DIRECTION_IN, $this->serviceName);
    }

    /**
     * @test
     */
    public function testCreatesANewOutgoingResponseEventAndGetsTheMessage()
    {
        $response    = new Response();
        $elapsedTime = $this->requestEvent->getElapsedTimeInMs();

        $event = new HttpResponseEvent($response, HttpEvent::DIRECTION_OUT, $elapsedTime, $this->serviceName);

        $timeMs  = number_format($elapsedTime, 2);
        $message = "Sent 200 response in {$timeMs}ms";
        $this->assertEquals($message, $event->getMessage());
    }

    /**
     * @test
     */
    public function testCreatesANewIncomingResponseEventAndGetsTheMessage()
    {
        $response    = new Response();
        $elapsedTime = $this->requestEvent->getElapsedTimeInMs();

        $event = new HttpResponseEvent($response, HttpEvent::DIRECTION_IN, $elapsedTime, $this->serviceName);

        $timeMs  = number_format($elapsedTime, 2);
        $message = "Received 200 response from $this->serviceName in {$timeMs}ms";
        $this->assertEquals($message, $event->getMessage());
    }

    /**
     * @test
     */
    public function testCreatesANewResponseEventAndGetsEventData()
    {
        $response    = new Response();
        $elapsedTime = $this->requestEvent->getElapsedTimeInMs();
        $direction   = HttpEvent::DIRECTION_OUT;

        $event = new HttpResponseEvent($response, $direction, $elapsedTime);

        $eventData = $event->getEvent();
        $headers   = $eventData['http_response']['headers'];
        unset($eventData['http_response']['headers']);
        $reqId = Session::get(RequestIdTrait::getRequestSessionKey());

        $expectedData = [
            'http_response' => [
                'time_ms'    => $elapsedTime,
                'request_id' => $reqId,
                'direction'  => $direction,
                'status'     => 200,
            ],
        ];
        $this->assertEquals($reqId, $headers['x-request-id'][0]);
        $this->assertEquals($expectedData, $eventData);
    }
}