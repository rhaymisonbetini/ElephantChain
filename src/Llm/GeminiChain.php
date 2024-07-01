<?php

namespace Rhaymison\ElephantChain\Llm;

use GeminiAPI\Client;
use GeminiAPI\Enums\ModelName;
use GeminiAPI\GenerationConfig;
use GeminiAPI\Resources\Parts\TextPart;
use Psr\Http\Client\ClientExceptionInterface;
use Rhaymison\ElephantChain\Interfaces\ModelChainInterface;

class GeminiChain implements  ModelChainInterface
{
    /**
     * @var Client
     */
    private Client $client;

    /**
     * @var float
     */
    private float $temperature;

    /**
     * @param string $apiKey
     * @param float $temperature
     */
    public function __construct(string $apiKey, float $temperature = 0.5)
    {
        $this->client = new Client($apiKey);
        $this->temperature = $temperature;
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function inference(array $prompt): string
    {
        $generationConfig = (new GenerationConfig())->withTemperature($this->temperature);
        $result = $this->client
            ->geminiPro()
            ->withGenerationConfig($generationConfig)
            ->generateContent(
                new TextPart('system: ' . $prompt['system']),
                new TextPart('user: ' . $prompt['user']),
            );
        return $result->text();
    }

}