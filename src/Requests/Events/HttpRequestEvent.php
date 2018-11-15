<?php

namespace Rebing\Timber\Requests\Events;

use Illuminate\Http\Request;

class HttpRequestEvent extends HttpEvent
{
    private $direction;

    const DIRECTION_OUT = 'outgoing';
    const DIRECTION_IN = 'incoming';

    public function __construct(Request $request, string $direction)
    {
        $this->request   = $request;
        $this->direction = $direction;
    }

    public function getMessage(): string
    {
        $method = $this->request->getMethod();
        $path   = $this->request->path();

        switch ($this->direction) {
            case self::DIRECTION_OUT:
                return "Sent $method $path";
            case self::DIRECTION_IN:
                return "Received $method $path";
        }

        return '';
    }

    public function getEvent(): array
    {
        return [
            'http_request' => [
                'headers'      => $this->request->headers->all(),
                'host'         => $this->request->getHost(),
                'method'       => $this->request->getMethod(),
                'path'         => $this->request->path(),
                'port'         => $this->request->getPort(),
                'query_string' => $this->request->getQueryString(),
                'scheme'       => $this->request->getScheme(),
                'request_id'   => $this->getRequestId(),
            ],
        ];
    }
}