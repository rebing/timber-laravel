<?php

namespace Rebing\Timber\Requests\Contexts;

class SystemContext extends AbstractContext
{
    public function getData(): array
    {
        $hostName = gethostname();

        return [
            'system' => [
                'hostname' => $hostName,
                'ip'       => gethostbyname($hostName),
                'pid'      => getmypid(),
            ],
        ];
    }
}