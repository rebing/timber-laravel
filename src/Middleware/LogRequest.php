<?php

namespace Rebing\Timber\Middleware;

use Closure;
use Illuminate\Http\Request;
use Rebing\Timber\Requests\LogLine;

/**
 * Middleware that logs the incoming request data to Timber
 *
 * Class LogTimberRequest
 * @package Rebing\Timber\Middleware
 */
class LogRequest
{
    /**
     * Handle an incoming request.
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
        $log     = new LogLine();
        $message = $this->getMessage($request);

        $log->json($message);

        return $next($request);
    }

    private function getMessage(Request $request)
    {
        $method = $request->method();
        $path   = $request->path();

        return "Received $method $path";
    }

    private function getEvent(Request $request)
    {
        return [
            'http_request' => [
                'headers' => [

                ],
            ],
        ];
    }
}