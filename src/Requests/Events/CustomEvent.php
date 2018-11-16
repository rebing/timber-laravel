<?php

namespace Rebing\Timber\Requests\Events;

class CustomEvent extends AbstractEvent
{
    protected $message;
    protected $data;
    protected $key;

    /**
     * @param string $message - A descriptive message
     * @param array $data - Custom data to be logged
     * @param string $key - The key name of the event
     */
    public function __construct(string $message, string $key, array $data)
    {
        $this->message = $message;
        $this->key     = $key;
        $this->data    = $data;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getEvent(): array
    {
        return [
            'custom' => [
                $this->key => $this->data,
            ]
        ];
    }

    public function getContext(): array
    {
        return [];
    }
}