<?php

namespace Rebing\Timber\Requests\Events;

use Illuminate\Http\Request;
use Session;

abstract class HttpEvent extends AbstractEvent
{
    /* @var $request Request */
    protected $request;
    protected $requestId;

    const SESSION_REQUEST_KEY = 'TIMBER_REQUEST_ID';

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
    protected function getRequestId(): string
    {
        if ($this->requestId) {
            return $this->requestId;
        }

        $reqId = Session::get(self::SESSION_REQUEST_KEY);

        if (is_null($reqId)) {
            $reqId = $this->setRequestId();
        }

        return $reqId;
    }

    public function getContext(): array
    {
        return [
            'http'   => $this->getHttpContext(),
            'system' => $this->getSystemContext(),
            'user'   => $this->getUserContext(),
        ];
    }

    private function getHttpContext(): array
    {
        return [
            'method'      => $this->request->getMethod(),
            'path'        => $this->request->path(),
            'remote_addr' => $this->request->ip(),
            'request_id'  => $this->getRequestId(),
        ];
    }
}