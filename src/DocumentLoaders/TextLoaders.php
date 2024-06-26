<?php

namespace Rhaymison\ElephantChain\DocumentLoaders;

use Rhaymison\ElephantChain\Helpers\ChunkGenerator;
use Rhaymison\ElephantChain\Helpers\StringHelpers;
use Rhaymison\ElephantChain\Validators\PathValidators;

class TextLoaders
{
    /**
     * maxTokens for openai 8192
     * @param string $path
     * @param int $chunkSize
     * @param int $overlap
     * @return array
     */
    public function dirTextLoaders(string $path, int $chunkSize, int $overlap): array
    {
        $ids = [];
        $documents = [];
        $metadata = [];

        PathValidators::pathValidator($path);
        $files = scandir($path);

        foreach ($files as $file) {
            $filePath = $path . DIRECTORY_SEPARATOR . $file;

            if (is_file($filePath) && pathinfo($filePath, PATHINFO_EXTENSION) === 'txt') {
                $content = file_get_contents($filePath);
                $content = StringHelpers::clearString($content);
                list($fileIds, $fileMetadata, $fileDocuments) = ChunkGenerator::generateChunksFromFile($content, $file, $chunkSize, $overlap);
                $ids = array_merge($ids, $fileIds);
                $documents = array_merge($documents, $fileDocuments);
                $metadata = array_merge($metadata, $fileMetadata);
            }
        }
        return [$ids, $metadata, $documents];
    }


    /**
     * @param string $filePath
     * @param int $chunkSize
     * @param int $overlap
     * @return array
     */
    public function singleTextFileLoader(string $filePath, int $chunkSize, int $overlap): array
    {
        PathValidators::fileValidator($filePath);
        $fileName = basename($filePath);
        $content = file_get_contents($filePath);
        $content = StringHelpers::clearString($content);
        return ChunkGenerator::generateChunksFromFile($content, $fileName, $chunkSize, $overlap);
    }

}