<?php

namespace Rebing\Timber\Tests\Requests\Events;

use Illuminate\Http\Request;
use Rebing\Timber\Requests\Events\HttpEvent;
use Rebing\Timber\Requests\Events\HttpResponseEvent;
use Rebing\Timber\Tests\TestCase;

class HttpResponseEventTest extends TestCase
{
    /**
     * @test
     */
    public function testCreatesANewOutgoingResponseEventAndGetsTheMessage()
    {
        $request = new Request();

        $event = new HttpResponseEvent($request, HttpEvent::DIRECTION_OUT);

        $message = "Sent 200 response in";
        $this->assertEquals($message, $event->getMessage());
    }
}