<?php

declare(strict_types=1);

namespace VPremiss\LivewireNonceable\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Attributes\WithMigration;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as Orchestra;
use Workbench\App\Models\User;

#[WithMigration('laravel', 'session')]
class TestCase extends Orchestra
{
    use WithWorkbench;
    use RefreshDatabase;

    protected function setUp(): void
    {
        $this->afterApplicationCreated(function () {
            User::create([
                'name' => 'Tester',
                'email' => 'tester@laravel.com',
                'password' => 'password',
            ]);
        });

        parent::setUp();
    }

    public function getEnvironmentSetUp($app)
    {
        $this->enforceConfigurations();
    }

    protected function enforceConfigurations()
    {
        config()->set('app.key', 'base64:' . base64_encode(random_bytes(32)));

        $localTimezone = 'Asia/Riyadh';
        config()->set('app.timezone', !env('IN_CI') ? $localTimezone : 'UTC');

        config()->set('app.locale', 'ar');
        config()->set('app.faker_locale', 'ar_SA');

        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', ':memory:');

        config()->set('cache.default', 'array');

        config()->set('session.driver', 'database');
        config()->set('session.encrypt', true);
    }
}
