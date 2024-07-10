<?php

namespace Rhaymison\ElephantChain\Tools;

use GuzzleHttp\Client;
use Rhaymison\ElephantChain\Interfaces\ModelChainInterface;
use Rhaymison\ElephantChain\Interfaces\ToolInterface;
use Rhaymison\ElephantChain\Prompts\PromptTemplate;
use Symfony\Component\DomCrawler\Crawler;

class DuckDuckGo implements ToolInterface
{
    private string $region;

    public function __construct(string $region)
    {
        $this->region = $region;
    }

    public function run(ModelChainInterface $llm, string $question): string
    {
        $form_params = [
            'q' => $question,
        ];

        if ($this->region) {
            $form_params['kl'] = $this->region;
        }


        $client = new Client(["verify" => false]);
        $response = $client->post('https://lite.duckduckgo.com/lite/', [
            'form_params' => $form_params
        ]);
        $content = $response->getBody()->getContents();

        $crawler = new Crawler($content);

        $weblinks = $crawler->filter('table:nth-of-type(3) .result-link');
        $webSnippets = $crawler->filter('table:nth-of-type(3) .result-snippet');

        $text = $weblinks->each(function (Crawler $node, $i) use ($webSnippets) {
            return [
                "title" => $node->html(),
                "url" => $node->attr('href'),
                "body" => trim($webSnippets->eq($i)->text())
            ];
        });

        $text = array_slice($text, 0, 3);

        $toolFinal = "";
        foreach ($text as $dkSearch) {
            $toolResponse = $this->apiExtractText($dkSearch['url']) . PHP_EOL;
            if (strlen($toolResponse) > 1000) {
                $toolResponse = substr($toolResponse, 0, 1000);
            }
            $prompt = "Create a summary for this context in language of the question. max sumary lengh: 300 caracteres." . PHP_EOL . 'Question: ' . $question . PHP_EOL . "conext: " . PHP_EOL . $toolResponse;
            $prompt = PromptTemplate::createPromptTemplate($prompt, []);
            $toolFinal .= $llm->inference($prompt) . PHP_EOL;
        }

        return $toolFinal;
    }

    function apiExtractText(string $url): string
    {
        $client = new Client(["verify" => false]);
        $response = $client->get($url);
        $content = $response->getBody()->getContents();

        $crawler = new Crawler($content);
        return $this->cleanText($crawler->filter('body')->text()) ?? '';
    }

    private function cleanText(string $text): array|string|null
    {
        $text = trim($text);

        $text = preg_replace("/(\n){4,}/", "\n\n\n", $text);
        $text = preg_replace("/ {3,}/", " ", $text);
        $text = preg_replace("/(\t)/", "", $text);
        return preg_replace("/\n+(\s*\n)*/", "\n", $text);
    }
}