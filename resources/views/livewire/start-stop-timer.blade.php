<div>
    <button wire:click="toggleTimer" class="{{ $isRunning ? 'bg-red-600 hover:bg-red-800' : 'bg-green-600 hover:bg-green-800' }} text-white font-bold py-2 px-6 rounded-full transition duration-300 ease-in-out flex items-center">
        @if ($isRunning)
            <span>Stop Timer</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 2C5.589 2 2 5.589 2 10c0 4.41 3.589 8 8 8s8-3.59 8-8c0-4.411-3.589-8-8-8zM8 14.586L12.586 10 8 5.414l1.414-1.414L15.414 10l-6.586 6.586L8 14.586z" clip-rule="evenodd" />
            </svg>
        @else
            <span>Start Timer</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 2C5.589 2 2 5.589 2 10c0 4.41 3.589 8 8 8s8-3.59 8-8c0-4.411-3.589-8-8-8zM9 14V6l6 4-6 4z" clip-rule="evenodd" />
            </svg>
        @endif
    </button>
</div>