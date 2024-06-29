<?php

namespace Rhaymison\ElephantChain\Prompts;

use Rhaymison\ElephantChain\Enuns\PromptsEnum;

class RetrieverPromptsTemplate
{

    /**
     * @param string $question
     * @param string|null $system
     * @return array
     */
    public static function simpleRetrieverPromptTemplate(string $question, string $system = null): array
    {

        return [
            'system' => $system ?? PromptsEnum::SYSTEM_SIMPLE_PROMPT_RETRIEVER->value,
            'user' => $question
        ];
    }

    public static function internalSummaryRetrieverPrepareTemplate(): array
    {
        return [
            'system' => PromptsEnum::SYSTEM_SIMPLE_PROMPT_SUMMARY_INTERAL_RETRIEVER->value,
            'user' => PromptsEnum::SYSTEM_USER_PROMPT_SUMMARY_INTERAL_RETRIEVER->value
        ];
    }

    /**
     * @param string $user
     * @param string $context
     * @return string
     */
    public static function transformPrompt(string $user, string $context): string
    {
        return "User Question: " . $user . "\n\n" . "Context: " . $context;
    }

    public static function transformForSummaryRetriever(string $internalPrompt, string $user, string $context)
    {
        return $internalPrompt . "\n\n" . "User Question: " . $user . "\n\n" . "Context: " . $context;
    }
}