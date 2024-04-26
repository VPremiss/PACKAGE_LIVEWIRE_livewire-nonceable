<?php

declare(strict_types=1);

namespace VPremiss\LivewireNonceable\Support\Concerns;

use VPremiss\Crafty\Facades\CraftyPackage;
use VPremiss\Crafty\Utilities\Configurated\Exceptions\ConfiguratedValidatedConfigurationException;

trait HasValidatedConfiguration
{
    protected function validateKeyAttributesLengthConfig()
    {
        if (!is_int(CraftyPackage::config('livewire-nonceable.key_attributes_length', $this))) {
            throw new ConfiguratedValidatedConfigurationException(
                'The configuration integer for "key attributes length" is not found!'
            );
        }
    }

    protected function validateThrowIfLongConfig()
    {
        if (!is_bool(CraftyPackage::config('livewire-nonceable.throw_if_key_attributes_are_long', $this))) {
            throw new ConfiguratedValidatedConfigurationException(
                'The configuration boolean for "throwing if key attributes are long" is not found!'
            );
        }
    }
}
