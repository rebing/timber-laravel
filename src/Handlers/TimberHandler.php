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
        if($record['level'] >= Logger::ERROR && isset($record['context']['exception'])
            && $record['context']['exception'] instanceof Exception) {
            $this->writeError($record);
        } else {
            $this->writeLog($record);
        }
    }

    private function writeLog(array $record)
    {
        if(count($record['context']) === 1) {
            $type = array_keys($record['context'])[0];
            $extra = array_first($record['context']);
        } else {
            $type = $record['channel'] . '.' .$record['level_name'];
            $extra = $record['context'];
        }

        dispatch(new CustomEvent($record['message'], $type, $extra, [], $record['level']));
    }

    private function writeError(array $record)
    {
        $exception = $record['context']['exception'];
        $extra = array_get($record['context'], 'extra', []);

        $event = new ErrorEvent($exception, $extra);
        $event->queue();
    }
}