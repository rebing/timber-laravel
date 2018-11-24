<?php

namespace Rebing\Timber\Requests\Events;

use Exception;
use Rebing\Timber\Requests\Contexts\CustomContext;

class ErrorEvent extends AbstractEvent
{
    const MAX_BACKTRACE_LENGTH = 20;

    private $exception;
    private $context;

    public function __construct(Exception $exception, array $context = [])
    {
        $this->exception = $exception;
        $this->context = $context;
    }

    public function getMessage(): string
    {
        $trace = $this->exception->getTrace();

        $message = 'Exception: "';
        $message .= $this->exception->getMessage();
        $message .= '" @ ';
        if (isset($trace[0])) {
            if (isset($trace[0]['class']) && $trace[0]['class'] != '') {
                $message .= $trace[0]['class'];
                $message .= array_get($trace, 'type', '->');
            }

            $message .= $trace[0]['function'];
        }

        return $message;
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
        $backtrace = [];

        foreach ($this->exception->getTrace() as $c => $frame) {
            if ($c >= self::MAX_BACKTRACE_LENGTH) {
                break;
            }

            if (isset($frame['file']) && isset($frame['line'])) {
                $backtrace[] = [
                    'file'     => array_get($frame, 'file', 'Unknown'),
                    'function' => $this->getTraceFunction($frame),
                    'line'     => array_get($frame, 'line', 1),
                ];
            }
        }

        return $backtrace;
    }

    private function getTraceFunction(array $frame): string
    {
        $function = array_get($frame, 'class', 'Unknown') . array_get($frame, 'type', '->') . $frame['function'];

        if (isset($frame['args'])) {
            $args = [];
            foreach ($frame['args'] as $arg) {
                if (is_string($arg)) {
                    $arg    = strlen($arg) < 200 ? $arg : (substr($arg, 0, 200) . '...');
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
            $args     = join(", ", $args);
            $function .= '(' . $args . ')';
        }

        return $function;
    }

    public function getContext(): array
    {
        $defaultData = parent::getContext();
        $context = new CustomContext('messages', $this->context);
        return array_merge($defaultData, $context->getData());
    }
}