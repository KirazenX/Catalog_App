<x-layouts::app :title="$product->name">
    <div class="max-w-4xl mx-auto p-6 space-y-6">
        <flux:link :href="route('catalog')" wire:navigate class="flex items-center gap-1 text-sm">
            <flux:icon.arrow-left class="size-4" />
            Kembali ke Katalog
        </flux:link>

        <div class="bg-white dark:bg-zinc-800 rounded-xl border border-zinc-200 dark:border-zinc-700 overflow-hidden">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-72 object-cover" />
            @else
                <div class="w-full h-72 bg-zinc-100 dark:bg-zinc-700 flex items-center justify-center">
                    <flux:icon.photo class="size-24 text-zinc-300" />
                </div>
            @endif
            <div class="p-6">
                <flux:heading size="xl">{{ $product->name }}</flux:heading>
                <p class="text-2xl font-bold mt-2 mb-4">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                <flux:text>{{ $product->description ?? 'Tidak ada deskripsi.' }}</flux:text>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-800 rounded-xl border border-zinc-200 dark:border-zinc-700 p-6">
            <livewire:catalog.comment-section :productId="$product->id" />
        </div>
    </div>
</x-layouts::app>