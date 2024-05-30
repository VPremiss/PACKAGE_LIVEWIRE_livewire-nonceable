<?php

declare(strict_types=1);

namespace VPremiss\LivewireNonceable\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use VPremiss\Crafty\Facades\CraftyPackage;
use VPremiss\LivewireNonceable\Support\Concerns\HasNoncingValidations;
use VPremiss\LivewireNonceable\Support\Exceptions\NoncenseException;

trait Nonceable
{
    use HasNoncingValidations;

    public array $nonces;

    public function mountNonceable()
    {
        $this->validateTheNoncingInterface();
        $this->validateTheNonceUniqueId();
        $this->validateTheNonces();

        $nonces = [];
        foreach ($this->getNonces() as $title => $secondsInCache) {
            $nonces[$this->formatCacheKey($title)] = $secondsInCache;
        }

        $this->nonces = $nonces;
    }

    private function formatCacheKey(string $key): string
    {
        return str($key)->kebab()->value();
    }

    private function generateNonceString(): string
    {
        return Str::random(40);
    }

    private function getNonceByTitle(string $title): array
    {
        $this->validateNonceTitle($title);

        $formattedTitle = $this->formatCacheKey($title);

        return [$formattedTitle, $this->nonces[$formattedTitle]]; // ? formattedTitle, seconds
    }

    private function formCacheKey(string $formattedTitle, string $nonce): string
    {
        $this->validateTheNonceUniqueId();

        $uniqueId = $this->formatCacheKey($this->getNonceUniqueId());

        return "nonce:$formattedTitle:{$uniqueId}:$nonce";
    }

    protected function generateNonce(string $title): string
    {
        list($formattedTitle, $seconds) = $this->getNonceByTitle($title);

        $nonce = $this->generateNonceString();

        Cache::put($this->formCacheKey($formattedTitle, $nonce), '', $seconds);

        return $nonce;
    }

    protected function deleteNonce(string $title, string $nonce): void
    {
        list($formattedTitle, $_) = $this->getNonceByTitle($title);

        if ($this->isNonceSense($title, $nonce)) {
            throw new NoncenseException('Could not find the a cachced nonce! Could not delete what was not found! :)');
        }

        Cache::forget($this->formCacheKey($formattedTitle, $nonce));
    }

    protected function validateNonce($nonce)
    {
        $minimumLength = CraftyPackage::getConfiguration('livewire-nonceable.key_attributes_length');
        $failed = Validator::make(
            ['nonce' => $nonce],
            ['nonce' => 'required|filled|string|min:' . $minimumLength],
        )->failed();

        if ($failed) {
            CraftyPackage::getConfiguration('livewire-nonceable.non_string_nonce_reaction')();
        };
    }

    public function doesNonceExist(string $title, $nonce): bool
    {
        $this->validateNonce($nonce);

        list($formattedTitle, $_) = $this->getNonceByTitle($title);

        return Cache::has($this->formCacheKey($formattedTitle, $nonce));
    }

    public function isNonceSense(string $title, $nonce): bool
    {
        $this->validateNonce($nonce);

        return !$this->doesNonceExist($title, $nonce);
    }

    public function validatedNonce(string $title, $nonce): bool
    {
        $this->validateNonce($nonce);

        if (!$this->doesNonceExist($title, $nonce)) {
            return false;
        }

        $this->deleteNonce($title, $nonce);

        return true;
    }
}
