<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">Katalog Produk</flux:heading>
            <flux:subheading>Temukan produk yang Anda butuhkan</flux:subheading>
        </div>
        <div class="w-72">
            <flux:input
                wire:model.live.debounce.300ms="search"
                placeholder="Cari produk..."
                icon="magnifying-glass"
                clearable
            />
        </div>
    </div>

    {{-- Grid --}}
    @if($products->isEmpty())
        <div class="flex flex-col items-center justify-center py-20 text-center">
            <flux:icon.archive-box class="size-16 text-zinc-300 mb-4" />
            <flux:heading>Tidak ada produk ditemukan</flux:heading>
            <flux:subheading>Coba ubah kata kunci pencarian Anda</flux:subheading>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($products as $product)
                <div class="bg-white dark:bg-zinc-800 rounded-xl border border-zinc-200 dark:border-zinc-700 overflow-hidden hover:shadow-md transition-shadow">
                    @if($product->image)
                        <img
                            src="{{ asset('storage/' . $product->image) }}"
                            alt="{{ $product->name }}"
                            class="w-full h-48 object-cover"
                        />
                    @else
                        <div class="w-full h-48 bg-zinc-100 dark:bg-zinc-700 flex items-center justify-center">
                            <flux:icon.photo class="size-16 text-zinc-300" />
                        </div>
                    @endif
                    <div class="p-4">
                        <flux:heading size="lg" class="truncate mb-1">{{ $product->name }}</flux:heading>
                        <flux:text class="line-clamp-2 text-sm mb-3">{{ $product->description ?? 'Tidak ada deskripsi.' }}</flux:text>
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-lg">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </span>
                            <flux:button
                                href="{{ route('products.show', $product) }}"
                                variant="primary"
                                size="sm"
                                wire:navigate
                            >
                                Lihat Detail
                            </flux:button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div>{{ $products->links() }}</div>
    @endif
</div>