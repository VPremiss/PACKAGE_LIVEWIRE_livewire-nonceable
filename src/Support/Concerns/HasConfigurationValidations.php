<?php

declare(strict_types=1);

namespace VPremiss\LivewireNonceable\Support\Concerns;

use Closure;
use VPremiss\Crafty\Utilities\Configurated\Exceptions\ConfiguratedValidatedConfigurationException;

trait HasConfigurationValidations
{
    protected function validateKeyAttributesLengthConfig($value): void
    {
        if (!is_int($value)) {
            throw new ConfiguratedValidatedConfigurationException(
                'The configuration INTEGER for "key attributes length" is not found!'
            );
        }
    }

    protected function validateThrowIfLongConfig($value): void
    {
        if (!is_bool($value)) {
            throw new ConfiguratedValidatedConfigurationException(
                'The configuration BOOLEAN for "throwing if key attributes are long" is not found!'
            );
        }
    }
    
    protected function validateNonStringNonceReaction($value): void
    {
        if (!$value instanceof Closure) {
            throw new ConfiguratedValidatedConfigurationException(
                'The configuration CLOSURE for "non-string nonce detected reactive logic" is not found!'
            );
        }
    }
}
