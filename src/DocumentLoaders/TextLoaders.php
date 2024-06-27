<?php

namespace Rhaymison\ElephantChain\DocumentLoaders;

use InvalidArgumentException;
use Rhaymison\ElephantChain\Enuns\ElephantEnum;

class TextLoaders
{
    /**
     * maxTokens for openai 8192
     * @param string $path
     * @param int $chunkSize
     * @param int $overlap
     * @return array
     */
    function textLoaders(string $path, int $chunkSize, int $overlap): array
    {
        $ids = [];
        $documents = [];
        $metadata = [];

        if (!is_dir($path)) {
            throw new InvalidArgumentException("O caminho especificado não é um diretório válido: $path");
        }

        $files = scandir($path);

        foreach ($files as $file) {
            $filePath = $path . DIRECTORY_SEPARATOR . $file;

            if (is_file($filePath) && pathinfo($filePath, PATHINFO_EXTENSION) === 'txt') {
                $content = file_get_contents($filePath);
                $content = str_replace("\n", " ", $content);
                $content = mb_convert_encoding($content, 'UTF-8', 'auto');
                $content = $this->removeAccents($content);
                $words = preg_split('/\s+/', $content);
                $totalWords = count($words);

                for ($i = 0; $i < $totalWords; $i += ($chunkSize - $overlap)) {
                    $chunk = array_slice($words, $i, $chunkSize);
                    $chunkText = implode(" ", $chunk);

                    if ($this->getTokenCount($chunkText) > ElephantEnum::MAX_CHUNK_SIZE->value) {
                        $chunkSizeAdjusted = $chunkSize;
                        while ($this->getTokenCount(implode(" ", array_slice($words, $i, $chunkSizeAdjusted))) > ElephantEnum::MAX_CHUNK_SIZE->value) {
                            $chunkSizeAdjusted--;
                        }
                        $chunk = array_slice($words, $i, $chunkSizeAdjusted);
                        $chunkText = implode(" ", $chunk);
                    }
                    $ids[] = $file . '_chunk_' . $i;
                    $documents[] = $chunkText;
                    $metadata[] = [$file => $file];
                }
            }
        }

        return [$ids, $metadata, $documents];
    }

    function getTokenCount($text): int
    {
        return count(preg_split('/\s+|(?<=\W)(?=\w)|(?<=\w)(?=\W)/', $text));
    }

    private function removeAccents(string $text): string
    {
        $text = preg_replace('/[áàâãäå]/ui', 'a', $text);
        $text = preg_replace('/[éèêë]/ui', 'e', $text);
        $text = preg_replace('/[íìîï]/ui', 'i', $text);
        $text = preg_replace('/[óòôõö]/ui', 'o', $text);
        $text = preg_replace('/[úùûü]/ui', 'u', $text);
        $text = preg_replace('/[ç]/ui', 'c', $text);
        return preg_replace('/[^a-z0-9\s]/ui', '', $text);
    }

}