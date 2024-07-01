<?php

namespace Rhaymison\ElephantChain\Embeddings;

use Partitech\PhpMistral\MistralClient;
use Partitech\PhpMistral\MistralClientException;

class MixtralEmbeddingFunction implements EmbeddingFunction
{
    private MistralClient $client;

    public function __construct(
        public readonly string $apiKey,
    )
    {
        $this->client = new  MistralClient($apiKey);
    }

    /**
     * @param array $texts
     * @return array|int[][]
     * @throws MistralClientException
     */
    public function generate(array $texts): array
    {
        $embeddings = [];
        foreach ($texts as $text) {
            $response = $this->client->embeddings([$text]);
            $embeddings[] = $response['data'][0]['embedding'];
        }
        return $embeddings;
    }
}