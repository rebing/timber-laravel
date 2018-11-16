<?php

namespace Rebing\Timber\Requests;

use Session;

trait RequestIdTrait
{
    public static function getRequestSessionKey()
    {
        return 'TIMBER_REQUEST_ID';
    }

    /**
     * Set a unique request ID for the current session
     */
    protected function setRequestId(): string
    {
        $reqId = strtolower(str_random(24));
        Session::put(self::getRequestSessionKey(), $reqId);
        return $reqId;
    }

    /**
     * Get a unique ID connected to the current session
     */
    public function getRequestId(): string
    {
        $reqId = Session::get(self::getRequestSessionKey());

        if (is_null($reqId)) {
            $reqId = $this->setRequestId();
        }

        return $reqId;
    }
}