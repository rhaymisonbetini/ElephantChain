<?php

namespace Rhaymison\ElephantChain\Chains;

use InvalidArgumentException;
use Rhaymison\ElephantChain\Interfaces\ModelChainInterface;
use Rhaymison\ElephantChain\Interfaces\ToolInterface;

class Chain
{

    /**
     * @var ModelChainInterface
     */
    protected ModelChainInterface $model;

    protected ?ToolInterface $tool;

    /**
     * @param ModelChainInterface $model
     * @param ToolInterface|null $tool
     */
    public function __construct(ModelChainInterface $model, ToolInterface $tool = null)
    {
        $this->model = $model;
        $this->tool = $tool;
    }

    /**
     * @param array $prompt
     * @return string
     */
    public function run(array $prompt): string
    {
        if ($this->tool !== null) {
            $toolResponse = $this->executeTool($prompt);
            $prompt['user'] = $prompt['user'] . PHP_EOL . '### additional info : ' . $toolResponse;
        }
        return $this->defineGate($prompt);
    }

    public function executeTool(array $prompt): string
    {
        return $this->tool->run($this->model, $prompt['user']);
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