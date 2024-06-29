<?php

namespace Rhaymison\ElephantChain\Chains;

use JetBrains\PhpStorm\Pure;
use Rhaymison\ElephantChain\Enuns\GatesEnum;
use Rhaymison\ElephantChain\Llm\GeminiChain;
use Rhaymison\ElephantChain\Llm\MixtralChain;
use Rhaymison\ElephantChain\Llm\OpenAiChain;
use Rhaymison\ElephantChain\Prompts\RetrieverPromptsTemplate;

class RetrieverChain extends Chains
{

    /**
     * @param OpenAiChain|GeminiChain|MixtralChain $model
     */
    public function __construct(OpenAiChain|GeminiChain|MixtralChain $model)
    {
        parent::__construct($model);
    }

    /**
     * @param mixed $retriever
     * @param array $prompt
     * @return string
     */
    public function dispatch(mixed $retriever, array $prompt): string
    {
        if (isset($retriever->documents[0])) {
            $summary = '';

            if (count($retriever->documents[0]) > 1) {
                $promptBase = RetrieverPromptsTemplate::internalSummaryRetrieverPrepareTemplate();
                foreach ($retriever->documents[0] as $document) {
                    $promptBase['user'] = RetrieverPromptsTemplate::transformForSummaryRetriever($promptBase['user'], $prompt['user'], $document);
                    $summary .= "\n\n" . $this->defineGate($promptBase);
                }
            } else {
                $summary = $retriever->documents[0][0] ?? '';
            }

            $prompt['user'] = RetrieverPromptsTemplate::transformPrompt($prompt['user'], $summary);
            return $this->defineGate($prompt);
        }
        return '';
    }

}