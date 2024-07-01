<?php

namespace Rhaymison\ElephantChain\Interfaces;

use Rhaymison\ElephantChain\Embeddings\OpenAIEmbeddingFunction;
use Codewithkyrian\ChromaDB\Resources\CollectionResource;
use Rhaymison\ElephantChain\Embeddings\GeminiEmbeddingsFunction;
use Rhaymison\ElephantChain\Embeddings\MixtralEmbeddingFunction;
use Rhaymison\ElephantChain\Embeddings\OpenAIEmbeddingFunction as ElephantOpenAiEmbeddingsFunction;
use Rhaymison\ElephantChain\Embeddings\EmbeddingFunction;

interface EmbeddingFunctionInterface extends EmbeddingFunction
{
    public function getOrCreateCollection(string $collection, $embeddings): CollectionResource;

}