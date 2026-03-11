<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between gap-4 flex-wrap">
        <div>
            <flux:heading size="xl">Katalog Produk</flux:heading>
            <flux:subheading>Temukan produk yang Anda butuhkan</flux:subheading>
        </div>
        <div class="w-full sm:w-72">
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
        <div class="flex flex-col items-center justify-center py-24 text-center">
            <div class="w-16 h-16 rounded-2xl bg-zinc-100 dark:bg-zinc-700 flex items-center justify-center mb-4">
                <flux:icon.archive-box class="size-8 text-zinc-400" />
            </div>
            <flux:heading>Tidak ada produk ditemukan</flux:heading>
            <flux:subheading class="mt-1">Coba ubah kata kunci pencarian Anda</flux:subheading>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($products as $product)
                <div class="group bg-white dark:bg-zinc-800 rounded-2xl border border-zinc-200 dark:border-zinc-700 overflow-hidden hover:shadow-lg hover:border-zinc-300 dark:hover:border-zinc-600 transition-all duration-200">
                    {{-- Gambar dengan rasio tetap --}}
                    <div class="relative w-full aspect-[4/3] bg-zinc-50 dark:bg-zinc-700 overflow-hidden">
                        @if($product->image)
                            <img
                                src="{{ asset('storage/' . $product->image) }}"
                                alt="{{ $product->name }}"
                                class="absolute inset-0 w-full h-full object-contain p-3 group-hover:scale-105 transition-transform duration-300"
                            />
                        @else
                            <div class="absolute inset-0 flex items-center justify-center">
                                <flux:icon.photo class="size-16 text-zinc-300 dark:text-zinc-600" />
                            </div>
                        @endif
                    </div>

                    {{-- Info --}}
                    <div class="p-4 border-t border-zinc-100 dark:border-zinc-700">
                        <h3 class="font-semibold text-zinc-900 dark:text-zinc-100 truncate text-base leading-snug">{{ $product->name }}</h3>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400 line-clamp-2 mt-1 mb-3 leading-relaxed">
                            {{ $product->description ?? 'Tidak ada deskripsi.' }}
                        </p>
                        <div class="flex items-center justify-between gap-2">
                            <span class="font-bold text-zinc-900 dark:text-white text-base">
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
