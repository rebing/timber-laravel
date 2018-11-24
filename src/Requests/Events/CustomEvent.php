<?php

namespace Rebing\Timber\Requests\Events;

use Monolog\Logger;
use Rebing\Timber\Requests\Contexts\CustomContext;

class CustomEvent extends AbstractEvent
{
    protected $message;
    protected $data;
    protected $context;
    protected $type;
    protected $messageMaxLength;

    /**
     * @param string $message - A descriptive message
     * @param string $type - The type name of the event
     * @param array $data - Custom data to be logged
     * @param array $context - Extra context to be added with event
     * @param int $logLevel
     * @param int $messageMaxLength
     */
    public function __construct(
        string $message,
        string $type,
        array $data,
        array $context = [],
        $logLevel = Logger::INFO,
        int $messageMaxLength = 200
    ) {
        $this->message = $message;
        $this->type = $type;
        $this->data = $data;
        $this->context = $context;
        $this->logLevel = $logLevel;
        $this->messageMaxLength = $messageMaxLength;
    }

    public function getMessage(): string
    {
        return substr($this->message, 0, $this->messageMaxLength);
    }

    public function getEvent(): array
    {
        if (!count($this->data)) {
            $this->data = [
                'message' => $this->message,
            ];
        }

        return [
            'custom' => [
                $this->type => (object)$this->data,
            ],
        ];
    }

    public function getContext(): array
    {
        $defaultData = parent::getContext();
        $context = new CustomContext($this->type, $this->context);
        return array_merge($defaultData, $context->getData());
    }
}