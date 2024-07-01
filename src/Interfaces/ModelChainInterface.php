<?php

namespace Rhaymison\ElephantChain\Interfaces;

interface ModelChainInterface
{
    public function inference(array $prompt): string;
}