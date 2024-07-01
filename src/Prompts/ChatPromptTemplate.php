<?php

namespace Rhaymison\ElephantChain\Prompts;

use Rhaymison\ElephantChain\Enuns\PromptsEnum;
use Rhaymison\ElephantChain\Helpers\StringHelpers;

class ChatPromptTemplate
{

    public static function chatTemplate(string $question, array $arguments, array $memory = [], string $system = null): array
    {
        return [
            'system' => $system ?? PromptsEnum::SYSTEM_SIMPLE_PROMPT_RETRIEVER->value,
            'user' => StringHelpers::transformForSimplePrompt($question, $arguments),
            'memory' => !empty($memory) ? $memory : []
        ];
    }

}