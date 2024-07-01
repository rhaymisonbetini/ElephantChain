<?php

namespace Rhaymison\ElephantChain\Interfaces;

use Codewithkyrian\ChromaDB\Embeddings\OpenAIEmbeddingFunction;
use Codewithkyrian\ChromaDB\Resources\CollectionResource;
use Rhaymison\ElephantChain\Embeddings\GeminiEmbeddingsFunction;
use Rhaymison\ElephantChain\Embeddings\MixtralEmbeddingFunction;
use Rhaymison\ElephantChain\Embeddings\OpenAIEmbeddingFunction as ElephantOpenAiEmbeddingsFunction;
use Codewithkyrian\ChromaDB\Embeddings\EmbeddingFunction;

interface EmbeddingFunctionInterface extends EmbeddingFunction
{
    public function getOrCreateCollection(string $collection, $embeddings): CollectionResource;

}