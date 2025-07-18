<div class="flex h-[80vh] max-w-5xl mx-auto border border-gray-300 rounded-lg">

    <div class="flex flex-col w-1/3 border-r border-gray-300">
        <div class="p-4 border-b">
            <button wire:click="startNewConversation" class="w-full px-4 py-2 text-white transition bg-blue-500 rounded hover:bg-blue-600">
                + Chat Baru
            </button>
        </div>

        <div class="flex-1 overflow-y-auto">
            @forelse($conversations as $conversation)
                <div wire:click="switchConversation({{ $conversation->id }})"
                     class="p-4 cursor-pointer hover:bg-gray-100 @if($conversation->id == $activeConversationId) bg-blue-100 @endif">
                    <p class="font-semibold truncate">{{ $conversation->title }}</p>
                    <p class="text-sm text-gray-500">{{ $conversation->created_at->diffForHumans() }}</p>
                </div>
            @empty
                <p class="p-4 text-sm text-gray-500">Belum ada percakapan.</p>
            @endforelse
        </div>
    </div>

    <div class="flex flex-col w-2/3">
        <div class="flex-1 p-6 overflow-y-auto">
            @forelse($messages as $interaction)
                <div class="mb-4">
                    <p class="font-semibold text-blue-600">You:</p>
                    <p class="pl-2">{{ $interaction['question'] }}</p>
                </div>
                <div class="mb-4">
                    <p class="font-semibold text-green-600">AI:</p>
                    <div class="pl-2 prose max-w-none">{!! \Illuminate\Support\Str::markdown($interaction['answer']) !!}</div>
                </div>
                <hr class="my-4">
            @empty
                <div class="flex items-center justify-center h-full">
                    <p class="text-gray-500">Silakan mulai percakapan baru atau pilih dari histori.</p>
                </div>
            @endforelse
        </div>

        <div class="p-4 border-t border-gray-200">
            <form wire:submit.prevent="askQuestion" class="flex items-center gap-3">
                <input type="text" wire:model="question" placeholder="Ketik pertanyaan Anda..." required
                       class="flex-1 px-3 py-2 text-black placeholder-gray-500 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400" />
                <button type="submit" class="flex items-center gap-2 px-4 py-2 text-white transition bg-blue-500 rounded hover:bg-blue-600" wire:loading.attr="disabled">
                    <span>Ask</span>
                    <div wire:loading>
                        <svg class="w-4 h-4 text-white animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </button>
            </form>
        </div>
    </div>
</div>
