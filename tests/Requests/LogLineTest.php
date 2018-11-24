<?php

namespace Rebing\Timber\Tests\Requests;

use Rebing\Timber\Requests\LogLine;
use Rebing\Timber\Tests\TestCase;

class LogLineTest extends TestCase
{
    /**
     * @group guzzle-request
     * @test
     */
    public function testCreatesNewJsonLogLine()
    {
        $message = str_random();
        $level   = LogLine::LOG_LEVEL_WARN;
        $context = [
            'user' => [
                'id' => str_random(),
            ],
        ];
        $event   = [
            'http_request' => [
                'method' => 'GET',
            ],
        ];

        $logLine  = new LogLine($message, $context, $event, $level);
        $response = $logLine->json();

        $this->assertEquals('Accepted logs', $response);
    }
}