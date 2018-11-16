<?php

namespace Rebing\Timber\Requests\Events;

use Session;

abstract class HttpEvent extends AbstractEvent
{
    protected $requestId;
    protected $direction;
    protected $serviceName;
    protected $requestStartTime;

    const SESSION_REQUEST_KEY = 'TIMBER_REQUEST_ID';
    const SESSION_REQUEST = 'TIMBER_REQUEST';
    const DIRECTION_OUT = 'outgoing';
    const DIRECTION_IN = 'incoming';

    /**
     * Set a unique request ID for the current session
     */
    protected function setRequestId(): string
    {
        $reqId = strtolower(str_random(24));
        Session::put(self::SESSION_REQUEST_KEY, $reqId);
        return $reqId;
    }

    /**
     * Get a unique ID connected to the current session
     */
    public function getRequestId(): string
    {
        $reqId = Session::get(self::SESSION_REQUEST_KEY);

        if (is_null($reqId)) {
            $reqId = $this->setRequestId();
        }

        return $reqId;
    }

    public function setRequestStartTime(): float
    {
        $this->requestStartTime = microtime(true);
        return $this->requestStartTime;
    }

    public function getRequestStartTime()
    {
        return $this->requestStartTime;
    }

    /**
     * Get the time from the start of the request
     */
    public function getElapsedTimeInMs(): ?float
    {
        $startTime = $this->getRequestStartTime();
        if (is_null($startTime)) {
            return null;
        }

        $currentTime = microtime(true);
        return ($currentTime - $startTime) * 1000;
    }

    public function getContext(): array
    {
        $data = [
            'http'   => $this->getHttpContext(),
            'system' => $this->getSystemContext(),
        ];

        if ($user = $this->getUserContext()) {
            $data['user'] = $user;
        }

        return $data;
    }

    private function getHttpContext(): array
    {
        return [
            'method'      => request()->method(),
            'path'        => request()->path(),
            'remote_addr' => request()->ip(),
            'request_id'  => $this->getRequestId(),
        ];
    }
}