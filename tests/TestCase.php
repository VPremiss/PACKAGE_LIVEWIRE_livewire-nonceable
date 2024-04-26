<?php

declare(strict_types=1);

namespace VPremiss\LivewireNonceable\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use VPremiss\LivewireNonceable\LivewireNonceableServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            LivewireNonceableServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
    }
}
