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
     * @param bool $outgoing
     * @param null|string $serviceName - An optional description, where the request or response is sent
     */
    public function __construct($request, bool $outgoing, ?string $serviceName = null)
    {
        $this->request     = $request;
        $this->outgoing    = $outgoing;
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

        if($this->outgoing) {
            $message = "Sent $method $path";
            if ($this->serviceName) {
                $message .= " to $this->serviceName";
            }
        } else {
            $message = "Received $method $path";
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
            'direction'  => $this->outgoing ? self::DIRECTION_OUT : self::DIRECTION_IN,
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