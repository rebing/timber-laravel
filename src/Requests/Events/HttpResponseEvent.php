<?php

namespace Rebing\Timber\Requests\Events;

use Illuminate\Http\Response;

class HttpResponseEvent extends HttpEvent
{
    /* @var $response Response */
    protected $response;
    protected $elapsedTimeMs;

    /**
     * HttpRequestEvent constructor.
     * @param Response $response
     * @param string $direction - incoming or outgoing
     * @param null|string $serviceName - An optional description, where the request or response is sent
     * @param float|null $elapsedTimeMs - Elapsed time from the request to the response
     */
    public function __construct(Response $response, string $direction, ?string $serviceName = null, ?float $elapsedTimeMs = null)
    {
        $this->response = $response;
        $this->direction = $direction;
        $this->serviceName = $serviceName;
        $this->elapsedTimeMs = $elapsedTimeMs;
    }

    public function getMessage(): string
    {
        $status = $this->response->status();

        switch ($this->direction) {
            case self::DIRECTION_OUT:
                $message = "Sent $status response";
                break;
            case self::DIRECTION_IN:
                $message = "Received $status response";
                if($this->serviceName) {
                    $message .= " from $this->serviceName";
                }
                break;
            default:
                $message = "Response $status";
        }

        if($this->elapsedTimeMs) {
            $elapsedTime = number_format($this->elapsedTimeMs, 2);
            $message .= " in {$elapsedTime}ms";
        }

        return $message;
    }

    public function getEvent(): array
    {
        // TODO: Implement getEvent() method.
    }
}