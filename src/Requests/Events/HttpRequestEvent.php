<?php

namespace Rebing\Timber\Requests\Events;

use Illuminate\Http\Request;

class HttpRequestEvent extends HttpEvent
{
    /* @var $request Request */
    protected $request;

    /**
     * HttpRequestEvent constructor.
     * @param Request $request
     * @param string $direction - incoming or outgoing
     * @param null|string $serviceName - An optional description, where the request or response is sent
     */
    public function __construct($request, string $direction, ?string $serviceName = null)
    {
        $this->request     = $request;
        $this->direction   = $direction;
        $this->serviceName = $serviceName;

        $this->setRequestId();
        $this->setRequestStartTime();
    }

    protected function setRequestId(): string
    {
        $reqId = parent::setRequestId();
        $this->request->headers->set('x-request-id', $reqId);
        return $reqId;
    }

    public function getMessage(): string
    {
        $method = $this->request->getMethod();
        $path   = $this->request->path();

        switch ($this->direction) {
            case self::DIRECTION_OUT:
                $message = "Sent $method $path";
                if ($this->serviceName) {
                    $message .= " to $this->serviceName";
                }
                break;
            case self::DIRECTION_IN:
                $message = "Received $method $path";
                break;
            default:
                $message = "Request $method $path";
        }

        return $message;
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
            } else {
                $data['body'] = json_encode($this->request->all());
            }
        }

        return [
            'http_request' => $data,
        ];
    }
}