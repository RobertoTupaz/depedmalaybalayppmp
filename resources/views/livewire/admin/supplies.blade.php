<?php

use App\Models\Category;
use App\Models\Supply;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public string $search = '';
    public string $categoryFilter = '';

    public bool $showModal = false;
    public ?int $editingId = null;

    public string $item = '';
    public string $unitOfMeasure = '';
    public string $unitPrice = '';
    public string $categoryId = '';

    public ?int $deletingId = null;

    #[\Livewire\Attributes\Computed]
    public function supplies()
    {
        return Supply::with('category')
            ->when($this->search, fn ($q) => $q->where('item', 'like', '%' . $this->search . '%'))
            ->when($this->categoryFilter, fn ($q) => $q->where('category_id', $this->categoryFilter))
            ->orderBy('item')
            ->paginate(25);
    }

    #[\Livewire\Attributes\Computed]
    public function categories()
    {
        return Category::orderBy('name')->get();
    }

    public function openCreate(): void
    {
        $this->editingId = null;
        $this->item = '';
        $this->unitOfMeasure = '';
        $this->unitPrice = '';
        $this->categoryId = '';
        $this->resetErrorBag();
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $supply = Supply::findOrFail($id);
        $this->editingId = $id;
        $this->item = $supply->item;
        $this->unitOfMeasure = $supply->unit_of_measure;
        $this->unitPrice = (string) $supply->unit_price;
        $this->categoryId = (string) $supply->category_id;
        $this->resetErrorBag();
        $this->showModal = true;
    }

    public function save(): void
    {
        $data = $this->validate([
            'item'          => ['required', 'string'],
            'unitOfMeasure' => ['required', 'string', 'max:30'],
            'unitPrice'     => ['required', 'numeric', 'min:0.01'],
            'categoryId'    => ['required', 'exists:categories,id'],
        ]);

        $payload = [
            'item'            => $data['item'],
            'unit_of_measure' => $data['unitOfMeasure'],
            'unit_price'      => $data['unitPrice'],
            'category_id'     => $data['categoryId'],
        ];

        if ($this->editingId) {
            Supply::findOrFail($this->editingId)->update($payload);
        } else {
            Supply::create($payload);
        }

        $this->showModal = false;
        unset($this->supplies);
        Flux::toast($this->editingId ? 'Supply updated.' : 'Supply added.');
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;
    }

    public function deleteSupply(): void
    {
        if ($this->deletingId) {
            Supply::findOrFail($this->deletingId)->delete();
            $this->deletingId = null;
            unset($this->supplies);
            Flux::toast('Supply deleted.');
        }
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedCategoryFilter(): void
    {
        $this->resetPage();
    }
}; ?>

<div class="contents">
    <flux:main>
            <div class="mb-6 flex items-center justify-between">
                <flux:heading size="xl">Supplies</flux:heading>
                <flux:button variant="primary" icon="plus" wire:click="openCreate">Add Supply</flux:button>
            </div>

            <flux:card>
                <div class="mb-4 flex flex-wrap gap-3">
                    <flux:input
                        wire:model.live.debounce.300ms="search"
                        placeholder="Search supplies..."
                        icon="magnifying-glass"
                        class="flex-1"
                    />
                    <flux:select wire:model.live="categoryFilter" class="w-56" placeholder="All categories">
                        <flux:select.option value="">All categories</flux:select.option>
                        @foreach($this->categories as $category)
                            <flux:select.option value="{{ $category->id }}">{{ $category->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </div>

                <flux:table>
                    <flux:table.columns>
                        <flux:table.column class="w-1/2">Item</flux:table.column>
                        <flux:table.column>Category</flux:table.column>
                        <flux:table.column>Unit</flux:table.column>
                        <flux:table.column>Unit Price</flux:table.column>
                        <flux:table.column>MUP (×1.1)</flux:table.column>
                        <flux:table.column>Updated</flux:table.column>
                        <flux:table.column></flux:table.column>
                    </flux:table.columns>
                    <flux:table.rows>
                        @forelse($this->supplies as $supply)
                            <flux:table.row :key="$supply->id">
                                <flux:table.cell>{{ $supply->item }}</flux:table.cell>
                                <flux:table.cell>
                                    <flux:badge size="sm" color="zinc" inset="top bottom">
                                        {{ $supply->category->name }}
                                    </flux:badge>
                                </flux:table.cell>
                                <flux:table.cell>{{ $supply->unit_of_measure }}</flux:table.cell>
                                <flux:table.cell variant="strong">₱{{ number_format($supply->unit_price, 2) }}</flux:table.cell>
                                <flux:table.cell class="text-green-700 dark:text-green-400">
                                    ₱{{ number_format($supply->markedUpPrice(), 2) }}
                                </flux:table.cell>
                                <flux:table.cell class="text-xs text-zinc-400">
                                    {{ $supply->updated_at->format('M j, Y') }}
                                </flux:table.cell>
                                <flux:table.cell>
                                    <div class="flex gap-1">
                                        <flux:button size="sm" variant="ghost" icon="pencil" wire:click="openEdit({{ $supply->id }})">Edit</flux:button>
                                        <flux:button size="sm" variant="ghost" icon="trash" class="text-red-500" wire:click="confirmDelete({{ $supply->id }})">Delete</flux:button>
                                    </div>
                                </flux:table.cell>
                            </flux:table.row>
                        @empty
                            <flux:table.row>
                                <flux:table.cell colspan="7" class="py-8 text-center text-zinc-400">
                                    No supplies found.
                                </flux:table.cell>
                            </flux:table.row>
                        @endforelse
                    </flux:table.rows>
                </flux:table>

                <div class="mt-4">
                    {{ $this->supplies->links() }}
                </div>
            </flux:card>
    </flux:main>

    {{-- Add / Edit Modal --}}
    <flux:modal wire:model="showModal" class="md:w-[480px]">
        <div class="space-y-4">
            <flux:heading size="lg">{{ $editingId ? 'Edit Supply' : 'Add Supply' }}</flux:heading>

            <flux:field>
                <flux:label>Item Description</flux:label>
                <flux:textarea wire:model="item" rows="2" />
                <flux:error name="item" />
            </flux:field>

            <flux:field>
                <flux:label>Category</flux:label>
                <flux:select wire:model="categoryId" placeholder="Select category...">
                    @foreach($this->categories as $category)
                        <flux:select.option value="{{ $category->id }}">{{ $category->name }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:error name="categoryId" />
            </flux:field>

            <div class="grid grid-cols-2 gap-4">
                <flux:field>
                    <flux:label>Unit of Measure</flux:label>
                    <flux:input wire:model="unitOfMeasure" placeholder="e.g. piece, pack" />
                    <flux:error name="unitOfMeasure" />
                </flux:field>

                <flux:field>
                    <flux:label>Unit Price (₱)</flux:label>
                    <flux:input type="number" wire:model="unitPrice" step="0.01" min="0.01" />
                    <flux:error name="unitPrice" />
                </flux:field>
            </div>

            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button variant="primary" wire:click="save">
                    {{ $editingId ? 'Update' : 'Add Supply' }}
                </flux:button>
            </div>
        </div>
    </flux:modal>

    {{-- Delete Confirm --}}
    <flux:modal wire:model.boolean="deletingId" class="md:w-80">
        <div class="space-y-4">
            <flux:heading>Delete supply?</flux:heading>
            <flux:text>This cannot be undone. Any orders for this supply will also be removed.</flux:text>
            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button variant="danger" wire:click="deleteSupply">Delete</flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:toast />
</div>
