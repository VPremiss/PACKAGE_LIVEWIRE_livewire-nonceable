<?php

declare(strict_types=1);

use Illuminate\Support\Carbon;
use Workbench\App\Livewire\Test;

use function Pest\Livewire\livewire;

it('can process the the security nonce properly', function () {
    auth()->loginUsingId(1);

    Carbon::setTestNow($initialTime = Carbon::now());

    $component = livewire(Test::class)
        ->assertViewHas('works', false)
        ->dispatch('preparation-requested');

    $nonce = $component->instance()->getGeneratedNonce();
    
    Carbon::setTestNow($initialTime->copy()->addSeconds(7)); // ? Before the 10 second expiration
    
    $component->call('test', $nonce);
    
    $component
        ->refresh()
        ->assertViewHas('works', true);
});

it('generates a valid nonce for only a specific period of time', function () {
    auth()->loginUsingId(1);

    Carbon::setTestNow($initialTime = Carbon::now());

    $component = livewire(Test::class)
        ->assertViewHas('works', false)
        ->dispatch('preparation-requested');

    $nonce = $component->instance()->getGeneratedNonce();
    
    Carbon::setTestNow($initialTime->copy()->addSeconds(15)); // ? After the 10 second expiration
    
    $component->call('test', $nonce);
})->throws(Exception::class, 'Stop it. Get some semicolons!');
