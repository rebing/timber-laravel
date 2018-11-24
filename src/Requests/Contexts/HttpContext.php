<?php

namespace Rebing\Timber\Requests\Contexts;

use Session;

class HttpContext extends AbstractContext
{
    public function getData(): array
    {
        return [
            'http' => [
                'method'      => request()->method(),
                'path'        => request()->path(),
                'remote_addr' => request()->ip(),
                'request_id'  => Session::getId(),
            ],
        ];
    }
}