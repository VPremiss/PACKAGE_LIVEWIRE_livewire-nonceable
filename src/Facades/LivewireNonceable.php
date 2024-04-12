<?php

namespace VPremiss\LivewireNonceable\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \VPremiss\LivewireNonceable\LivewireNonceable
 */
class LivewireNonceable extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \VPremiss\LivewireNonceable\LivewireNonceable::class;
    }
}
