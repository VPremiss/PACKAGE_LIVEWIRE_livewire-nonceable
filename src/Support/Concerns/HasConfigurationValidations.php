<?php

declare(strict_types=1);

namespace VPremiss\LivewireNonceable\Support\Concerns;

use VPremiss\Crafty\Utilities\Configurated\Exceptions\ConfiguratedValidatedConfigurationException;

trait HasConfigurationValidations
{
    protected function validateKeyAttributesLengthConfig($value): void
    {
        if (!is_int($value)) {
            throw new ConfiguratedValidatedConfigurationException(
                'The configuration integer for "key attributes length" is not found!'
            );
        }
    }

    protected function validateThrowIfLongConfig($value): void
    {
        if (!is_bool($value)) {
            throw new ConfiguratedValidatedConfigurationException(
                'The configuration boolean for "throwing if key attributes are long" is not found!'
            );
        }
    }
}
