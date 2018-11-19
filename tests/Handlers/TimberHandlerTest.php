<?php

namespace Rebing\Timber\Tests\Handlers;

use Carbon\Carbon;
use Monolog\Logger;
use Rebing\Timber\Handlers\TimberHandler;
use Rebing\Timber\Tests\TestCase;

class TimberHandlerTest extends TestCase
{
    /**
     * @group testing
     * @test
     */
    public function testCreatesAndWritesToLogWithNewTimberHandler()
    {
        $data    = $this->mockResource(str_random());
        $handler = new TimberHandler();

        $handler->handle($data);
    }

    protected function mockResource(
        string $message,
        $context = [],
        $extra = [],
        $level = Logger::DEBUG,
        $levelName = 'debug',
        $channel = ''
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