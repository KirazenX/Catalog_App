<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateProduct extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $description = '';
    public string $price = '';
    public $image;

    public function save(): void
    {
        $this->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'image'       => 'nullable|image|max:2048',
        ]);

        $data = [
            'name'        => $this->name,
            'description' => $this->description,
            'price'       => $this->price,
        ];

        if ($this->image) {
            $data['image'] = $this->image->store('products', 'public');
        }

        Product::create($data);

        session()->flash('success', 'Produk berhasil ditambahkan.');

        $this->redirect(route('admin.products'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.create-product');
    }
}
