<?php

namespace Rebing\Timber\Middleware;

use Closure;
use Illuminate\Http\Request;
use Rebing\Timber\Requests\Events\HttpEvent;
use Rebing\Timber\Requests\Events\HttpResponseEvent;

/**
 * Middleware (afterware) that logs the outgoing response data to Timber
 *
 * Class LogTimberRequest
 * @package Rebing\Timber\Middleware
 */
class LogResponse
{
    /**
     * Handle an outgoing response and log its data to Timber
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
        $response = $next($request);
        dispatch(new HttpResponseEvent($response, HttpEvent::DIRECTION_OUT));

        return $next($request);
    }
}