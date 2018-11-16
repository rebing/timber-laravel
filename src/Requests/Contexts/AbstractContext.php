<?php

namespace Rebing\Timber\Requests\Contexts;

use Rebing\Timber\Requests\RequestIdTrait;

abstract class AbstractContext
{
    use RequestIdTrait;

    abstract public function getData(): array;
}