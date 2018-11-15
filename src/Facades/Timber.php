<?php

namespace Rebing\Timber\Facades;

use Illuminate\Support\Facades\Facade;

class Timber extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'timber';
    }
}
