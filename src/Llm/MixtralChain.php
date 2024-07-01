<?php

namespace Rhaymison\ElephantChain\Llm;

use Partitech\PhpMistral\Messages;
use Partitech\PhpMistral\MistralClient;
use Partitech\PhpMistral\MistralClientException;
use Rhaymison\ElephantChain\Interfaces\ModelChainInterface;

class MixtralChain implements ModelChainInterface
{
    private MistralClient $client;
    private string $modelName;
    private float $temperature;

    /**
     * @param string $apiKey
     * @param string $modelName
     * @param float $temperature
     */
    public function __construct(string $apiKey, string $modelName = 'mistral-large-latest', float $temperature = 0.5)
    {
        $this->client = new  MistralClient($apiKey);
        $this->modelName = $modelName;
        $this->temperature = $temperature;
    }

    /**
     * @param array $prompt
     * @return string
     * @throws MistralClientException
     */
    public function inference(array $prompt): string
    {
        $messages = new Messages();
        $messages->addSystemMessage($prompt['system']);
        $messages->addUserMessage($prompt['user']);

        $result = $this->client->chat($messages,
            [
                'model' => $this->modelName,
                'temperature' => $this->temperature,
            ]
        );
        return $result->getMessage();
    }
}