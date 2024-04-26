<?php

declare(strict_types=1);

namespace VPremiss\LivewireNonceable\Traits;

use Illuminate\Support\Facades\Redis; // TODO what if `predis` wasn't installed?
use Illuminate\Support\Str;
use VPremiss\LivewireNonceable\Support\Concerns\HasNoncingValidations;
use VPremiss\LivewireNonceable\Support\Exceptions\NoncenseException;

trait Nonceable
{
    use HasNoncingValidations;

    public array $nonces;
    public string $nonceUniqueId;

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
        $this->nonceUniqueId = $this->formatCacheKey($this->getNonceUniqueId());
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
        return "nonce:$formattedTitle:{$this->nonceUniqueId}:$nonce";
    }

    protected function generateNonce(string $title): string
    {
        list($formattedTitle, $seconds) = $this->getNonceByTitle($title);

        $nonce = $this->generateNonceString();

        Redis::setex($this->formCacheKey($formattedTitle, $nonce), $seconds, '');

        return $nonce;
    }

    protected function deleteNonce(string $title, string $nonce): void
    {
        list($formattedTitle, $_) = $this->getNonceByTitle($title);

        if ($this->isNonceSense($title, $nonce)) {
            throw new NoncenseException('Could not find the a cachced nonce! Could not delete what was not found! :)');
        }

        Redis::del($this->formCacheKey($formattedTitle, $nonce));
    }

    public function doesNonceExist(string $title, string $nonce): bool
    {
        list($formattedTitle, $_) = $this->getNonceByTitle($title);

        return Redis::exists($this->formCacheKey($formattedTitle, $nonce));
    }

    public function isNonceSense(string $title, string $nonce): bool
    {
        return !$this->doesNonceExist($title, $nonce);
    }
}
