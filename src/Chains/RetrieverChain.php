<?php

namespace Rhaymison\ElephantChain\Chains;

use Psr\Http\Client\ClientExceptionInterface;
use Rhaymison\ElephantChain\Llm\GeminiChain;
use Rhaymison\ElephantChain\Llm\MixtralChain;
use Rhaymison\ElephantChain\Llm\OpenAiChain;
use Rhaymison\ElephantChain\Prompts\RetrieverPromptsTemplate;

class RetrieverChain extends Chain
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
     * @throws ClientExceptionInterface
     */
    public function dispatch(array $retriever, array $prompt): string
    {
        $summary = '';
        if (count($retriever) > 1) {
            $promptBase = RetrieverPromptsTemplate::internalSummaryRetrieverPrepareTemplate();
            foreach ($retriever as $document) {
                $promptBase['user'] = RetrieverPromptsTemplate::transformForSummaryRetriever($promptBase['user'], $prompt['user'], $document);
                $summary .= "\n\n" . $this->defineGate($promptBase);
            }
        } else {
            $summary = $retriever[0] ?? '';
        }
        $prompt['user'] = RetrieverPromptsTemplate::transformPrompt($prompt['user'], $summary);
        return $this->defineGate($prompt);
    }

}