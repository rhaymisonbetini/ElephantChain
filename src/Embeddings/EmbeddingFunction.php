<?php

declare(strict_types=1);

namespace Rhaymison\ElephantChain\Embeddings;


interface EmbeddingFunction extends  \Codewithkyrian\ChromaDB\Embeddings\EmbeddingFunction
{
    /**
     * Generates embeddings for the given texts
     *
     * @param string[] $texts
     *
     * @return int[][]
     */
    public function generate(array $texts): array;

}