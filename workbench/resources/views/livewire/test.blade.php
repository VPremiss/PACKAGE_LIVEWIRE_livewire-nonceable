<div>
    <button
        type="button"
        wire:click="$wire.dispatch('preparation-requested')"
    >
        Prepare
    </button>

    <button
        type="button"
        x-on:preparation-began.window="setTimeout(() => $wire.test($event.detail.nonce), 5000)"
    >
        Test
    </button>

    <span>{{ $works ? 'done!' : 'testing...' }}</span>
</div>
