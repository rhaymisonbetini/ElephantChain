<?php

namespace Rhaymison\ElephantChain\Databases;

use AllowDynamicProperties;
use Rhaymison\ElephantChain\Embeddings\GeminiEmbeddingsFunction;
use Rhaymison\ElephantChain\Embeddings\OpenAIEmbeddingFunction;

#[AllowDynamicProperties] final class ElephantVectors
{

    private OpenAIEmbeddingFunction|GeminiEmbeddingsFunction $embedddingFunction;

    /**
     * @param OpenAIEmbeddingFunction|GeminiEmbeddingsFunction $embedddingFunction
     */
    public function __construct(OpenAIEmbeddingFunction|GeminiEmbeddingsFunction $embedddingFunction)
    {
        $this->embedinnonFunction = $embedddingFunction;
    }

    /**
     * @param array $chunks
     * @return array
     */
    public function generateEmbeddingsChunks(array $chunks): array
    {
        //TODO:GENERATEEMBEDDINGS;
        return [];
    }

    /**
     * @param array $chunks
     * @param string $searcher
     * @return array
     */
    public function retriever(array $chunks, string $searcher): array
    {
        //TODO:GENERATEEMBEDDINGS;
        return [];
    }
}