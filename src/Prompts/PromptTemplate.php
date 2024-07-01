<?php

namespace Rhaymison\ElephantChain\Prompts;

use Rhaymison\ElephantChain\Enuns\PromptsEnum;
use Rhaymison\ElephantChain\Helpers\StringHelpers;

class PromptTemplate
{

    public static function createPromptTemplate(string $question, array $arguments, string $system = null): array
    {
        return [
            'system' => $system ?? PromptsEnum::SYSTEM_SIMPLE_PROMPT_RETRIEVER->value,
            'user' => StringHelpers::transformForSimplePrompt($question, $arguments)
        ];
    }

    public static function sequentialPromptTemplate(string $question, $system = null): array
    {
        return [
            'system' => $system ?? PromptsEnum::SYSTEM_SIMPLE_PROMPT_RETRIEVER->value,
            'user' => $question
        ];
    }

}