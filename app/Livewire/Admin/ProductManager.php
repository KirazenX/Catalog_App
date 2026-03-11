<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ProductManager extends Component
{
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

    public function render()
    {
        $products = Product::latest()->paginate(10);
        return view('livewire.admin.product-manager', compact('products'));
    }
}