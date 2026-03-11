<div class="space-y-6">
    @if(!auth()->user()->is_admin)
        <div class="flex flex-col items-center justify-center py-24 text-center">
            <div class="w-16 h-16 rounded-2xl bg-red-100 dark:bg-red-900/30 flex items-center justify-center mb-4">
                <flux:icon.lock-closed class="size-8 text-red-500" />
            </div>
            <flux:heading>Akses Ditolak</flux:heading>
            <flux:subheading class="mt-1">Halaman ini hanya untuk administrator.</flux:subheading>
        </div>
    @else
        {{-- Header --}}
        <div class="flex items-center gap-3">
            <flux:button href="{{ route('admin.products') }}" variant="ghost" icon="arrow-left" wire:navigate>
                Kembali
            </flux:button>
            <div class="h-5 w-px bg-zinc-200 dark:bg-zinc-700"></div>
            <div>
                <flux:heading size="xl">Tambah Produk Baru</flux:heading>
                <flux:subheading>Isi informasi produk di bawah ini</flux:subheading>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-800 rounded-2xl border border-zinc-200 dark:border-zinc-700 shadow-sm overflow-hidden max-w-2xl">
            <div class="px-6 py-4 border-b border-zinc-100 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-700/40">
                <flux:heading size="base">Informasi Produk</flux:heading>
            </div>

            <div class="px-6 py-6 space-y-5">
                <div>
                    <flux:input wire:model="name" label="Nama Produk" placeholder="Masukkan nama produk" required />
                    @error('name') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <flux:textarea wire:model="description" label="Deskripsi" rows="4" placeholder="Deskripsi produk (opsional)" />
                    @error('description') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <flux:input wire:model="price" label="Harga (Rp)" type="number" min="0" placeholder="0" required />
                    @error('price') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <flux:label>
                        Foto Produk
                        <span class="text-zinc-400 font-normal text-xs ml-1">(opsional)</span>
                    </flux:label>
                    <div class="mt-1">
                        <input
                            wire:model="image"
                            type="file"
                            accept="image/*"
                            class="block w-full text-sm text-zinc-500 dark:text-zinc-400
                                   file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0
                                   file:text-sm file:bg-zinc-100 file:text-zinc-700
                                   dark:file:bg-zinc-700 dark:file:text-zinc-300
                                   hover:file:bg-zinc-200 dark:hover:file:bg-zinc-600
                                   cursor-pointer transition-colors"
                        />
                    </div>
                    @error('image') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    @if($image)
                        <div class="mt-4">
                            <p class="text-xs text-zinc-500 dark:text-zinc-400 mb-2 font-medium">Preview:</p>
                            <div class="w-36 h-36 rounded-xl overflow-hidden border border-zinc-200 dark:border-zinc-600 bg-zinc-50 dark:bg-zinc-700">
                                <img src="{{ $image->temporaryUrl() }}" class="w-full h-full object-contain p-2" alt="Preview" />
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="px-6 py-4 bg-zinc-50 dark:bg-zinc-700/30 border-t border-zinc-200 dark:border-zinc-700 flex items-center justify-end gap-3">
                <flux:button href="{{ route('admin.products') }}" variant="filled" wire:navigate>Batal</flux:button>
                <flux:button wire:click="save" wire:loading.attr="disabled" variant="primary" icon="plus">
                    <span wire:loading.remove wire:target="save">Tambah Produk</span>
                    <span wire:loading wire:target="save">Menyimpan...</span>
                </flux:button>
            </div>
        </div>
    @endif
</div>
