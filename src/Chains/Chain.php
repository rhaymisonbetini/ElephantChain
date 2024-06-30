<?php

namespace Rhaymison\ElephantChain\Chains;

use InvalidArgumentException;
use Psr\Http\Client\ClientExceptionInterface;
use Rhaymison\ElephantChain\Llm\GeminiChain;
use Rhaymison\ElephantChain\Llm\MixtralChain;
use Rhaymison\ElephantChain\Llm\OpenAiChain;

class Chain
{

    /**
     * @var OpenAiChain|GeminiChain|MixtralChain
     */
    protected OpenAiChain|GeminiChain|MixtralChain $model;

    /**
     * @param OpenAiChain|GeminiChain|MixtralChain $model
     */
    public function __construct(OpenAiChain|GeminiChain|MixtralChain $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $prompt
     * @return string
     * @throws ClientExceptionInterface
     */
    public function run(array $prompt): string
    {
        return $this->defineGate($prompt);
    }


    /**
     * @param array $prompt
     * @return string
     * @throws ClientExceptionInterface
     */
    public function defineGate(array $prompt): string
    {
        return match (true) {
            $this->model instanceof OpenAiChain => $this->handleOpenAiGate($prompt),
            $this->model instanceof GeminiChain => $this->handleGeminiGate($prompt),
            $this->model instanceof MixtralChain => $this->handleMixtralGate($prompt),
            default => throw new InvalidArgumentException('invalid model'),
        };
    }

    /**
     * @param array $prompt
     * @return string
     * @throws ClientExceptionInterface
     */
    private function handleOpenAiGate(array $prompt): string
    {
        return $this->model->inference($prompt);
    }

    /**
     * @param array $prompt
     * @return string
     * @throws ClientExceptionInterface
     */
    private function handleGeminiGate(array $prompt): string
    {
        return $this->model->inference($prompt);
    }

    /**
     * @param array $prompt
     * @return string
     */
    private function handleMixtralGate(array $prompt): string
    {
        // TODO: Implement Mixtral gate logic
        return '';
    }

}