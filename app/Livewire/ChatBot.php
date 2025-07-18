<?php

namespace App\Livewire;

use App\Models\User;
use Prism\Prism\Prism;
use Livewire\Component;
use App\Models\ChatInteraction;
use Prism\Prism\Enums\Provider;
use Prism\Prism\ValueObjects\Messages\UserMessage;
use Prism\Prism\ValueObjects\Messages\AssistantMessage;

class ChatBot extends Component
{

    public $messages = [];
    public $question;

    public function mount()
    {
        // Initialize any properties or perform actions when the component is mounted
        $this->message = ChatInteraction::all(); // Fetch all chat interactions

    }

    public function askQuestion()
    {
        $conversation = [];

        foreach ($this->messages as $messageData) {
            if (is_object($messageData)) {
                $conversation[] = new UserMessage($messageData->question);
                $conversation[] = new AssistantMessage($messageData->answer);
            } else {
                $conversation[] = new UserMessage($messageData['question']);
                $conversation[] = new AssistantMessage($messageData['answer']);
            }
        }

        $conversation[] = new UserMessage($this->question);

        $response = Prism::text()
            ->using(Provider::OpenRouter, 'deepseek/deepseek-chat-v3-0324:free')
            ->withSystemPrompt('Kamu Sebagai FAQ Assistant, yang sangat membantu user untuk menjawab pertanyaan seputar produk yang ada di website ini. Jawab pertanyaan dengan singkat, padat, dan jelas.')
            ->withMessages($conversation)
            ->asText();

        $this->answer = $response->text;

        $interaction = ChatInteraction::create([
            'question' => $this->question,
            'answer' => $this->answer,
            'user_id' => auth()->id() // Assuming the user is authenticated
        ]);

        $this->messages[] = $interaction->toArray();
        $this->question = ''; // Clear the question input after asking

    }
    public function render()
    {
        return view('livewire.chat-bot');
    }
}
