<?php

use App\Models\Office;
use Livewire\Volt\Component;

new class extends Component {
    public string $selectedOfficeId = '';

    #[\Livewire\Attributes\Computed]
    public function offices()
    {
        return Office::orderBy('name')->get(['id', 'name', 'group']);
    }

    public function proceed(): void
    {
        $this->validate(['selectedOfficeId' => ['required', 'exists:offices,id']]);
        $this->redirect(route('ppmp.orders', $this->selectedOfficeId));
    }
}; ?>

<div class="contents">
    <flux:main class="flex items-center justify-center min-h-[60vh]">
        <flux:card class="w-full max-w-md">
            <flux:heading size="xl">PPMP</flux:heading>
            <flux:text class="mt-1 mb-6">Project Procurement Management Plan</flux:text>

            <form wire:submit="proceed" class="space-y-4">
                <flux:field>
                    <flux:label>Select your office</flux:label>
                    <flux:select wire:model="selectedOfficeId" placeholder="Choose office...">
                        @foreach ($this->offices as $office)
                            <flux:select.option value="{{ $office->id }}">{{ $office->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:error name="selectedOfficeId" />
                </flux:field>

                <flux:button type="submit" variant="primary" class="w-full">
                    Continue
                </flux:button>
            </form>
        </flux:card>
    </flux:main>
</div>