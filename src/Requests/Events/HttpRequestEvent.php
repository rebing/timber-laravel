<?php

namespace Rebing\Timber\Requests\Events;

use Illuminate\Http\Request;

class HttpRequestEvent extends HttpEvent
{
    /**
     * HttpRequestEvent constructor.
     * @param Request $request
     * @param string $direction - incoming or outgoing
     * @param null|string $serviceName - An optional description, where the request or response is sent
     */
    public function __construct(Request $request, string $direction, ?string $serviceName = null)
    {
        $this->request = $request;
        $this->direction = $direction;
        $this->serviceName = $serviceName;
    }

    public function getMessage(): string
    {
        $method = $this->request->getMethod();
        $path = $this->request->path();

        switch ($this->direction) {
            case self::DIRECTION_OUT:
                return "Sent $method $path";
            case self::DIRECTION_IN:
                return "Received $method $path";
        }

        return "Request $method $path";
    }

    public function getEvent(): array
    {
        $data = [
            'method'     => $this->request->getMethod(),
            'path'       => $this->request->path(),
            'scheme'     => $this->request->getScheme(),
            'request_id' => $this->getRequestId(),
            'direction'  => $this->direction,
        ];

        if (count($this->request->headers->all())) {
            $data['headers'] = $this->request->headers->all();
        }
        if (!empty($this->request->getHost())) {
            $data['host'] = $this->request->getHost();
        }
        if (!empty($this->request->getPort())) {
            $data['port'] = $this->request->getPort();
        }
        if (!empty($this->request->getQueryString())) {
            $data['query_string'] = $this->request->getQueryString();
        }
        if (!empty($this->serviceName)) {
            $data['service_name'] = $this->serviceName;
        }
        if (count($this->request->all())) {
            if ($this->request->isJson()) {
                $data['body'] = $this->request->json();
            } elseif ($this->request->isXmlHttpRequest()) {
                $data['body'] = $this->request->getContent();
            }
        }

        return [
            'http_request' => $data,
        ];
    }
}