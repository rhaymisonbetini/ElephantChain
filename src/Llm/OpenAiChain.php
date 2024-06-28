<?php

namespace Rhaymison\ElephantChain\Llm;

use OpenAI;

class OpenAiChain
{

    /**
     * @var OpenAI\Client
     */
    private OpenAI\Client $client;

    /**
     * @var string
     */
    private string $modelName;

    /**
     * @param string $apiKey
     * @param string $modelName
     */
    public function __construct(string $apiKey, string $modelName)
    {
        $this->client = OpenAI::client($apiKey);
        $this->modelName = $modelName;
    }

    public function inference(array $prompt): string
    {
        $result = $this->client->chat()->create([
            'model' => $this->modelName,
            'messages' => [
                ['role' => 'system', 'content' => $prompt['system']],
                ['role' => 'user', 'content' => $prompt['user']],
            ],
        ]);
        return $result->choices[0]->message->content;
    }

}