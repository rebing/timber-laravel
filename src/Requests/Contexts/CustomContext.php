<?php

namespace Rebing\Timber\Requests\Contexts;

class CustomContext extends AbstractContext
{
    protected $key;
    protected $data;

    public function __construct(string $key, array $data)
    {
        $this->key  = $key;
        $this->data = $data;
    }

    public function getData(): array
    {
        if (!count($this->data)) {
            return [];
        }

        return [
            'custom' => [
                $this->key => $this->data,
            ],
        ];
    }
}