<?php

namespace Rebing\Timber\Handlers;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Rebing\Timber\Requests\Events\CustomEvent;
use Exception;
use Rebing\Timber\Requests\Events\ErrorEvent;

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
        $type = $record['channel'] . '.' .$record['level_name'];

        if($record['level'] >= Logger::ERROR && isset($record['context']['exception'])
            && $record['context']['exception'] instanceof Exception) {
            $this->writeError($record['context']['exception']);
        } else {
            $this->writeLog($type, $record);
        }
    }

    private function writeLog(string $type, array $record)
    {
        dispatch(new CustomEvent($record['message'], $type, $record['extra'], $record['context']));
    }

    private function writeError(Exception $e)
    {
        $event = new ErrorEvent($e);
        $event->send();
    }
}