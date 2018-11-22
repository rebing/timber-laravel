<?php

namespace Rebing\Timber\Requests\Events;

use Rebing\Timber\Requests\Contexts\CustomContext;

class CustomEvent extends AbstractEvent
{
    protected $message;
    protected $data;
    protected $context;
    protected $type;

    /**
     * @param string $message - A descriptive message
     * @param string $type - The type name of the event
     * @param array $data - Custom data to be logged
     * @param array $context - Extra context to be added with event
     */
    public function __construct(string $message, string $type, array $data, array $context = [])
    {
        $this->message = $message;
        $this->type = $type;
        $this->data = $data;
        $this->context = $context;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getEvent(): array
    {
        if (!count($this->data)) {
            return [];
        }

        return [
            'custom' => [
                $this->type => $this->data,
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