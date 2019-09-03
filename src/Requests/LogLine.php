<?php

namespace Rebing\Timber\Requests;

use Carbon\Carbon;
use GuzzleHttp\RequestOptions;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Rebing\Timber\Timber;

class LogLine extends Timber implements ShouldQueue
{
    use Dispatchable, Queueable, InteractsWithQueue;

    const PAYLOAD_SCHEMA = 'https://raw.githubusercontent.com/timberio/log-event-json-schema/v4.1.0/schema.json';

    const LOG_LEVEL_DEBUG = 'debug';
    const LOG_LEVEL_INFO = 'info';
    const LOG_LEVEL_WARN = 'warn';
    const LOG_LEVEL_ERROR = 'error';

    private $message, $context, $event, $level, $dt;

    public function __construct(
        string $message,
        array $context = [],
        array $event = [],
        string $level = self::LOG_LEVEL_INFO,
        Carbon $dt = null
    ) {
        parent::__construct();

        $this->message = $message;
        $this->context = $context;
        $this->event = $event;
        $this->level = $level;
        $this->dt = $dt ?: Carbon::now()->tz('UTC')->format('Y-m-d\TH:i:s.u\Z');
    }

    public function handle()
    {
        return $this->json();
    }

    /**
     * Send a plain (multi-line) log
     *
     * @param string $message
     */
    public function json()
    {
        $data = [
            '$schema' => self::PAYLOAD_SCHEMA,
            'dt'      => $this->dt,
            'message' => $this->message,
            'level'   => $this->level,
        ];

        if (count($this->context)) {
            $data['context'] = $this->context;
        }
        if (count($this->event)) {
            $data['event'] = $this->event;
        }

        $options = [
            RequestOptions::JSON => [$data],
        ];

        return $this->doRequest(self::METHOD_POST, 'frames', $options);
    }

}
