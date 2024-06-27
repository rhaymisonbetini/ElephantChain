<?php

namespace Rhaymison\ElephantChain\DocumentLoaders;

use Illuminate\Support\Facades\File;
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
    public function textLoaders(string $path, int $chunkSize, int $overlap): array
    {
        $ids = [];
        $documents = [];
        $metadata = [];

        $files = File::files($path);
        foreach ($files as $file) {
            if ($file->getExtension() === 'txt') {
                $content = File::get($file->getRealPath());
                $content = str_replace("\n", " ", $content);
                $content = mb_convert_encoding($content, 'UTF-8', 'auto');

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

                    $ids[] = $file->getFilename() . '_chunk_' . $i;
                    $documents[] = $chunkText;
                    $metadata[] = [$file->getFilename() => $file->getFilename()];
                }
            }
        }
        return [$ids, $documents, $metadata];
    }

    function getTokenCount($text): int
    {
        return count(preg_split('/\s+|(?<=\W)(?=\w)|(?<=\w)(?=\W)/', $text));
    }


}