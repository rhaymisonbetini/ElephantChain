<?php

namespace Rhaymison\ElephantChain\Prompts;

use Rhaymison\ElephantChain\Enuns\PromptsEnum;

class PromptTemplate
{

    public static function createPromptTemplate(string $question, array $arguments, string $system = null): array
    {
        return [
            'system' => $system ?? PromptsEnum::SYSTEM_SIMPLE_PROMPT_RETRIEVER->value,
            'user' => self::transformForSimplePromot($question, $arguments)
        ];
    }

    public static function sequentialPromptTemplate(string $question, $system = null): array
    {
        return [
            'system' => $system ?? PromptsEnum::SYSTEM_SIMPLE_PROMPT_RETRIEVER->value,
            'user' => $question
        ];
    }

    public static function transformForSimplePromot(string $question, array $arguments): string
    {
        preg_match_all('/\{\s*(.*?)\s*\}/', $question, $matches);

        foreach ($matches[1] as $placeholder) {
            if (array_key_exists($placeholder, $arguments)) {
                $question = str_replace('{' . $placeholder . '}', $arguments[$placeholder], $question);
            }
        }

        return $question;
    }
}