<?php

declare(strict_types=1);

namespace VPremiss\LivewireNonceable\Support\Concerns;

use VPremiss\Crafty\Facades\CraftyPackage;
use VPremiss\LivewireNonceable\LivewireNonceableServiceProvider;
use VPremiss\LivewireNonceable\Support\Exceptions\NoncenseException;

trait HasNoncingValidations
{
    private function validateTheNoncingInterface(): void
    {
        if (
            !method_exists(self::class, 'getNonces')
            || !method_exists(self::class, 'getNonceUniqueId')
        ) {
            throw new NoncenseException(
                'Noncing interface methods were not found! Please implement it on ' . self::class . ' Livewire component.'
            );
        }
    }

    private function validateTheNonceUniqueId(): void
    {
        if (empty($uniqueId = $this->getNonceUniqueId())) {
            throw new NoncenseException(
                'The Noncing unique ID was found in ' . self::class . ' Livewire component to be an empty string!'
            );
        }

        if (
            CraftyPackage::validatedConfig(
                'livewire-nonceable.throw_if_key_attributes_are_long',
                LivewireNonceableServiceProvider::class,
            )
        ) {
            $length = CraftyPackage::validatedConfig(
                'livewire-nonceable.key_attributes_length',
                LivewireNonceableServiceProvider::class,
            );

            if (strlen($uniqueId) > $length) {
                throw new NoncenseException(
                    'The Noncing unique ID was found in ' . self::class . " Livewire component to surpass the limit defined in configuration: ({$length})."
                );
            }
        }
    }

    private function validateTheNonces(): void
    {
        if (empty($nonces = $this->getNonces())) {
            throw new NoncenseException(
                'The Noncing nonces were found in ' . self::class . ' Livewire component to be an empty array!'
            );
        }

        foreach ($nonces as $key => $value) {
            if (!is_string($key) || empty($key)) {
                throw new NoncenseException(
                    'A key in Noncing nonces was found in ' . self::class . ' Livewire component to NOT be a string or empty! It is meant to be cache key title to lasting cache time in seconds; strings to integers.'
                );
            }

            if (
                CraftyPackage::validatedConfig(
                    'livewire-nonceable.throw_if_key_attributes_are_long',
                    LivewireNonceableServiceProvider::class,
                )
            ) {
                $length = CraftyPackage::validatedConfig(
                    'livewire-nonceable.key_attributes_length',
                    LivewireNonceableServiceProvider::class,
                );

                if (strlen($key) > $length) {
                    throw new NoncenseException(
                        'A key in Noncing nonces was found in ' . self::class . " Livewire component to be a long string! Longer than the length defined in configuration: ($length)."
                    );
                }
            }

            if (!is_int($value)) {
                throw new NoncenseException(
                    'A value in Noncing nonces was found in ' . self::class . ' Livewire component to NOT be an integer! It is meant to be cache key title to lasting cache time in seconds; strings to integers.'
                );
            }

            if ($value <= 0) {
                throw new NoncenseException(
                    'A value in Noncing nonces was found in ' . self::class . ' Livewire component to an integer less than zero! It is meant to be cache key title to lasting cache time in seconds; strings to integers. (MORE than zero, obviously!)'
                );
            }
        }
    }

    private function validateNonceTitle($title): void
    {
        if (!array_key_exists($title, $this->getNonces())) {
            throw new NoncenseException(
                "The given nonce title '$title' doesn't exist in " . self::class . " Livewire component's getNonces() titles-to-seconds array."
            );
        }
    }
}
