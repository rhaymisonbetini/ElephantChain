<?php

namespace Rhaymison\ElephantChain\Llm;

use OpenAI;
use Rhaymison\ElephantChain\Interfaces\ModelChainInterface;

class OpenAiChain implements ModelChainInterface
{

    /**
     * @var OpenAI\Client
     */
    private OpenAI\Client $client;

    /**
     * @var string
     */
    private string $modelName;

    private float $temperature;

    /**
     * @param string $apiKey
     * @param string $modelName
     * @param float $temperature
     */
    public function __construct(string $apiKey, string $modelName, float $temperature = 0.5)
    {
        $this->client = OpenAI::client($apiKey);
        $this->modelName = $modelName;
        $this->temperature = $temperature;
    }

    public function inference(array $prompt): string
    {
        $result = $this->client->chat()->create([
            'model' => $this->modelName,
            'temperature' => $this->temperature,
            'messages' => [
                ['role' => 'system', 'content' => $prompt['system']],
                ['role' => 'user', 'content' => $prompt['user']],
            ],
        ]);
        return $result->choices[0]->message->content;
    }

}