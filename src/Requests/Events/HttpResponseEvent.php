<?php

namespace Rebing\Timber\Requests\Events;

use Symfony\Component\HttpFoundation\Response;
use Session;

class HttpResponseEvent extends HttpEvent
{
    /* @var $response Response */
    protected $response;
    protected $elapsedTimeMs;

    /**
     * HttpRequestEvent constructor.
     * @param Response $response
     * @param bool $outgoing
     * @param null|string $serviceName - An optional description, where the request or response is sent
     * @param float|null $elapsedTimeMs - Elapsed time from the request to the response
     */
    public function __construct($response, bool $outgoing, float $elapsedTimeMs, ?string $serviceName = null)
    {
        $this->response      = $response;
        $this->outgoing      = $outgoing;
        $this->serviceName   = $serviceName;
        $this->elapsedTimeMs = $elapsedTimeMs;
    }

    public function getMessage(): string
    {
        $status = $this->response->status();

        if ($this->outgoing) {
            $message = "Sent $status response";
        } else {
            $message = "Received $status response";
            if ($this->serviceName) {
                $message .= " from $this->serviceName";
            }
        }

        $elapsedTime = number_format($this->elapsedTimeMs, 2);
        $message     .= " in {$elapsedTime}ms";

        return $message;
    }

    public function getEvent(): array
    {
        $data = [
            'status'     => $this->response->status(),
            'request_id' => Session::getId(),
            'direction'  => $this->outgoing ? self::DIRECTION_OUT : self::DIRECTION_IN,
            'time_ms'    => $this->elapsedTimeMs,
        ];

        if (count($this->response->headers->all())) {
            $data['headers'] = $this->response->headers->all();
        }

        return [
            'http_response' => $data,
        ];
    }
}