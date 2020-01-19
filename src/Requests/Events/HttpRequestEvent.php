<?php

namespace Rebing\Timber\Requests\Events;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Rebing\Timber\Exceptions\TimberException;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;

class HttpRequestEvent extends HttpEvent
{
    protected $request;
    protected $headers = [];

    /**
     * HttpRequestEvent constructor.
     * @param $request
     * @param bool $outgoing
     * @param null|string $serviceName - An optional description, where the request or response is sent
     * @throws TimberException
     */
    public function __construct($request, bool $outgoing, ?string $serviceName = null)
    {
        if ($request instanceof \Illuminate\Http\Request) {
            $this->headers = $request->header();
        } elseif ($request instanceof \GuzzleHttp\Psr7\ServerRequest) {
            // We have to grab headers before conversion as they are somehow lost
            $this->headers = array_map(function ($v) {
                return $v[0];
            }, $request->getHeaders());

            $factory = new HttpFoundationFactory();
            $request = \Illuminate\Http\Request::createFromBase($factory->createRequest($request));
        } else {
            throw new TimberException('Invalid Request. Found: ' . get_class($request));
        }

        $this->request = $request;
        $this->outgoing = $outgoing;
        $this->serviceName = $serviceName;

        $this->setRequestStartTime();
    }

    public function getMessage(): string
    {
        $method = $this->request->getMethod();
        $path = $this->request->path();

        if ($this->outgoing) {
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
        $request = $this->request;

        $data = [
            'method'     => $request->getMethod(),
            'path'       => $request->path(),
            'scheme'     => $request->getScheme(),
            'request_id' => Session::getId(),
            'direction'  => $this->outgoing ? self::DIRECTION_OUT : self::DIRECTION_IN,
        ];

        if (count($this->headers)) {
            $data['headers'] = $this->headers;
        }
        if (!empty($request->getHost())) {
            $data['host'] = $request->getHost();
        }
        if (!empty($request->getPort())) {
            $data['port'] = $request->getPort();
        }
        if (!empty($request->getQueryString())) {
            $data['query_string'] = $request->getQueryString();
        }
        if (!empty($this->serviceName)) {
            $data['service_name'] = $this->serviceName;
        }

        if (count($request->all())) {
            // JSON
            if (Str::contains($request->header('CONTENT_TYPE'), ['/json', '+json'])) {
                $data['body'] = substr((string)$request->getContent(), 0, 8192);
            } else {
                $data['body'] = substr(json_encode($request->all()), 0, 8192);
            }
        } elseif ($request->getContent()) {
            $data['body'] = substr((string)$request->getContent(), 0, 8192);
        }

        return [
            'http_request' => $data,
        ];
    }
}
