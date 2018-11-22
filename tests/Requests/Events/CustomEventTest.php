<?php

namespace Rebing\Timber\Tests\Requests\Events;

use Rebing\Timber\Requests\Events\CustomEvent;
use Rebing\Timber\Tests\TestCase;

class CustomEventTest extends TestCase
{
    private $message;
    private $key;
    private $data;
    private $context;

    public function setUp()
    {
        parent::setUp();

        $this->message = str_random();
        $this->key     = str_random();
        $this->data    = [
            'test' => 'value',
        ];
        $this->context = [
            'key' => str_random(),
        ];
    }

    /**
     * @test
     */
    public function testCreateACustomEventAndGetTheMessage()
    {
        $event = new CustomEvent($this->message, $this->key, $this->data, $this->context);

        $this->assertEquals($this->message, $event->getMessage());
    }

    /**
     * @test
     */
    public function testCreateACustomEventAndGetTheEventData()
    {
        $event = new CustomEvent($this->message, $this->key, $this->data, $this->context);

        $eventData = [
            'custom' => [
                $this->key => $this->data,
            ],
        ];
        $this->assertEquals($eventData, $event->getEvent());
    }

    /**
     * @test
     */
    public function testCreateACustomEventAndGetTheContext()
    {
        $event = new CustomEvent($this->message, $this->key, $this->data, $this->context);

        $contextData = [
            'custom' => [
                $this->key => $this->context,
            ],
        ];
        $this->assertEquals($contextData, $event->getContext());
    }

    /**
     * @group guzzle-request
     * @test
     */
    public function testCreatesNewJsonLogLineFromCustomEvent()
    {
        $event    = new CustomEvent(str_random(), str_random(), ['key', 'some' => 'value']);
        $response = $event->send();

        $this->assertEquals('Accepted logs', $response);
    }
}