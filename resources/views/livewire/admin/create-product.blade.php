<div class="space-y-6">
    @if(!auth()->user()->is_admin)
        <flux:callout variant="danger" icon="x-circle" heading="Akses Ditolak">
            Halaman ini hanya untuk administrator.
        </flux:callout>
    @else
        {{-- Header --}}
        <div class="flex items-center gap-4">
            <flux:button
                href="{{ route('admin.products') }}"
                variant="ghost"
                icon="arrow-left"
                wire:navigate
            >
                Kembali
            </flux:button>
            <div>
                <flux:heading size="xl">Tambah Produk Baru</flux:heading>
                <flux:subheading>Isi informasi produk di bawah ini</flux:subheading>
            </div>
        </div>

        {{-- Form --}}
        <div class="bg-white dark:bg-zinc-800 rounded-xl border border-zinc-200 dark:border-zinc-700 overflow-hidden">
            <div class="px-6 py-5 border-b border-zinc-200 dark:border-zinc-700">
                <flux:heading size="lg">Informasi Produk</flux:heading>
            </div>

            <div class="px-6 py-6 space-y-6">
                {{-- Nama Produk --}}
                <div>
                    <flux:input
                        wire:model="name"
                        label="Nama Produk"
                        placeholder="Masukkan nama produk"
                        required
                    />
                    @error('name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Deskripsi --}}
                <div>
                    <flux:textarea
                        wire:model="description"
                        label="Deskripsi"
                        rows="4"
                        placeholder="Deskripsi produk (opsional)"
                    />
                    @error('description')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Harga --}}
                <div>
                    <flux:input
                        wire:model="price"
                        label="Harga (Rp)"
                        type="number"
                        min="0"
                        placeholder="0"
                        required
                    />
                    @error('price')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Foto Produk --}}
                <div>
                    <flux:label>Foto Produk <span class="text-zinc-400 font-normal text-xs">(opsional)</span></flux:label>
                    <div class="mt-1">
                        <input
                            wire:model="image"
                            type="file"
                            accept="image/*"
                            class="block w-full text-sm text-zinc-500
                                   file:mr-4 file:py-2 file:px-4
                                   file:rounded-lg file:border-0
                                   file:text-sm file:bg-zinc-100 file:text-zinc-700
                                   hover:file:bg-zinc-200 cursor-pointer"
                        />
                    </div>
                    @error('image')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror

                    @if($image)
                        <div class="mt-4">
                            <p class="text-sm text-zinc-500 mb-2">Preview:</p>
                            <img
                                src="{{ $image->temporaryUrl() }}"
                                class="h-40 w-40 rounded-xl object-cover border border-zinc-200 dark:border-zinc-700 shadow-sm"
                                alt="Preview foto produk"
                            />
                        </div>
                    @endif
                </div>
            </div>

            {{-- Footer Actions --}}
            <div class="px-6 py-4 bg-zinc-50 dark:bg-zinc-700/30 border-t border-zinc-200 dark:border-zinc-700 flex items-center justify-end gap-3">
                <flux:button
                    href="{{ route('admin.products') }}"
                    variant="filled"
                    wire:navigate
                >
                    Batal
                </flux:button>
                <flux:button
                    wire:click="save"
                    wire:loading.attr="disabled"
                    variant="primary"
                    icon="plus"
                >
                    <span wire:loading.remove wire:target="save">Tambah Produk</span>
                    <span wire:loading wire:target="save">Menyimpan...</span>
                </flux:button>
            </div>
        </div>
    @endif
</div>
