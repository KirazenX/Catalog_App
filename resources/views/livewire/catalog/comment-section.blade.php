<div class="space-y-6">
    <flux:heading>Komentar ({{ $comments->count() }})</flux:heading>

    <div class="space-y-3">
        <flux:textarea
            wire:model="body"
            placeholder="Tulis komentar Anda..."
            rows="3"
        />
        @error('body')
            <p class="text-red-500 text-sm">{{ $message }}</p>
        @enderror
        <flux:button wire:click="addComment" variant="primary">
            Kirim Komentar
        </flux:button>
    </div>

    <flux:separator />

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
                        <span class="text-zinc-400 text-xs">{{ $comment->created_at->diffForHumans() }}</span>
                    </div>
                    <flux:text class="text-sm">{{ $comment->body }}</flux:text>
                </div>
            </div>
        @empty
            <flux:text class="text-center py-4">Belum ada komentar. Jadilah yang pertama!</flux:text>
        @endforelse
    </div>
</div>