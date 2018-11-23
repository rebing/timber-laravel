<?php

return [
    /**
     * Set this to false, if you don't want the requests to
     * actually be sent to Timber
     */
    'enabled' => env('TIMBER_ENABLED', true),

    'api_key'   => env('TIMBER_API_KEY'),
];