<?php

namespace App\Livewire\Catalog;

use App\Models\Comment;
use Livewire\Component;

class CommentSection extends Component
{
    public int $productId;
    public string $body = '';

    public function mount(int $productId): void
    {
        $this->productId = $productId;
    }

    public function addComment(): void
    {
        $this->validate(['body' => 'required|string|max:1000']);

        Comment::create([
            'product_id' => $this->productId,
            'user_id'    => auth()->id(),
            'body'       => $this->body,
        ]);

        $this->reset('body');
    }

    public function render()
    {
        $comments = Comment::with('user')
            ->where('product_id', $this->productId)
            ->latest()
            ->get();

        return view('livewire.catalog.comment-section', compact('comments'));
    }
}