<?php

namespace Rhaymison\ElephantChain\Validators;

use InvalidArgumentException;

class PathValidators
{

    public static function fileValidator(string $filePath): void
    {
        if (!is_file($filePath) || pathinfo($filePath, PATHINFO_EXTENSION) !== 'pdf') {
            throw new InvalidArgumentException("O caminho especificado não é um arquivo de texto válido: $filePath");
        }
    }

    public static function pathValidator(string $filePath): void
    {
        if (!is_dir($filePath)) {
            throw new InvalidArgumentException("O caminho especificado não é um diretório válido: $filePath");
        }
    }

}