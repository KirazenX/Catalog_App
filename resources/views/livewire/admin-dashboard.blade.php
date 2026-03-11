<div class="space-y-6">
    @if(!auth()->user()->is_admin)
        <div class="flex flex-col items-center justify-center py-24 text-center">
            <div class="w-20 h-20 rounded-2xl bg-red-100 dark:bg-red-900/30 flex items-center justify-center mb-4">
                <flux:icon.lock-closed class="size-10 text-red-500" />
            </div>
            <flux:heading size="xl" class="mb-2">Akses Ditolak</flux:heading>
            <flux:subheading class="max-w-sm">Halaman dashboard hanya dapat diakses oleh administrator.</flux:subheading>
            <div class="mt-6">
                <flux:button href="{{ route('catalog') }}" variant="primary" wire:navigate>
                    Ke Katalog Produk
                </flux:button>
            </div>
        </div>
    @else
        {{-- Header --}}
        <div>
            <flux:heading size="xl">Dashboard</flux:heading>
            <flux:subheading>Selamat datang, {{ auth()->user()->name }}. Berikut ringkasan aplikasi katalog.</flux:subheading>
        </div>

        {{-- Stat Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Total Produk --}}
            <div class="bg-white dark:bg-zinc-800 rounded-2xl border border-zinc-200 dark:border-zinc-700 p-5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center shrink-0">
                    <flux:icon.archive-box class="size-6 text-blue-600 dark:text-blue-400" />
                </div>
                <div>
                    <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $totalProducts }}</p>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">Total Produk</p>
                </div>
            </div>

            {{-- Total Pengguna --}}
            <div class="bg-white dark:bg-zinc-800 rounded-2xl border border-zinc-200 dark:border-zinc-700 p-5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center shrink-0">
                    <flux:icon.users class="size-6 text-emerald-600 dark:text-emerald-400" />
                </div>
                <div>
                    <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $totalUsers }}</p>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">Total Pengguna</p>
                </div>
            </div>

            {{-- Total Komentar --}}
            <div class="bg-white dark:bg-zinc-800 rounded-2xl border border-zinc-200 dark:border-zinc-700 p-5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-900/40 flex items-center justify-center shrink-0">
                    <flux:icon.chat-bubble-left-right class="size-6 text-amber-600 dark:text-amber-400" />
                </div>
                <div>
                    <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $totalComments }}</p>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">Total Komentar</p>
                </div>
            </div>

            {{-- Total Admin --}}
            <div class="bg-white dark:bg-zinc-800 rounded-2xl border border-zinc-200 dark:border-zinc-700 p-5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-purple-100 dark:bg-purple-900/40 flex items-center justify-center shrink-0">
                    <flux:icon.shield-check class="size-6 text-purple-600 dark:text-purple-400" />
                </div>
                <div>
                    <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $totalAdmins }}</p>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">Total Admin</p>
                </div>
            </div>
        </div>

        {{-- Bottom Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Produk Terbaru --}}
            <div class="bg-white dark:bg-zinc-800 rounded-2xl border border-zinc-200 dark:border-zinc-700 overflow-hidden">
                <div class="px-5 py-4 border-b border-zinc-200 dark:border-zinc-700 flex items-center justify-between">
                    <flux:heading size="base">Produk Terbaru</flux:heading>
                    <flux:button href="{{ route('admin.products') }}" size="sm" variant="ghost" wire:navigate>
                        Lihat Semua
                    </flux:button>
                </div>
                <div class="divide-y divide-zinc-100 dark:divide-zinc-700">
                    @forelse($latestProducts as $product)
                        <div class="flex items-center gap-3 px-5 py-3">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}"
                                     class="w-10 h-10 rounded-lg object-cover shrink-0" />
                            @else
                                <div class="w-10 h-10 rounded-lg bg-zinc-100 dark:bg-zinc-700 flex items-center justify-center shrink-0">
                                    <flux:icon.photo class="size-5 text-zinc-400" />
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium truncate text-zinc-800 dark:text-zinc-200">{{ $product->name }}</p>
                                <p class="text-xs text-zinc-500">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            </div>
                            <p class="text-xs text-zinc-400 shrink-0">{{ $product->created_at->diffForHumans() }}</p>
                        </div>
                    @empty
                        <div class="px-5 py-8 text-center text-zinc-400 text-sm">Belum ada produk.</div>
                    @endforelse
                </div>
            </div>

            {{-- Produk Paling Banyak Dikomentari --}}
            <div class="bg-white dark:bg-zinc-800 rounded-2xl border border-zinc-200 dark:border-zinc-700 overflow-hidden">
                <div class="px-5 py-4 border-b border-zinc-200 dark:border-zinc-700">
                    <flux:heading size="base">Produk Terpopuler</flux:heading>
                    <flux:subheading class="text-xs">Berdasarkan jumlah komentar</flux:subheading>
                </div>
                <div class="divide-y divide-zinc-100 dark:divide-zinc-700">
                    @forelse($productWithMostComments as $i => $product)
                        <div class="flex items-center gap-3 px-5 py-3">
                            <span class="w-6 h-6 rounded-full text-xs font-bold flex items-center justify-center shrink-0
                                {{ $i === 0 ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-400' : 'bg-zinc-100 text-zinc-500 dark:bg-zinc-700 dark:text-zinc-400' }}">
                                {{ $i + 1 }}
                            </span>
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}"
                                     class="w-10 h-10 rounded-lg object-cover shrink-0" />
                            @else
                                <div class="w-10 h-10 rounded-lg bg-zinc-100 dark:bg-zinc-700 flex items-center justify-center shrink-0">
                                    <flux:icon.photo class="size-5 text-zinc-400" />
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium truncate text-zinc-800 dark:text-zinc-200">{{ $product->name }}</p>
                                <p class="text-xs text-zinc-500">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            </div>
                            <span class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 shrink-0 bg-zinc-100 dark:bg-zinc-700 px-2 py-1 rounded-full">
                                {{ $product->comments_count }} komentar
                            </span>
                        </div>
                    @empty
                        <div class="px-5 py-8 text-center text-zinc-400 text-sm">Belum ada data.</div>
                    @endforelse
                </div>
            </div>

            {{-- Komentar Terbaru --}}
            <div class="bg-white dark:bg-zinc-800 rounded-2xl border border-zinc-200 dark:border-zinc-700 overflow-hidden lg:col-span-2">
                <div class="px-5 py-4 border-b border-zinc-200 dark:border-zinc-700">
                    <flux:heading size="base">Komentar Terbaru</flux:heading>
                </div>
                <div class="divide-y divide-zinc-100 dark:divide-zinc-700">
                    @forelse($latestComments as $comment)
                        <div class="flex items-start gap-3 px-5 py-3">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center text-white text-xs font-bold shrink-0">
                                {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="text-sm font-medium text-zinc-800 dark:text-zinc-200">{{ $comment->user->name }}</span>
                                    <span class="text-xs text-zinc-400">pada</span>
                                    <span class="text-xs text-blue-600 dark:text-blue-400 truncate max-w-[120px]">{{ $comment->product->name }}</span>
                                </div>
                                <p class="text-sm text-zinc-600 dark:text-zinc-300 mt-0.5 line-clamp-1">{{ $comment->body }}</p>
                            </div>
                            <p class="text-xs text-zinc-400 shrink-0">{{ $comment->created_at->diffForHumans() }}</p>
                        </div>
                    @empty
                        <div class="px-5 py-8 text-center text-zinc-400 text-sm">Belum ada komentar.</div>
                    @endforelse
                </div>
            </div>

        </div>
    @endif
</div>
