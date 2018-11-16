<?php

namespace Rebing\Timber\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Rebing\Timber\Requests\Events\HttpRequestEvent;
use Rebing\Timber\Requests\Events\HttpResponseEvent;

/**
 * Middleware that logs the incoming request and
 * outgoing response data to Timber
 *
 * Class LogTimberRequest
 * @package Rebing\Timber\Middleware
 */
class LogRequest
{
    /* @var $reqEvent HttpRequestEvent */
    private $reqEvent;

    /**
     * Handle an incoming request and log its data to Timber
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $this->reqEvent = new HttpRequestEvent($request, false);
        dispatch($this->reqEvent);

        return $next($request);
    }

    /**
     * Perform any final actions for the request lifecycle.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Response $response
     * @return void
     */
    public function terminate(Request $request, Response $response)
    {
        $responseEvent = new HttpResponseEvent($response, true, $this->reqEvent->getElapsedTimeInMs());
        dispatch($responseEvent);
    }
}