<div class="space-y-6">
    @if(!auth()->user()->is_admin)
        <flux:callout variant="danger" icon="x-circle" heading="Akses Ditolak">
            Halaman ini hanya untuk administrator.
        </flux:callout>
    @else
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl">Manajemen Produk</flux:heading>
                <flux:subheading>Kelola data produk katalog</flux:subheading>
            </div>
            <flux:button wire:click="openCreate" variant="primary" icon="plus">
                Tambah Produk
            </flux:button>
        </div>

        <x-action-message on="product-saved">Produk berhasil disimpan.</x-action-message>

        {{-- Tabel --}}
        <div class="bg-white dark:bg-zinc-800 rounded-xl border border-zinc-200 dark:border-zinc-700 overflow-hidden">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-700/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Foto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Nama Produk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Harga</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-zinc-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse($products as $product)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700/30 transition-colors">
                            <td class="px-6 py-4">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}"
                                         class="h-12 w-12 rounded-lg object-cover" />
                                @else
                                    <div class="h-12 w-12 rounded-lg bg-zinc-100 dark:bg-zinc-700 flex items-center justify-center">
                                        <flux:icon.photo class="size-6 text-zinc-400" />
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium">{{ $product->name }}</div>
                                <div class="text-sm text-zinc-500 truncate max-w-xs">{{ $product->description }}</div>
                            </td>
                            <td class="px-6 py-4">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-right">
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
                            <td colspan="4" class="px-6 py-16 text-center text-zinc-500">
                                <flux:icon.archive-box class="size-12 mx-auto text-zinc-300 mb-3" />
                                <p>Belum ada produk.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-700">
                {{ $products->links() }}
            </div>
        </div>

        {{-- Modal --}}
        @if($showModal)
            <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
                <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-xl w-full max-w-lg border border-zinc-200 dark:border-zinc-700">
                    <div class="px-6 py-4 border-b border-zinc-200 dark:border-zinc-700">
                        <flux:heading size="lg">
                            {{ $isEditing ? 'Edit Produk' : 'Tambah Produk Baru' }}
                        </flux:heading>
                    </div>
                    <div class="px-6 py-4 space-y-4">
                        <flux:input wire:model="name" label="Nama Produk" placeholder="Nama produk" required />
                        @error('name') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror

                        <flux:textarea wire:model="description" label="Deskripsi" rows="3" placeholder="Deskripsi (opsional)" />

                        <flux:input wire:model="price" label="Harga (Rp)" type="number" min="0" placeholder="0" required />
                        @error('price') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror

                        <div>
                            <flux:label>
                                Foto Produk
                                @if($isEditing)
                                    <span class="text-zinc-400 font-normal text-xs">(kosongkan jika tidak ingin mengganti)</span>
                                @endif
                            </flux:label>
                            <input
                                wire:model="image"
                                type="file"
                                accept="image/*"
                                class="mt-1 block w-full text-sm text-zinc-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:bg-zinc-100 file:text-zinc-700 hover:file:bg-zinc-200 cursor-pointer"
                            />
                            @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            @if($image)
                                <img src="{{ $image->temporaryUrl() }}" class="mt-3 h-28 w-28 rounded-lg object-cover" />
                            @endif
                        </div>
                    </div>
                    <div class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-700 flex justify-end gap-3">
                        <flux:button wire:click="$set('showModal', false)" variant="filled">Batal</flux:button>
                        <flux:button wire:click="save" variant="primary">
                            {{ $isEditing ? 'Simpan Perubahan' : 'Tambah Produk' }}
                        </flux:button>
                    </div>
                </div>
            </div>
        @endif
    @endif
</div>