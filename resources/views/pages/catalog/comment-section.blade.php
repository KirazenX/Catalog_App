<?php

use App\Models\Comment;
use Livewire\Volt\Component;

new class extends Component {
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

    public function with(): array
    {
        $comments = Comment::with('user')
            ->where('product_id', $this->productId)
            ->latest()
            ->get();

        return compact('comments');
    }
}; ?>

<div class="space-y-6">
    <flux:heading>Komentar ({{ $comments->count() }})</flux:heading>

    {{-- Form tambah komentar --}}
    <div class="space-y-3">
        <flux:textarea
            wire:model="body"
            placeholder="Tulis komentar Anda..."
            rows="3"
        />
        @error('body')
            <flux:text class="text-red-500 text-sm">{{ $message }}</flux:text>
        @enderror
        <flux:button wire:click="addComment" variant="primary">
            Kirim Komentar
        </flux:button>
    </div>

    <flux:separator />

    {{-- Daftar komentar --}}
    <div class="space-y-4">
        @forelse($comments as $comment)
            <div class="flex gap-3">
                <flux:avatar
                    :name="$comment->user->name"
                    :initials="$comment->user->initials()"
                    class="shrink-0"
                />
                <div class="flex-1 bg-zinc-50 dark:bg-zinc-700/50 rounded-xl px-4 py-3">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="font-medium text-sm">{{ $comment->user->name }}</span>
                        <flux:text class="text-xs">{{ $comment->created_at->diffForHumans() }}</flux:text>
                    </div>
                    <flux:text class="text-sm">{{ $comment->body }}</flux:text>
                </div>
            </div>
        @empty
            <flux:text class="text-center py-4">Belum ada komentar. Jadilah yang pertama!</flux:text>
        @endforelse
    </div>
</div>