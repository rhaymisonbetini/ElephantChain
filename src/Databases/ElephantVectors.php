<?php

namespace Rhaymison\ElephantChain\Databases;

use AllowDynamicProperties;
use Rhaymison\ElephantChain\Embeddings\GeminiEmbeddingsFunction;
use Rhaymison\ElephantChain\Embeddings\OpenAIEmbeddingFunction;
use Rhaymison\ElephantChain\Helpers\MathHelpers;
use Rhaymison\ElephantChain\Helpers\StringHelpers;

#[AllowDynamicProperties] final class ElephantVectors
{

    private OpenAIEmbeddingFunction|GeminiEmbeddingsFunction $embedddingFunction;

    /**
     * @param OpenAIEmbeddingFunction|GeminiEmbeddingsFunction $embedddingFunction
     */
    public function __construct(OpenAIEmbeddingFunction|GeminiEmbeddingsFunction $embedddingFunction)
    {
        $this->embedddingFunction = $embedddingFunction;
    }

    /**
     * @param array $chunks
     * @return array
     */
    public function generateEmbeddingsChunks(array $chunks): array
    {
        if (isset($chunks[2])) {
            $embeddingsMatrix = array();
            foreach ($chunks[2] as $chunk) {
                $embeddingsMatrix[] = $this->embedddingFunction->generate([$chunk]);
            }
            $chunks[3] = $embeddingsMatrix;
        } else {
            $chunks[3] = [];
        }
        return $chunks;
    }

    /**
     * @param array $chunks
     * @param string $searcher
     * @param int $k
     * @return array
     */
    public function retriever(array $chunks, string $searcher, int $k = 1): array
    {
        $similarities = [];

        if (isset($chunks[3])) {
            $searchVector = $this->embedddingFunction->generate([StringHelpers::purifyString($searcher)]);
            if (is_array($searchVector[0])) {
                foreach ($chunks[3] as $key => $embeddings) {
                    $similarity = MathHelpers::cosineSimilaruty($searchVector[0], $embeddings[0]);
                    $similarities[] = ['position' => $key, 'similarity' => $similarity];
                }
            }
        }
        $topKSimilarities = self::getTopKSimilarities($similarities, $k);
        $texts = $chunks[2];
        return array_map(function ($item) use ($texts) {
            return $texts[$item['position']];
        }, $topKSimilarities);
    }

    /**
     * @param $array
     * @param $k
     * @return array
     */
    public static function getTopKSimilarities($array, $k): array
    {
        usort($array, function ($a, $b) {
            return $b['similarity'] <=> $a['similarity'];
        });
        return array_slice($array, 0, $k);
    }

}