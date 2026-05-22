<?php

use App\Models\Category;
use App\Models\Office;
use App\Models\Order;
use App\Models\Supply;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public Office $office;

    public bool $showCartModal = false;
    public ?int $editingOrderId = null;
    public ?int $selectedSupplyId = null;
    public int $quantity = 1;
    public string $monthNeeded = '';

    public ?int $deletingOrderId = null;

    public string $search = '';
    public string $categoryFilter = '';

    public function mount(Office $office): void
    {
        $this->office = $office;
        $this->monthNeeded = now()->addMonth()->format('Y-m');
    }

    #[\Livewire\Attributes\Computed]
    public function cartOrders()
    {
        return $this->office->orders()->with('supply.category')->get();
    }

    #[\Livewire\Attributes\Computed]
    public function cartTotal(): float
    {
        return $this->cartOrders->sum(fn ($order) => $order->lineTotal());
    }

    #[\Livewire\Attributes\Computed]
    public function walletBalance(): float
    {
        return round((float) $this->office->allocation - $this->cartTotal, 2);
    }

    #[\Livewire\Attributes\Computed]
    public function supplies()
    {
        return Supply::with('category')
            ->when($this->search, fn ($q) => $q->where('item', 'like', '%' . $this->search . '%'))
            ->when($this->categoryFilter, fn ($q) => $q->where('category_id', $this->categoryFilter))
            ->orderBy('item')
            ->paginate(20);
    }

    #[\Livewire\Attributes\Computed]
    public function categories()
    {
        return Category::orderBy('name')->get();
    }

    public function openAddModal(int $supplyId): void
    {
        $this->editingOrderId = null;
        $this->selectedSupplyId = $supplyId;
        $this->quantity = 1;
        $this->monthNeeded = now()->addMonth()->format('Y-m');
        $this->resetErrorBag();
        $this->showCartModal = true;
    }

    public function openEditModal(int $orderId): void
    {
        $order = Order::findOrFail($orderId);
        $this->editingOrderId = $orderId;
        $this->selectedSupplyId = $order->supply_id;
        $this->quantity = $order->quantity;
        $this->monthNeeded = $order->month_needed;
        $this->resetErrorBag();
        $this->showCartModal = true;
    }

    public function saveCart(): void
    {
        $this->validate([
            'quantity'    => ['required', 'integer', 'min:1'],
            'monthNeeded' => ['required', 'date_format:Y-m'],
        ]);

        if ($this->editingOrderId) {
            Order::findOrFail($this->editingOrderId)->update([
                'quantity'     => $this->quantity,
                'month_needed' => $this->monthNeeded,
            ]);
        } else {
            $existing = Order::where('office_id', $this->office->id)
                ->where('supply_id', $this->selectedSupplyId)
                ->where('month_needed', $this->monthNeeded)
                ->exists();

            if ($existing) {
                $this->addError('monthNeeded', 'This item is already in your cart for that month.');

                return;
            }

            Order::create([
                'office_id'    => $this->office->id,
                'supply_id'    => $this->selectedSupplyId,
                'quantity'     => $this->quantity,
                'month_needed' => $this->monthNeeded,
            ]);
        }

        $this->showCartModal = false;
        unset($this->cartOrders, $this->cartTotal, $this->walletBalance);
    }

    public function confirmDelete(int $orderId): void
    {
        $this->deletingOrderId = $orderId;
    }

    public function deleteOrder(): void
    {
        if ($this->deletingOrderId) {
            Order::findOrFail($this->deletingOrderId)->delete();
            $this->deletingOrderId = null;
            unset($this->cartOrders, $this->cartTotal, $this->walletBalance);
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
    <flux:main class="max-w-full boder-1 border-red-500">

            {{-- Page header --}}
            <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
                <div>
                    <flux:heading size="xl">{{ $office->name }}</flux:heading>
                    <flux:text class="mt-1">
                        Budget: <strong>₱{{ number_format($office->allocation, 2) }}</strong>
                    </flux:text>
                </div>
                <flux:button href="{{ route('ppmp.select') }}" variant="ghost" icon="arrow-left" wire:navigate>
                    Change Office
                </flux:button>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-4 xl:grid-cols-5">

                {{-- Cart Sidebar --}}
                <div class="lg:col-span-1">
                    <flux:card class="sticky top-4">
                        <flux:heading size="lg" class="mb-4 flex items-center gap-2">
                            <flux:icon name="shopping-cart" class="size-5" />
                            Cart
                        </flux:heading>

                        @if($this->cartOrders->isEmpty())
                            <flux:text class="py-6 text-center text-zinc-400">No items yet.</flux:text>
                        @else
                            <div class="space-y-2">
                                @foreach($this->cartOrders as $order)
                                    <div class="rounded-lg border border-zinc-100 bg-zinc-50 p-3 dark:border-zinc-700 dark:bg-zinc-800">
                                        <p class="text-sm font-medium leading-tight">{{ $order->supply->item }}</p>
                                        <p class="mt-1 text-xs text-zinc-500">
                                            {{ $order->quantity }} × ₱{{ number_format($order->supply->markedUpPrice(), 2) }}
                                            &mdash; {{ \Carbon\Carbon::createFromFormat('Y-m', $order->month_needed)->format('M Y') }}
                                        </p>
                                        <p class="text-xs font-semibold text-zinc-700 dark:text-zinc-300">
                                            ₱{{ number_format($order->lineTotal(), 2) }}
                                        </p>
                                        <div class="mt-2 flex gap-1">
                                            <flux:button size="xs" variant="ghost" icon="pencil" wire:click="openEditModal({{ $order->id }})">Edit</flux:button>
                                            <flux:button size="xs" variant="ghost" icon="trash" class="text-red-500" wire:click="confirmDelete({{ $order->id }})">Remove</flux:button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <flux:separator class="my-4" />

                            <div class="space-y-1 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-zinc-600 dark:text-zinc-400">Total Ordered:</span>
                                    <span class="font-semibold">₱{{ number_format($this->cartTotal, 2) }}</span>
                                </div>
                                <div class="flex justify-between {{ $this->walletBalance < 0 ? 'text-red-600 dark:text-red-400' : 'text-green-700 dark:text-green-400' }}">
                                    <span>Balance:</span>
                                    <span class="font-bold">₱{{ number_format($this->walletBalance, 2) }}</span>
                                </div>
                            </div>

                            <div class="mt-4 space-y-2">
                                @if($this->walletBalance >= 0)
                                    <flux:button
                                        href="{{ route('reports.my-ppmp', $office) }}"
                                        variant="primary"
                                        class="w-full"
                                        icon="document-text"
                                        target="_blank"
                                    >
                                        View My PPMP
                                    </flux:button>
                                @endif
                                <flux:button
                                    href="{{ route('reports.group-ppmp', $office->group) }}"
                                    variant="ghost"
                                    class="w-full"
                                    target="_blank"
                                >
                                    Group PPMP
                                </flux:button>
                                <flux:button
                                    href="{{ route('reports.order-summary', $office->group) }}"
                                    variant="ghost"
                                    class="w-full"
                                    target="_blank"
                                >
                                    Order Summary
                                </flux:button>
                            </div>
                        @endif
                    </flux:card>
                </div>

                {{-- Supplies Table --}}
                <div class="lg:col-span-3 xl:col-span-4">
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
                                <flux:table.column>Item</flux:table.column>
                                <flux:table.column class="w-28">Category</flux:table.column>
                                <flux:table.column class="w-16">Unit</flux:table.column>
                                <flux:table.column class="w-28">Unit Price</flux:table.column>
                                <flux:table.column class="w-32">Mark-up Price</flux:table.column>
                                <flux:table.column class="w-20"></flux:table.column>
                            </flux:table.columns>
                            <flux:table.rows>
                                @forelse($this->supplies as $supply)
                                    <flux:table.row :key="$supply->id">
                                        <flux:table.cell class="whitespace-normal">{{ $supply->item }}</flux:table.cell>
                                        <flux:table.cell>
                                            <flux:badge size="sm" color="zinc" inset="top bottom">
                                                {{ $supply->category->name }}
                                            </flux:badge>
                                        </flux:table.cell>
                                        <flux:table.cell>{{ $supply->unit_of_measure }}</flux:table.cell>
                                        <flux:table.cell variant="strong">₱{{ number_format($supply->unit_price, 2) }}</flux:table.cell>
                                        <flux:table.cell variant="strong" class="text-green-700 dark:text-green-400">
                                            ₱{{ number_format($supply->markedUpPrice(), 2) }}
                                        </flux:table.cell>
                                        <flux:table.cell>
                                            <flux:button
                                                size="sm"
                                                variant="primary"
                                                icon="plus"
                                                wire:click="openAddModal({{ $supply->id }})"
                                            >
                                                Add
                                            </flux:button>
                                        </flux:table.cell>
                                    </flux:table.row>
                                @empty
                                    <flux:table.row>
                                        <flux:table.cell colspan="6" class="py-8 text-center text-zinc-400">
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
                </div>
            </div>

    </flux:main>

    {{-- Add / Edit Cart Modal --}}
    <flux:modal wire:model="showCartModal" class="md:w-96">
        @if($selectedSupplyId && ($cartSupply = \App\Models\Supply::find($selectedSupplyId)))
            <div class="space-y-4">
                <div>
                    <flux:heading size="lg">{{ $editingOrderId ? 'Edit Order' : 'Add to Cart' }}</flux:heading>
                    <flux:text class="mt-1 font-medium leading-tight">{{ $cartSupply->item }}</flux:text>
                </div>

                <flux:field>
                    <flux:label>Marked-up Price</flux:label>
                    <flux:input value="₱{{ number_format($cartSupply->markedUpPrice(), 2) }}" readonly />
                </flux:field>

                <flux:field>
                    <flux:label>Quantity</flux:label>
                    <flux:input type="number" wire:model="quantity" min="1" />
                    <flux:error name="quantity" />
                </flux:field>

                <flux:field>
                    <flux:label>Month Needed</flux:label>
                    <flux:input type="month" wire:model="monthNeeded" />
                    <flux:error name="monthNeeded" />
                </flux:field>

                <div class="flex justify-end gap-2">
                    <flux:modal.close>
                        <flux:button variant="ghost">Cancel</flux:button>
                    </flux:modal.close>
                    <flux:button variant="primary" wire:click="saveCart">
                        {{ $editingOrderId ? 'Update' : 'Add to Cart' }}
                    </flux:button>
                </div>
            </div>
        @endif
    </flux:modal>

    {{-- Delete Confirm Modal --}}
    <flux:modal wire:model.boolean="deletingOrderId" class="md:w-80">
        <div class="space-y-4">
            <flux:heading>Remove item?</flux:heading>
            <flux:text>This will remove the item from your cart.</flux:text>
            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button variant="danger" wire:click="deleteOrder">Remove</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
