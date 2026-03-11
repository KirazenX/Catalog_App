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
            <flux:callout variant="success" icon="check-circle">
                {{ session('success') }}
            </flux:callout>
        @endif

        <x-action-message on="product-saved">Produk berhasil disimpan.</x-action-message>

        {{-- Tabel --}}
        <div class="bg-white dark:bg-zinc-800 rounded-2xl border border-zinc-200 dark:border-zinc-700 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                    <thead>
                        <tr class="bg-zinc-50 dark:bg-zinc-700/50">
                            <th class="px-5 py-3.5 text-left text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider w-20">Foto</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Nama Produk</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider w-36">Harga</th>
                            <th class="px-5 py-3.5 text-right text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-700">
                        @forelse($products as $product)
                            <tr class="hover:bg-zinc-50/70 dark:hover:bg-zinc-700/30 transition-colors">
                                <td class="px-5 py-3">
                                    <div class="w-14 h-14 rounded-xl overflow-hidden bg-zinc-100 dark:bg-zinc-700 flex items-center justify-center shrink-0">
                                        @if($product->image)
                                            <img
                                                src="{{ asset('storage/' . $product->image) }}"
                                                alt="{{ $product->name }}"
                                                class="w-full h-full object-contain p-1"
                                            />
                                        @else
                                            <flux:icon.photo class="size-6 text-zinc-400" />
                                        @endif
                                    </div>
                                </td>
                                <td class="px-5 py-3">
                                    <div class="font-medium text-zinc-900 dark:text-zinc-100">{{ $product->name }}</div>
                                    <div class="text-sm text-zinc-500 dark:text-zinc-400 truncate max-w-xs mt-0.5">
                                        {{ $product->description ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-5 py-3">
                                    <span class="text-sm font-semibold text-zinc-800 dark:text-zinc-200">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <flux:button wire:click="openEdit({{ $product->id }})" size="sm" variant="filled">
                                            Edit
                                        </flux:button>
                                        <flux:button
                                            wire:click="delete({{ $product->id }})"
                                            wire:confirm="Yakin ingin menghapus produk '{{ $product->name }}'?"
                                            size="sm"
                                            variant="danger"
                                        >
                                            Hapus
                                        </flux:button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-16 text-center">
                                    <div class="flex flex-col items-center gap-2 text-zinc-400">
                                        <flux:icon.archive-box class="size-10" />
                                        <p class="text-sm">Belum ada produk.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-4 border-t border-zinc-200 dark:border-zinc-700">
                {{ $products->links() }}
            </div>
        </div>

        {{-- Modal Edit --}}
        @if($showModal)
            <div class="fixed inset-0 bg-black/60 dark:bg-black/70 z-50 flex items-center justify-center p-4 backdrop-blur-sm">
                <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-2xl w-full max-w-lg border border-zinc-200 dark:border-zinc-700">
                    <div class="px-6 py-4 border-b border-zinc-200 dark:border-zinc-700 flex items-center justify-between">
                        <flux:heading size="lg">Edit Produk</flux:heading>
                        <button
                            wire:click="$set('showModal', false)"
                            class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 transition-colors"
                        >
                            <flux:icon.x-mark class="size-5" />
                        </button>
                    </div>
                    <div class="px-6 py-5 space-y-4">
                        <div>
                            <flux:input wire:model="name" label="Nama Produk" placeholder="Nama produk" required />
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <flux:textarea wire:model="description" label="Deskripsi" rows="3" placeholder="Deskripsi (opsional)" />
                        </div>
                        <div>
                            <flux:input wire:model="price" label="Harga (Rp)" type="number" min="0" placeholder="0" required />
                            @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <flux:label>
                                Foto Produk
                                <span class="text-zinc-400 font-normal text-xs ml-1">(kosongkan jika tidak ingin mengganti)</span>
                            </flux:label>
                            <input
                                wire:model="image"
                                type="file"
                                accept="image/*"
                                class="mt-1 block w-full text-sm text-zinc-500 dark:text-zinc-400
                                       file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0
                                       file:text-sm file:bg-zinc-100 file:text-zinc-700
                                       dark:file:bg-zinc-700 dark:file:text-zinc-300
                                       hover:file:bg-zinc-200 dark:hover:file:bg-zinc-600
                                       cursor-pointer transition-colors"
                            />
                            @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            @if($image)
                                <div class="mt-3 w-24 h-24 rounded-xl overflow-hidden border border-zinc-200 dark:border-zinc-600">
                                    <img src="{{ $image->temporaryUrl() }}" class="w-full h-full object-contain p-1" />
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-700 flex justify-end gap-3">
                        <flux:button wire:click="$set('showModal', false)" variant="filled">Batal</flux:button>
                        <flux:button wire:click="save" variant="primary">Simpan Perubahan</flux:button>
                    </div>
                </div>
            </div>
        @endif
    @endif
</div>
