<?php

namespace Rebing\Timber\Requests;

use Carbon\Carbon;
use GuzzleHttp\RequestOptions;
use Rebing\Timber\Timber;

class LogLine extends Timber
{

    const PAYLOAD_SCHEMA = 'https://raw.githubusercontent.com/timberio/log-event-json-schema/v4.1.0/schema.json';

    const LOG_LEVEL_DEBUG = 'debug';
    const LOG_LEVEL_INFO = 'info';
    const LOG_LEVEL_WARN = 'warn';
    const LOG_LEVEL_ERROR = 'error';

    /**
     * Send a plain (multi-line) log
     *
     * @param string $message
     */
    public function json(string $message, array $context = [], array $event = [], string $level = self::LOG_LEVEL_INFO)
    {
        $data = [
            '$schema' => self::PAYLOAD_SCHEMA,
            'dt'      => Carbon::now()->toIso8601ZuluString(),
            'message' => $message,
            'level'   => $level,
        ];

        if (count($context)) {
            $data['context'] = $context;
        }
        if (count($event)) {
            $data['event'] = $event;
        }

        $options = [
            RequestOptions::JSON => [$data],
        ];
        dd($data);

        return $this->doRequest(self::METHOD_POST, 'frames', $options);
    }

}