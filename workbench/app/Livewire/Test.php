<?php

declare(strict_types=1);

namespace Workbench\App\Livewire;

use Exception;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;
use VPremiss\LivewireNonceable\Interfaces\Noncing;
use VPremiss\LivewireNonceable\Traits\Nonceable;

class Test extends Component implements Noncing
{
    use Nonceable;

    #[Locked]
    public $works = false;

    public function getNonces(): array
    {
        return ['testing' => 10];
    }

    public function getNonceUniqueId(): string
    {
        return (string)auth()->user()->id;
    }

    public function getNonceValidation(): bool
    {
        return auth()->user()->id === $this->getNonceUniqueId();
    }

    #[On('preparation-requested')]
    public function begin()
    {
        $this->prepare();
    }

    protected function prepare()
    {
        $nonce = $this->generateNonce('testing');

        $this->dispatch('preparation-began', ['nonce' => $nonce]);
    }

    // TODO Fix when Livewire is able to test by communicating with the views back and forth
    // ! Simulated because Livewire does not respect view currently, as I understand
    public function getGeneratedNonce(): string
    {
        return $this->generateNonce('testing');
    }
    
    public function test($nonce)
    {
        if (!$this->validatedNonce('testing', $nonce)) {
            throw new Exception('Stop it. Get some semicolons!');
        }

        $this->works = true;
    }

    public function render()
    {
        return view('livewire.test');
    }
}
