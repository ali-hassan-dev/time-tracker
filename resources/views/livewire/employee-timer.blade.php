<div>
    <button wire:click="toggleTimer"
        class="{{ $isRunning ? 'bg-red-600 hover:bg-red-800' : 'bg-green-600 hover:bg-green-800' }} text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
        {{ $isRunning ? 'Stop Timer' : 'Start Timer' }}
    </button>
</div>