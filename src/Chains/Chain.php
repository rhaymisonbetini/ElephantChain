<?php

namespace Rhaymison\ElephantChain\Chains;

use InvalidArgumentException;
use Psr\Http\Client\ClientExceptionInterface;
use Rhaymison\ElephantChain\Interfaces\ModelChainInterface;
use Rhaymison\ElephantChain\Llm\GeminiChain;
use Rhaymison\ElephantChain\Llm\MixtralChain;
use Rhaymison\ElephantChain\Llm\OpenAiChain;

class Chain
{

    /**
     * @var ModelChainInterface
     */
    protected ModelChainInterface $model;

    /**
     * @param ModelChainInterface $model
     */
    public function __construct(ModelChainInterface $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $prompt
     * @return string
     */
    public function run(array $prompt): string
    {
        return $this->defineGate($prompt);
    }

    /**
     * @param array $prompt
     * @return string
     */
    public function defineGate(array $prompt): string
    {
        return match (true) {
            $this->model instanceof ModelChainInterface => $this->model->inference($prompt),
            default => throw new InvalidArgumentException('Invalid model'),
        };
    }
}