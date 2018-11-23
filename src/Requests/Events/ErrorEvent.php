<?php

namespace Rebing\Timber\Requests\Events;

use Exception;

class ErrorEvent extends AbstractEvent
{
    private $exception;

    public function __construct(Exception $exception)
    {
        $this->exception = $exception;
    }

    public function getMessage(): string
    {
        return $this->exception->getMessage();
    }

    public function getEvent(): array
    {
        $backtrace = $this->getBacktrace();
        return [
            'error' => [
                'name'      => get_class($this->exception),
                'message'   => $this->getMessage(),
                'backtrace' => $backtrace,
            ],
        ];
    }

    private function getBacktrace(): array
    {
        return array_map(function($frame) {
            return [
                'file' => $frame['file'],
                'function' => $this->getTraceFunction($frame),
                'line' => $frame['line'],
            ];
        }, $this->exception->getTrace());
    }

    private function getTraceFunction(array $frame): string
    {
        $function = $frame['class'] . '->' . $frame['function'];

        if(isset($frame['args'])) {
            $args = [];
            foreach($frame['args'] as $arg) {
                if (is_string($arg)) {
                    $arg = strlen($arg) < 200 ? $arg : (substr($arg, 0, 200) . '...');
                    $args[] = "'" . $arg . "'";
                } elseif (is_array($arg)) {
                    $args[] = "Array";
                } elseif (is_null($arg)) {
                    $args[] = 'NULL';
                } elseif (is_bool($arg)) {
                    $args[] = ($arg) ? "true" : "false";
                } elseif (is_object($arg)) {
                    $args[] = get_class($arg);
                } elseif (is_resource($arg)) {
                    $args[] = get_resource_type($arg);
                } else {
                    $args[] = $arg;
                }
            }
            $args = join(", ", $args);
            $function .= '(' . $args . ')';
        }

        return $function;
    }
}