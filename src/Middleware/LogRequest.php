<?php

namespace Rebing\Timber\Middleware;

use Closure;
use Illuminate\Http\Request;
use Rebing\Timber\Requests\Events\HttpEvent;
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
        $reqEvent = new HttpRequestEvent($request, HttpEvent::DIRECTION_IN);
        dispatch($reqEvent);

        $response = $next($request);
        $responseEvent = new HttpResponseEvent($response, HttpEvent::DIRECTION_OUT, $reqEvent->getElapsedTimeInMs());
        dispatch($responseEvent);

        return $response;
    }
}