<?php

namespace App\Livewire;

use App\Models\ChatMessage;
use App\Models\Conversation;
use Livewire\Component;
use Prism\Prism\Enums\Provider;
use Prism\Prism\Prism;
use Prism\Prism\ValueObjects\Messages\AssistantMessage;
use Prism\Prism\ValueObjects\Messages\UserMessage;

class ChatRobot extends Component
{
    public $messages = [];
    public string $question = '';
    public $conversations = [];
    public ?int $activeConversationId = null;

    // Method ini dijalankan saat komponen pertama kali di-load
    public function mount()
    {
        $this->loadConversations();

        // Ambil percakapan terakhir sebagai percakapan aktif
        $latestConversation = Conversation::where('user_id', auth()->id())
            ->latest()
            ->first();

        if ($latestConversation) {
            $this->switchConversation($latestConversation->id);
        }
    }

    // Memuat daftar histori percakapan
    public function loadConversations()
    {
        $this->conversations = Conversation::where('user_id', auth()->id())
            ->latest()
            ->get();
    }

    // Memulai sesi chat baru
    public function startNewConversation()
    {
        $this->activeConversationId = null;
        $this->messages = [];
        $this->question = '';
    }

    // Berpindah ke percakapan lain dari histori
    public function switchConversation(int $conversationId)
    {
        $this->activeConversationId = $conversationId;
        $this->messages = ChatMessage::where('conversation_id', $conversationId)
            ->get()
            ->toArray();
    }

    public function askQuestion()
    {
        $this->validate(['question' => 'required']);

        $currentConversationId = $this->activeConversationId;

        // Jika ini adalah chat baru, buat dulu "wadah" percakapannya
        if (!$currentConversationId) {
            $newConversation = Conversation::create([
                'user_id' => auth()->id(),
                'title' => substr($this->question, 0, 50), // Ambil 50 karakter pertama sbg judul
            ]);
            $currentConversationId = $newConversation->id;
            $this->activeConversationId = $currentConversationId;
            $this->loadConversations(); // Muat ulang histori di sidebar
        }

        // Bangun konteks dari pesan yang sudah ada
        $context = [];
        foreach ($this->messages as $message) {
            $context[] = new UserMessage($message['question']);
            $context[] = new AssistantMessage($message['answer']);
        }
        $context[] = new UserMessage($this->question);

        // Panggil AI
        $response = Prism::text()
            ->using(Provider::OpenRouter, 'deepseek/deepseek-chat-v3-0324:free') // Ganti dengan provider Anda
            ->withSystemPrompt('Kamu Sebagai FAQ Assistant, yang sangat membantu user untuk menjawab pertanyaan seputar produk yang ada di website ini. Jawab pertanyaan dengan singkat, padat, dan jelas.')
            ->withMessages($context)
            ->generate();

        $answer = $response->text;

        // Simpan Q&A ke database dengan conversation_id yang benar
        $interaction = ChatMessage::create([
            'conversation_id' => $currentConversationId,
            'question' => $this->question,
            'answer' => $answer,
        ]);

        $this->messages[] = $interaction->toArray();
        $this->question = ''; // Kosongkan input
    }

    public function render()
    {
        return view('livewire.chat-robot');
    }
}
