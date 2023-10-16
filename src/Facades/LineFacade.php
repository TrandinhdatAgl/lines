<?php

namespace Lines\Lines\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * LineFacade
 */
class LineFacade extends Facade
{
    /**
     * getFacadeAccessor
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'line_facade';
    }
}
