<?php

namespace Rhaymison\ElephantChain\DocumentLoaders;

use Rhaymison\ElephantChain\Helpers\ChunkGenerator;
use Rhaymison\ElephantChain\Helpers\StringHelpers;
use Rhaymison\ElephantChain\Validators\PathValidators;
use Spatie\PdfToText\Exceptions\PdfNotFound;
use Spatie\PdfToText\Pdf;

class PdfLoaders
{

    /**
     * maxTokens for openai 8192
     * @param string $path
     * @param int $chunkSize
     * @param int $overlap
     * @return array
     * @throws PdfNotFound
     */
    public function dirPdfLoader(string $path, int $chunkSize, int $overlap): array
    {
        $ids = [];
        $documents = [];
        $metadata = [];

        PathValidators::pathValidator($path);
        $files = scandir($path);

        foreach ($files as $file) {
            $filePath = $path . DIRECTORY_SEPARATOR . $file;

            if (is_file($filePath) && pathinfo($filePath, PATHINFO_EXTENSION) === 'pdf') {
                $text = (new Pdf())->setPdf($filePath)->text();
                $content = StringHelpers::clearString($text);
                list($fileIds, $fileMetadata, $fileDocuments) = ChunkGenerator::generateChunksFromFile($content, $file, $chunkSize, $overlap);
                $ids = array_merge($ids, $fileIds);
                $documents = array_merge($documents, $fileDocuments);
                $metadata = array_merge($metadata, $fileMetadata);
            }
        }
        return [$ids, $metadata, $documents];
    }

    /**
     * @param string $fileName
     * @param int $chunkSize
     * @param int $overlap
     * @return array
     * @throws PdfNotFound
     */
    public function singlePdfLoader(string $fileName, int $chunkSize, int $overlap): array
    {
        PathValidators::fileValidator($fileName);
        $text = (new Pdf())
            ->setPdf($fileName)
            ->text();
        $content = StringHelpers::clearString($text);
        return ChunkGenerator::generateChunksFromFile($content, $fileName, $chunkSize, $overlap);
    }


}