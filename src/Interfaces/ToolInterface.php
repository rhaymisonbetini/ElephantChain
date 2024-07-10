<?php


namespace Rhaymison\ElephantChain\Interfaces;

interface ToolInterface
{
    public function run(ModelChainInterface $llm, string $question): string;
}