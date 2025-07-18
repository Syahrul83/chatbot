<div class="p-6 max-w-xl mx-auto">
    @foreach($messages as $interaction)
        @php
            if (is_array($interaction)) {
                $question = $interaction['question'] ?? '';
                $answer = $interaction['answer'] ?? '';
            } else {
                $question = $interaction->question ?? '';
                $answer = $interaction->answer ?? '';
            }
        @endphp
        <p><strong>You:</strong> {{ $question }}</p>
        <p><strong>AI:</strong> {{ $answer }}</p>
        <hr>
    @endforeach


    <form wire:submit.prevent="askQuestion" class="mt-4 flex items-center gap-3">
        <input type="text" wire:model="question" placeholder="Type your question..." required
            class="flex-1 px-3 py-2 rounded-md border border-gray-300 text-black bg-gray placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-400" />
        <button type="submit"
            class="bg-blue-500 hover:bg-blue-600 text-white rounded px-4 py-2 flex items-center gap-2 transition">
            <span>Ask</span>
            <div wire:loading>
                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
            </div>
        </button>
    </form>

</div>
