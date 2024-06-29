<?php

declare(strict_types=1);

namespace Rhaymison\ElephantChain\Embeddings;

use GeminiAPI\Client;
use GeminiAPI\Enums\ModelName;
use GeminiAPI\Resources\Parts\TextPart;
use Psr\Http\Client\ClientExceptionInterface;

class GeminiEmbeddingsFunction implements EmbeddingFunction
{
    private Client $client;

    public function __construct(
        public readonly string $apiKey,
        public readonly ModelName  $model = ModelName::Embedding,
    )
    {
        $this->client = new Client($this->apiKey);
    }

    /**
     * @inheritDoc
     */
    public function generate(array $texts): array
    {
        $embeddings = [];

        try {
            foreach ($texts as $text) {
                $response = $this->client->embeddingModel($this->model)
                    ->embedContent(new TextPart($text));

                $embeddings[] = $response->embedding->values;
            }
            return $embeddings;
        } catch (ClientExceptionInterface $e) {
            throw new \RuntimeException("Error calling Gemini API: {$e->getMessage()}", 0, $e);
        }
    }
}
