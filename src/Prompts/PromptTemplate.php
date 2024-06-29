<?php

namespace Rhaymison\ElephantChain\Prompts;

use Rhaymison\ElephantChain\Enuns\PromptsEnum;

class PromptTemplate
{

    public function createPromptTemplate(string $question, array $arguments, string $system = null): array
    {
        return [
            'system' => $system ?? PromptsEnum::SYSTEM_SIMPLE_PROMPT_RETRIEVER->value,
            'user' => $this->transformForSimplePromot($question, $arguments)
        ];
    }

    public function transformForSimplePromot(string $question, array $arguments): string
    {
        preg_match_all('/\{\$(.*?)\}/', $question, $matches);
        foreach ($matches[1] as $placeholder) {
            if (array_key_exists($placeholder, $arguments)) {
                $text = str_replace('{$' . $placeholder . '}', $arguments[$placeholder], $question);
            }
        }
        return $text ?? '';
    }
}