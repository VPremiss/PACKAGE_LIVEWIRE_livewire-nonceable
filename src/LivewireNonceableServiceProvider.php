<?php

declare(strict_types=1);

namespace VPremiss\LivewireNonceable;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use VPremiss\Crafty\Utilities\Configurated\Interfaces\Configurated;
use VPremiss\LivewireNonceable\Support\Concerns\HasValidatedConfiguration;

class LivewireNonceableServiceProvider extends PackageServiceProvider implements Configurated
{
    use HasValidatedConfiguration;

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('livewire-nonceable')
            ->hasConfigFile();
    }

    public function configValidation(string $configKey): void
    {
        match ($configKey) {
            'livewire-nonceable.key_attributes_length' => $this->validateKeyAttributesLengthConfig(),
            'livewire-nonceable.throw_if_key_attributes_are_long' => $this->validateThrowIfLongConfig(),
        };
    }

    public function configDefault(string $configKey): mixed
    {
        return match ($configKey) {
            'livewire-nonceable.key_attributes_length' => 40,
            'livewire-nonceable.throw_if_key_attributes_are_long' => false,
        };
    }
}
