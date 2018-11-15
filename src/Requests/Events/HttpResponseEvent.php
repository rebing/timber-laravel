<?php

namespace Rebing\Timber\Requests\Events;

use Illuminate\Http\Response;

class HttpResponseEvent extends HttpEvent
{
    /* @var $response Response */
    private $response;

    /**
     * HttpRequestEvent constructor.
     * @param Response $response
     * @param string $direction - incoming or outgoing
     * @param null|string $serviceName - An optional description, where the request or response is sent
     */
    public function __construct(Response $response, string $direction, ?string $serviceName = null)
    {
        $this->response = $response;
        $this->direction = $direction;
        $this->serviceName = $serviceName;
    }

    public function getMessage(): string
    {
        $status = $this->response->status();

        switch ($this->direction) {
            case self::DIRECTION_OUT:
                return "Sent $status response in ";
            case self::DIRECTION_IN:
                return "Received $status response from .. in ";
        }

        return "Response $status";
    }

    public function getEvent(): array
    {
        // TODO: Implement getEvent() method.
    }
}