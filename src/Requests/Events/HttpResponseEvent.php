<?php

namespace Rebing\Timber\Requests\Events;

use Illuminate\Support\Facades\Session;
use Rebing\Timber\Exceptions\TimberException;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;

class HttpResponseEvent extends HttpEvent
{
    protected $response;
    protected $elapsedTimeMs;

    /**
     * HttpRequestEvent constructor.
     * @param $response
     * @param bool $outgoing
     * @param float|null $elapsedTimeMs - Elapsed time from the request to the response
     * @param null|string $serviceName - An optional description, where the request or response is sent
     * @throws TimberException
     */
    public function __construct($response, bool $outgoing, float $elapsedTimeMs, ?string $serviceName = null)
    {
        if($response instanceof \GuzzleHttp\Psr7\Response) {
            $factory = new HttpFoundationFactory();
            $response = $factory->createResponse($response);
        }

        if(!($response instanceof \Symfony\Component\HttpFoundation\Response)) {
            throw new TimberException('Invalid Response. Found: ' . get_class($response));
        }

        $this->response      = $response;
        $this->outgoing      = $outgoing;
        $this->serviceName   = $serviceName;
        $this->elapsedTimeMs = $elapsedTimeMs;
    }

    public function getMessage(): string
    {
        $status = $this->response->getStatusCode();

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
            'status'     => $this->response->getStatusCode(),
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
