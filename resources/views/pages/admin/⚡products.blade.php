<?php

use App\Models\Product;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

new class extends Component {
    use WithFileUploads, WithPagination;

    public ?int $productId = null;
    public string $name = '';
    public string $description = '';
    public string $price = '';
    public $image;
    public bool $showModal = false;
    public bool $isEditing = false;

    public function openCreate(): void
    {
        $this->reset(['productId', 'name', 'description', 'price', 'image', 'isEditing']);
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $product = Product::findOrFail($id);
        $this->productId = $product->id;
        $this->name = $product->name;
        $this->description = $product->description ?? '';
        $this->price = (string) $product->price;
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'image'       => $this->isEditing ? 'nullable|image|max:2048' : 'required|image|max:2048',
        ]);

        $data = [
            'name'        => $this->name,
            'description' => $this->description,
            'price'       => $this->price,
        ];

        if ($this->image) {
            $data['image'] = $this->image->store('products', 'public');
        }

        if ($this->isEditing) {
            Product::findOrFail($this->productId)->update($data);
        } else {
            Product::create($data);
        }

        $this->showModal = false;
        $this->reset(['productId', 'name', 'description', 'price', 'image', 'isEditing']);
        $this->dispatch('product-saved');
    }

    public function delete(int $id): void
    {
        Product::findOrFail($id)->delete();
    }

    public function with(): array
    {
        $products = Product::latest()->paginate(10);
        return compact('products');
    }
}; ?>

<x-layouts::app :title="__('Manajemen Produk')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">

        @if(!auth()->user()->is_admin)
            <flux:callout variant="danger" icon="x-circle" heading="Akses Ditolak">
                Halaman ini hanya untuk administrator.
            </flux:callout>
        @else
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
                                             class="h-12 w-12 rounded-lg object-cover border border-zinc-200 dark:border-zinc-600" />
                                    @else
                                        <div class="h-12 w-12 rounded-lg bg-zinc-100 dark:bg-zinc-700 flex items-center justify-center">
                                            <flux:icon.photo class="size-6 text-zinc-400" />
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-zinc-900 dark:text-zinc-100">{{ $product->name }}</div>
                                    <div class="text-sm text-zinc-500 truncate max-w-xs">{{ $product->description }}</div>
                                </td>
                                <td class="px-6 py-4 text-zinc-700 dark:text-zinc-300">
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
                                    <p>Belum ada produk. Tambahkan produk pertama!</p>
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
            <flux:modal name="product-modal" :show="$showModal" class="max-w-lg" @close="$wire.showModal = false">
                <div class="space-y-6">
                    <flux:heading size="lg">
                        {{ $isEditing ? 'Edit Produk' : 'Tambah Produk Baru' }}
                    </flux:heading>

                    <div class="space-y-4">
                        <flux:input
                            wire:model="name"
                            label="Nama Produk"
                            placeholder="Masukkan nama produk"
                            required
                        />
                        @error('name')
                            <flux:text class="text-red-500 text-sm -mt-2">{{ $message }}</flux:text>
                        @enderror

                        <flux:textarea
                            wire:model="description"
                            label="Deskripsi"
                            placeholder="Deskripsi produk (opsional)"
                            rows="3"
                        />

                        <flux:input
                            wire:model="price"
                            label="Harga (Rp)"
                            type="number"
                            min="0"
                            placeholder="0"
                            required
                        />
                        @error('price')
                            <flux:text class="text-red-500 text-sm -mt-2">{{ $message }}</flux:text>
                        @enderror

                        <div>
                            <flux:label>
                                Foto Produk
                                @if($isEditing)
                                    <span class="text-zinc-400 font-normal">(kosongkan jika tidak ingin mengganti)</span>
                                @endif
                            </flux:label>
                            <input
                                wire:model="image"
                                type="file"
                                accept="image/*"
                                class="mt-1 block w-full text-sm text-zinc-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-zinc-100 file:text-zinc-700 hover:file:bg-zinc-200 cursor-pointer"
                            />
                            @error('image')
                                <flux:text class="text-red-500 text-sm mt-1">{{ $message }}</flux:text>
                            @enderror
                            @if($image)
                                <img src="{{ $image->temporaryUrl() }}" class="mt-3 h-28 w-28 rounded-lg object-cover border border-zinc-200" />
                            @endif
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <flux:button @click="$wire.showModal = false" variant="filled">Batal</flux:button>
                        <flux:button wire:click="save" variant="primary">
                            {{ $isEditing ? 'Simpan Perubahan' : 'Tambah Produk' }}
                        </flux:button>
                    </div>
                </div>
            </flux:modal>
        @endif
    </div>
</x-layouts::app>