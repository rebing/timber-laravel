<?php

namespace Rebing\Timber\Tests\Handlers;

use Carbon\Carbon;
use Monolog\Logger;
use Rebing\Timber\Handlers\TimberHandler;
use Rebing\Timber\Tests\TestCase;

class TimberHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function testCreatesAndWritesToLogWithNewTimberHandler()
    {
        $data = $this->mockResource(str_random(), [], ['key' => 'value']);
        $handler = new TimberHandler();

        $handled = $handler->handle($data);

        $this->assertFalse($handled);
    }

    protected function mockResource(
        string $message,
        $context = [],
        $extra = [],
        $level = Logger::DEBUG,
        $levelName = 'debug',
        $channel = 'default'
    ) {
        return [
            'message'    => $message,
            'level'      => $level,
            'level_name' => $levelName,
            'context'    => $context,
            'extra'      => $extra,
            'channel'    => $channel,
            'datetime'   => \DateTimeImmutable::createFromMutable(Carbon::now()),
        ];
    }
}