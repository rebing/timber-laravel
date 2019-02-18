<?php

namespace Rebing\Timber\Middleware;

use Closure;
use Illuminate\Http\Request;
use Rebing\Timber\Requests\Events\HttpRequestEvent;
use Rebing\Timber\Requests\Events\HttpResponseEvent;
use Symfony\Component\HttpFoundation\Response;

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

    const REQUEST_START_HEADER = 'X-Timber-Request-Start';

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
        $request->headers->set(self::REQUEST_START_HEADER, $this->reqEvent->getRequestStartTime());
        $this->reqEvent->queue();

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
        // Get the elapsed time
        $startTime = $request->headers->get(self::REQUEST_START_HEADER);
        $request->headers->remove(self::REQUEST_START_HEADER);
        $currentTime = microtime(true);
        $elapsedTimeMs = ($currentTime - $startTime) * 1000;

        $responseEvent = new HttpResponseEvent($response, true, $elapsedTimeMs);
        $responseEvent->queue();
    }
}