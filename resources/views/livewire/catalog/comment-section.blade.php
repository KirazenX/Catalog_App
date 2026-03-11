<div class="space-y-5">
    {{-- Form tambah komentar --}}
    <div class="space-y-3">
        <textarea
            wire:model="body"
            rows="3"
            placeholder="Tulis komentar Anda..."
            class="w-full rounded-xl px-4 py-3 text-sm resize-none transition
                   bg-zinc-50 dark:bg-zinc-700/50
                   border border-zinc-200 dark:border-zinc-600
                   text-zinc-800 dark:text-zinc-200
                   placeholder-zinc-400 dark:placeholder-zinc-500
                   focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500/60"
        ></textarea>
        @error('body')
            <p class="text-red-500 text-xs">{{ $message }}</p>
        @enderror
        <flux:button wire:click="addComment" variant="primary" size="sm">
            Kirim Komentar
        </flux:button>
    </div>

    <div class="border-t border-zinc-100 dark:border-zinc-700"></div>

    {{-- Jumlah komentar --}}
    <div class="flex items-center gap-2">
        <span class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">{{ $comments->count() }} Komentar</span>
    </div>

    {{-- Daftar komentar --}}
    <div class="space-y-4">
        @forelse($comments as $comment)
            <div class="flex gap-3">
                {{-- Avatar --}}
                <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-xs font-bold shrink-0 mt-0.5"
                     style="background: linear-gradient(135deg, #3b82f6, #7c3aed);">
                    {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                </div>
                {{-- Bubble --}}
                <div class="flex-1 min-w-0 rounded-2xl rounded-tl-sm px-4 py-3
                            bg-zinc-50 dark:bg-zinc-700/50
                            border border-zinc-100 dark:border-zinc-600/50">
                    <div class="flex items-center gap-2 mb-1 flex-wrap">
                        <span class="text-sm font-semibold text-zinc-800 dark:text-zinc-200">{{ $comment->user->name }}</span>
                        <span class="text-xs text-zinc-400 dark:text-zinc-500">{{ $comment->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-sm text-zinc-700 dark:text-zinc-300 leading-relaxed">{{ $comment->body }}</p>
                </div>
            </div>
        @empty
            <div class="flex flex-col items-center justify-center py-10 text-center">
                <div class="w-12 h-12 rounded-2xl bg-zinc-100 dark:bg-zinc-700/50 flex items-center justify-center mb-3">
                    <flux:icon.chat-bubble-left-right class="size-6 text-zinc-400" />
                </div>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Belum ada komentar.</p>
                <p class="text-xs text-zinc-400 dark:text-zinc-500 mt-1">Jadilah yang pertama berkomentar!</p>
            </div>
        @endforelse
    </div>
</div>
