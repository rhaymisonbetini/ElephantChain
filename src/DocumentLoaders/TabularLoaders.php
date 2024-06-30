<?php

namespace Rhaymison\ElephantChain\DocumentLoaders;

class TabularLoaders
{
    public function csvLoader(string $filename, string $separator = ',', ?int $length = 1000): array
    {
        $data = [];
        if (($handle = fopen($filename, "r")) !== false) {
            $header = fgetcsv($handle, $length, $separator);
            while (($row = fgetcsv($handle, $length, $separator)) !== false) {
                $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }
        return $data;
    }
}