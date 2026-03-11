<x-layouts::app :title="$product->name">
    <div class="max-w-4xl mx-auto px-4 py-8 space-y-6">

        {{-- Back link --}}
        <a href="{{ route('catalog') }}" wire:navigate
           class="inline-flex items-center gap-1.5 text-sm text-zinc-500 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-zinc-200 transition-colors no-underline">
            ← Kembali ke Katalog
        </a>

        {{-- Product Card --}}
        <div class="bg-white dark:bg-zinc-800 rounded-2xl border border-zinc-200 dark:border-zinc-700 overflow-hidden shadow-sm">

            {{-- Gambar full width di atas, tinggi tetap --}}
            <div class="w-full h-72 bg-zinc-50 dark:bg-zinc-700/50 flex items-center justify-center overflow-hidden">
                @if($product->image)
                    <img
                        src="{{ asset('storage/' . $product->image) }}"
                        alt="{{ $product->name }}"
                        class="w-full h-full object-contain p-4"
                    />
                @else
                    <div class="flex flex-col items-center gap-2 text-zinc-300 dark:text-zinc-600">
                        <flux:icon.photo class="size-20" />
                        <span class="text-sm">Tidak ada foto</span>
                    </div>
                @endif
            </div>

            {{-- Info produk --}}
            <div class="p-6 space-y-4 border-t border-zinc-100 dark:border-zinc-700">
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $product->name }}</h1>

                <p class="text-3xl font-extrabold text-blue-600 dark:text-blue-400">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </p>

                <div>
                    <p class="text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-1.5">Deskripsi</p>
                    <p class="text-zinc-700 dark:text-zinc-300 leading-relaxed text-sm">
                        {{ $product->description ?? 'Tidak ada deskripsi untuk produk ini.' }}
                    </p>
                </div>

                <p class="text-xs text-zinc-400 dark:text-zinc-500 pt-2 border-t border-zinc-100 dark:border-zinc-700">
                    Ditambahkan {{ $product->created_at->diffForHumans() }}
                </p>
            </div>
        </div>

        {{-- Comment Section --}}
        <div class="bg-white dark:bg-zinc-800 rounded-2xl border border-zinc-200 dark:border-zinc-700 overflow-hidden shadow-sm">
            <div class="px-6 py-4 border-b border-zinc-100 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-700/30">
                <h2 class="text-base font-semibold text-zinc-800 dark:text-zinc-200">Komentar</h2>
            </div>
            <div class="p-6">
                <livewire:catalog.comment-section :productId="$product->id" />
            </div>
        </div>

    </div>
</x-layouts::app>
