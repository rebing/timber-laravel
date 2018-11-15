<?php

namespace Rebing\Timber\Requests\Events;

use Illuminate\Http\Response;
use function is_null;
use function number_format;

class HttpResponseEvent extends HttpEvent
{
    /* @var $response Response */
    protected $response;

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
        $elapsedTime = $this->getElapsedTimeInMs();
        $elapsedTime = is_null($elapsedTime) ? null : number_format($elapsedTime, 2);

        switch ($this->direction) {
            case self::DIRECTION_OUT:
                $message = "Sent $status response in {$elapsedTime}ms";
                break;
            case self::DIRECTION_IN:
                $message = "Received $status response from $this->serviceName in {$elapsedTime}ms";
                break;
            default:
                $message = "Response $status";
        }

        return $message;
    }

    public function getEvent(): array
    {
        // TODO: Implement getEvent() method.
    }
}