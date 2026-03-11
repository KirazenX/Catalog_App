<div class="space-y-6">
    @if(!auth()->user()->is_admin)
        <flux:callout variant="danger" icon="x-circle" heading="Akses Ditolak">
            Halaman ini hanya untuk administrator.
        </flux:callout>
    @else
        {{-- Header --}}
        <div class="flex items-center justify-between gap-4 flex-wrap">
            <div>
                <flux:heading size="xl">Manajemen Produk</flux:heading>
                <flux:subheading>Kelola data produk katalog</flux:subheading>
            </div>
            <flux:button
                href="{{ route('admin.products.create') }}"
                variant="primary"
                icon="plus"
                wire:navigate
            >
                Tambah Produk
            </flux:button>
        </div>

        @if(session('success'))
            <flux:callout variant="success" icon="check-circle">{{ session('success') }}</flux:callout>
        @endif
        <x-action-message on="product-saved">Produk berhasil disimpan.</x-action-message>

        {{-- Tabel --}}
        <div class="rounded-2xl border border-zinc-700/60 overflow-hidden shadow-sm bg-zinc-800/60">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-zinc-700/50 border-b border-zinc-700/80">
                            <th class="px-5 py-3.5 text-left text-xs font-semibold text-zinc-400 uppercase tracking-wider" style="width:80px">Foto</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold text-zinc-400 uppercase tracking-wider">Nama Produk</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold text-zinc-400 uppercase tracking-wider" style="width:160px">Harga</th>
                            <th class="px-5 py-3.5 text-right text-xs font-semibold text-zinc-400 uppercase tracking-wider" style="width:160px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-700/50">
                        @forelse($products as $product)
                            <tr class="hover:bg-zinc-700/25 transition-colors">
                                <td class="px-5 py-3.5">
                                    <div class="w-14 h-14 rounded-xl overflow-hidden bg-zinc-700/50 flex items-center justify-center shrink-0 border border-zinc-600/40">
                                        @if($product->image)
                                            <img
                                                src="{{ asset('storage/' . $product->image) }}"
                                                alt="{{ $product->name }}"
                                                class="w-full h-full object-contain p-1.5"
                                            />
                                        @else
                                            <flux:icon.photo class="size-6 text-zinc-500" />
                                        @endif
                                    </div>
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="font-medium text-zinc-100">{{ $product->name }}</div>
                                    <div class="text-sm text-zinc-500 truncate max-w-xs mt-0.5">{{ $product->description ?? '-' }}</div>
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="text-sm font-semibold text-zinc-200">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <flux:button wire:click="openEdit({{ $product->id }})" size="sm" variant="filled">Edit</flux:button>
                                        <flux:button
                                            wire:click="delete({{ $product->id }})"
                                            wire:confirm="Yakin ingin menghapus produk '{{ $product->name }}'?"
                                            size="sm"
                                            variant="danger"
                                        >Hapus</flux:button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-16 text-center">
                                    <div class="flex flex-col items-center gap-2 text-zinc-500">
                                        <flux:icon.archive-box class="size-10" />
                                        <p class="text-sm">Belum ada produk.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-4 border-t border-zinc-700/60 bg-zinc-800/40">
                {{ $products->links() }}
            </div>
        </div>

        {{-- Modal Edit — lebih rapi & sesuai dark mode --}}
        @if($showModal)
            <div
                class="fixed inset-0 z-50 flex items-center justify-center p-4"
                style="background: rgba(0,0,0,0.65); backdrop-filter: blur(4px);"
            >
                <div class="bg-zinc-900 border border-zinc-700/80 rounded-2xl shadow-2xl w-full max-w-lg">
                    {{-- Modal Header --}}
                    <div class="flex items-center justify-between px-6 py-4 border-b border-zinc-700/60">
                        <div>
                            <h2 class="text-base font-semibold text-zinc-100">Edit Produk</h2>
                            <p class="text-xs text-zinc-500 mt-0.5">Perbarui informasi produk</p>
                        </div>
                        <button
                            wire:click="$set('showModal', false)"
                            class="w-8 h-8 rounded-lg flex items-center justify-center text-zinc-400 hover:text-zinc-200 hover:bg-zinc-700/60 transition-colors"
                        >
                            <flux:icon.x-mark class="size-4" />
                        </button>
                    </div>

                    {{-- Modal Body --}}
                    <div class="px-6 py-5 space-y-4">
                        {{-- Nama --}}
                        <div>
                            <label class="block text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-1.5">Nama Produk</label>
                            <input
                                wire:model="name"
                                type="text"
                                placeholder="Nama produk"
                                class="w-full bg-zinc-800 border border-zinc-600/60 rounded-xl px-4 py-2.5 text-sm text-zinc-100 placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition"
                            />
                            @error('name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Deskripsi --}}
                        <div>
                            <label class="block text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-1.5">Deskripsi</label>
                            <textarea
                                wire:model="description"
                                rows="3"
                                placeholder="Deskripsi produk (opsional)"
                                class="w-full bg-zinc-800 border border-zinc-600/60 rounded-xl px-4 py-2.5 text-sm text-zinc-100 placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition resize-none"
                            ></textarea>
                        </div>

                        {{-- Harga --}}
                        <div>
                            <label class="block text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-1.5">Harga (Rp)</label>
                            <input
                                wire:model="price"
                                type="number"
                                min="0"
                                placeholder="0"
                                class="w-full bg-zinc-800 border border-zinc-600/60 rounded-xl px-4 py-2.5 text-sm text-zinc-100 placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition"
                            />
                            @error('price') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Foto --}}
                        <div>
                            <label class="block text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-1.5">
                                Foto Produk
                                <span class="text-zinc-600 font-normal normal-case ml-1">(kosongkan jika tidak ingin mengganti)</span>
                            </label>
                            <input
                                wire:model="image"
                                type="file"
                                accept="image/*"
                                class="block w-full text-sm text-zinc-400
                                       file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0
                                       file:text-xs file:font-semibold
                                       file:bg-zinc-700 file:text-zinc-300
                                       hover:file:bg-zinc-600 cursor-pointer transition-colors"
                            />
                            @error('image') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                            @if($image)
                                <div class="mt-3 w-24 h-24 rounded-xl overflow-hidden border border-zinc-600/60 bg-zinc-800">
                                    <img src="{{ $image->temporaryUrl() }}" class="w-full h-full object-contain p-1" />
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-zinc-700/60 bg-zinc-800/40 rounded-b-2xl">
                        <button
                            wire:click="$set('showModal', false)"
                            class="px-4 py-2 text-sm font-medium text-zinc-300 bg-zinc-700/60 hover:bg-zinc-700 rounded-xl transition-colors"
                        >
                            Batal
                        </button>
                        <button
                            wire:click="save"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-500 rounded-xl transition-colors"
                        >
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>
        @endif
    @endif
</div>
