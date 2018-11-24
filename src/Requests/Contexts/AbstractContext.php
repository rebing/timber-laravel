<?php

namespace Rebing\Timber\Requests\Contexts;

abstract class AbstractContext {

    abstract public function getData(): array;
}