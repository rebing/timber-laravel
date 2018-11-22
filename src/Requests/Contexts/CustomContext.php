<?php

namespace Rebing\Timber\Requests\Contexts;

class CustomContext extends AbstractContext
{
    protected $type;
    protected $data;

    public function __construct(string $type, array $data)
    {
        $this->type  = $type;
        $this->data = $data;
    }

    public function getData(): array
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
}