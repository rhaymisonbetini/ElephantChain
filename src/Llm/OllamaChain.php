<?php

namespace Rhaymison\ElephantChain\Llm;
use ModelflowAi\Ollama\ClientInterface;
use ModelflowAi\Ollama\Ollama;

class OllamaChain  implements ModelChainInterface
{
    private string $model;
    private ClientInterface $client;

    private float $temperature;

    public function __construct(string $model, float $temperature = 0.5)
    {
        $this->model = $model;
        $this->temperature = $temperature;
        $this->client = Ollama::client();
    }

    public function inference(array $prompt): string
    {
        $chat = $this->client->chat();
        $response = $chat->create([
            'model' => $this->model,
            'options' => [
                'temperature' => $this->temperature,
            ],
            'messages' => [
                ['role' => 'system', 'content' => $prompt['system']],
                ['role' => 'user', 'content' => $prompt['user']],
            ],
        ]);
        return $response->message->content ?? '';
    }
}
