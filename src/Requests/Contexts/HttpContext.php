<?php

namespace Rebing\Timber\Requests\Contexts;


class HttpContext extends AbstractContext
{
    public function getData(): array
    {
        return [
            'http' => [
                'method'      => request()->method(),
                'path'        => request()->path(),
                'remote_addr' => request()->ip(),
                'request_id'  => $this->getRequestId(),
            ],
        ];
    }
}