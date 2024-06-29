<?php

namespace Rhaymison\ElephantChain\Chains;

use InvalidArgumentException;
use Rhaymison\ElephantChain\Enuns\GatesEnum;
use Rhaymison\ElephantChain\Llm\GeminiChain;
use Rhaymison\ElephantChain\Llm\MixtralChain;
use Rhaymison\ElephantChain\Llm\OpenAiChain;
use Rhaymison\ElephantChain\Prompts\RetrieverPromptsTemplate;

class RetrieverChain
{
    /**
     * @var OpenAiChain|GeminiChain|MixtralChain
     */
    private OpenAiChain|GeminiChain|MixtralChain $model;

    /**
     * @param OpenAiChain|GeminiChain|MixtralChain $model
     */
    public function __construct(OpenAiChain|GeminiChain|MixtralChain $model)
    {
        $this->model = $model;
    }

    /**
     * @param mixed $retriever
     * @param array $prompt
     * @return string
     */
    public function dispatch(mixed $retriever, array $prompt): string
    {
        if (isset($retriever->documents[0])) {
            if (count($retriever->documents[0]) > 1) {

                $summary = '';
                $promptBase = RetrieverPromptsTemplate::internalSummaryRetrieverPrepareTemplate();
                foreach ($retriever->documents[0] as $key => $document) {
                    $promptBase['user'] = RetrieverPromptsTemplate::transformForSummaryRetriever($promptBase['user'], $prompt['user'], $document);
                    $summary .= "\n\n" . $this->defineGate($this->model, $document ?? '', $promptBase, GatesEnum::SUMARY_GATE->value);
                }
                return $this->defineGate($this->model, $summary, $prompt, GatesEnum::INFERENCE_GATE->value);

            } else {
                return $this->defineGate($this->model, $retriever->documents[0][0] ?? '', $prompt, GatesEnum::INFERENCE_GATE->value);
            }
        }
    }

    /**
     * @param $model
     * @param string $fractal
     * @param array $prompt
     * @param int $gate
     * @return string
     */
    public function defineGate($model, string $fractal, array $prompt, int $gate): string
    {
        return match (true) {
            $model instanceof OpenAiChain => $this->handleOpenAiGate($model, $fractal, $prompt, $gate),
            $model instanceof GeminiChain => $this->handleGeminiGate($model, $fractal, $prompt, $gate),
            $model instanceof MixtralChain => $this->handleMixtralGate($model, $fractal, $prompt, $gate),
            default => throw new InvalidArgumentException('invalid model'),
        };
    }

    /**
     * @param OpenAiChain|GeminiChain|MixtralChain $model
     * @param string $fractal
     * @param array $prompt
     * @param int $gate
     * @return string
     */
    private function handleOpenAiGate(OpenAiChain|GeminiChain|MixtralChain $model, string $fractal, array $prompt, int $gate): string
    {
        if ($gate == GatesEnum::INFERENCE_GATE->value) {
            $prompt['user'] = RetrieverPromptsTemplate::transformPrompt($prompt['user'], $fractal);
            return $this->model->inference($prompt);
        }

        if ($gate == GatesEnum::SUMARY_GATE->value) {
            return $this->model->inference($prompt);
        }

        return '';
    }

    /**
     * @param OpenAiChain|GeminiChain|MixtralChain $model
     * @param string $fractal
     * @param array $prompt
     * @param int $gate
     * @return string
     */
    private function handleGeminiGate(OpenAiChain|GeminiChain|MixtralChain $model, string $fractal, array $prompt, int $gate): string
    {
        // TODO: Implement Gemini gate logic
        return '';
    }

    /**
     * @param OpenAiChain|GeminiChain|MixtralChain $model
     * @param string $fractal
     * @param array $prompt
     * @param int $gate
     * @return string
     */
    private function handleMixtralGate(OpenAiChain|GeminiChain|MixtralChain $model, string $fractal, array $prompt, int $gate): string
    {
        // TODO: Implement Mixtral gate logic
        return '';
    }

}