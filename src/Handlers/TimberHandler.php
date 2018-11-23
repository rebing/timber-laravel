<?php

namespace Rebing\Timber\Handlers;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Rebing\Timber\Requests\Events\CustomEvent;

class TimberHandler extends AbstractProcessingHandler
{
    public function __construct($level = Logger::DEBUG, $bubble = true)
    {
        parent::__construct($level, $bubble);
    }

    /**
     * Writes the record down to the log of the implementing handler
     *
     * @param  array $record
     * @return void
     */
    protected function write(array $record)
    {
        $channel = $record['channel'];
        $levelName = $record['level_name'];
        $type = "$channel.$levelName";

        dispatch(new CustomEvent($record['message'], $type, $record['extra'], $record['context']));
    }
}