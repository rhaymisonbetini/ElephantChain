<?php

namespace Rhaymison\ElephantChain\Helpers;

use Rhaymison\ElephantChain\Enuns\ElephantEnum;

final class ChunkGenerator
{
    /**
     * @param array $words
     * @param string $fileName
     * @param int $chunkSize
     * @param int $overlap
     * @return array
     */
    public static function generateChunksFromFile(array $words, string $fileName, int $chunkSize, int $overlap): array
    {
        $ids = [];
        $documents = [];
        $metadata = [];
        $totalWords = count($words);

        for ($i = 0; $i < $totalWords; $i += ($chunkSize - $overlap)) {
            $chunk = array_slice($words, $i, $chunkSize);
            $chunkText = implode(" ", $chunk);

            if (StringHelpers::getTokenCount($chunkText) > ElephantEnum::MAX_CHUNK_SIZE->value) {
                $chunkSizeAdjusted = $chunkSize;
                while (StringHelpers::getTokenCount(implode(" ", array_slice($words, $i, $chunkSizeAdjusted))) > ElephantEnum::MAX_CHUNK_SIZE->value) {
                    $chunkSizeAdjusted--;
                }
                $chunk = array_slice($words, $i, $chunkSizeAdjusted);
                $chunkText = implode(" ", $chunk);
            }
            $ids[] = $fileName . '_chunk_' . $i;
            $documents[] = $chunkText;
            $metadata[] = [$fileName => $fileName];
        }

        return [$ids, $metadata, $documents];
    }
}