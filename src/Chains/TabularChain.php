<?php

namespace Rhaymison\ElephantChain\Chains;

use Exception;
use Psr\Http\Client\ClientExceptionInterface;
use Rhaymison\ElephantChain\Enuns\PromptsEnum;
use Rhaymison\ElephantChain\Helpers\StringHelpers;
use Rhaymison\ElephantChain\Prompts\PromptTemplate;

class TabularChain extends Chain
{

    /**
     * @param array $datas
     * @param string $question
     * @return array
     * @throws ClientExceptionInterface
     */
    public function dispatchTabularChain(array $datas, string $question): string
    {
        $function = $this->createFilterFunction(array_slice($datas, 0, 4), $question);
        $sanitizedFunction = StringHelpers::sanitizeTabularEval($function);
        eval($sanitizedFunction);
        if (function_exists('filterData')) {
            $data = filterData($datas);
            print_r(json_encode($data));
            $question .= "\n\n" . "Datos para serem analisados na resposta: " . "\n\n" . json_encode($data);
            $prompt = PromptTemplate::createPromptTemplate($question, []);
            return $this->model->inference($prompt);
        } else {
            throw new Exception("Problem to execute eval function");
        }
    }

    /**
     * @param array $fragment
     * @param string $question
     * @return string
     * @throws ClientExceptionInterface
     */
    public function createFilterFunction(array $fragment, string $question): string
    {
        $prepare = PromptsEnum::SYSTEM_TABULAR_FUN_CREATE->value . "\n\n" .
            "User Question: " . $question . "\n\n" . $question . "\n\n" .
            "Real datas to create filter: " . "\n\n" . json_encode($fragment);
        $prompt = PromptTemplate::createPromptTemplate($prepare, []);
        return $this->model->inference($prompt);
    }
}