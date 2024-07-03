<?php

namespace Rhaymison\ElephantChain\DocumentLoaders;

use Rhaymison\ElephantChain\Helpers\ChunkGenerator;
use Rhaymison\ElephantChain\Helpers\StringHelpers;
use Rhaymison\ElephantChain\Validators\PathValidators;
use PhpOffice\PhpWord\IOFactory;
use Exception;

class DocLoaders
{
    /**
     * maxTokens for openai 8192
     * @param string $path
     * @param int $chunkSize
     * @param int $overlap
     * @return array
     */
    public function dirDocLoaders(string $path, int $chunkSize, int $overlap): array
    {
        $ids = [];
        $documents = [];
        $metadata = [];

        PathValidators::pathValidator($path);
        $files = scandir($path);

        foreach ($files as $file) {
            $filePath = $path . DIRECTORY_SEPARATOR . $file;
            if (is_file($filePath) && pathinfo($filePath, PATHINFO_EXTENSION) === 'doc') {
                try {
                    $content = $this->getDocContent($filePath);
                    $content = StringHelpers::clearString($content);
                    list($fileIds, $fileMetadata, $fileDocuments) = ChunkGenerator::generateChunksFromFile($content, $file, $chunkSize, $overlap);
                    $ids = array_merge($ids, $fileIds);
                    $documents = array_merge($documents, $fileDocuments);
                    $metadata = array_merge($metadata, $fileMetadata);
                } catch (Exception $e) {
                    // Log or handle the error appropriately
                    echo "Error processing file $file: " . $e->getMessage() . "\n";
                }
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
    public function singleDocFileLoader(string $filePath, int $chunkSize, int $overlap): array
    {
        PathValidators::fileValidator($filePath);
        $fileName = basename($filePath);
        try {
            $content = $this->getDocContent($filePath);
            $content = StringHelpers::clearString($content);
            return ChunkGenerator::generateChunksFromFile($content, $fileName, $chunkSize, $overlap);
        } catch (Exception $e) {
            // Log or handle the error appropriately
            echo "Error processing file $filePath: " . $e->getMessage() . "\n";
            return [[], [], []];
        }
    }

    /**
     * Extract content from a Word document
     * @param string $filePath
     * @return string
     * @throws Exception
     */
    private function getDocContent(string $filePath): string
    {
        $text = '';
        $phpWord = IOFactory::load($filePath, 'MsDoc');
        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                if (method_exists($element, 'getText')) {
                    $text .= $element->getText();
                }
            }
        }
        return $text;
    }
}
