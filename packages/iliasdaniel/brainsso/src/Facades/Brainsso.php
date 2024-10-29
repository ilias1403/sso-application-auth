<?php

namespace Iliasdaniel\Brainsso\Facades;

use Illuminate\Support\Facades\Facade;

class Brainsso extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'brainsso';
    }
}
