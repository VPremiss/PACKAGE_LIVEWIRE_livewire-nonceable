<?php

declare(strict_types=1);

namespace VPremiss\LivewireNonceable;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use VPremiss\Crafty\Utilities\Configurated\Interfaces\Configurated;
use VPremiss\Crafty\Utilities\Configurated\Traits\ManagesConfigurations;
use VPremiss\LivewireNonceable\Support\Concerns\HasConfigurationValidations;

class LivewireNonceableServiceProvider extends PackageServiceProvider implements Configurated
{
    use ManagesConfigurations;
    use HasConfigurationValidations;

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

    public function packageRegistered()
    {
        $this->registerConfigurations();
    }

    public function configurationValidations(): array
    {
        return [
            'livewire-nonceable' => [
                'key_attributes_length' => fn ($value) => $this->validateKeyAttributesLengthConfig($value),
                'throw_if_key_attributes_are_long' => fn ($value) => $this->validateThrowIfLongConfig($value),
            ],
        ];
    }
}
