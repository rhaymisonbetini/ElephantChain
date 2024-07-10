<?php

namespace Rhaymison\ElephantChain\Tools;

use Rhaymison\ElephantChain\Enuns\PromptsEnum;
use Rhaymison\ElephantChain\Interfaces\ModelChainInterface;
use Rhaymison\ElephantChain\Interfaces\ToolInterface;
use Rhaymison\ElephantChain\Prompts\PromptTemplate;

class Wikipedia implements ToolInterface
{
    private int $limit;
    private string $apiUrl = 'https://en.wikipedia.org/w/api.php';

    public function __construct($limit = 10)
    {
        $this->limit = $limit;
    }

    public function run(ModelChainInterface $llm, string $question): string
    {
        $prompt = PromptTemplate::createPromptTemplate('Question: ' . $question, [], PromptsEnum::WIKIPEDIA_PROMPT_THEME->value);
        $searchTheme = $llm->inference($prompt);
        $results = $this->dispatch($searchTheme);

        if (empty($results)) {
            return 'No results found.';
        }

        $formattedResults = $this->formatResults($results);
        $pageContents = $this->getPageContents(array_column($results, 'pageid'));

        $finalPrompt = $formattedResults . $pageContents;
        if (strlen($finalPrompt) > 500) {
            $finalPrompt = substr($finalPrompt, 0, 500 * $this->limit);
        }

        $prompt = "Create a summary for this context in language of the question." . PHP_EOL . 'Question: ' . $question . PHP_EOL . "conext: " . PHP_EOL . $finalPrompt;
        $prompt = PromptTemplate::createPromptTemplate($prompt, []);
        return $llm->inference($prompt);
    }

    public function dispatch($query): mixed
    {
        $params = [
            'action' => 'query',
            'list' => 'search',
            'srsearch' => $query,
            'format' => 'json',
            'srlimit' => $this->limit,
        ];

        $url = $this->apiUrl . '?' . http_build_query($params);

        $response = $this->makeRequest($url);

        if ($response && isset($response['query']['search'])) {
            if (empty($response['query']['search']) && isset($response['query']['searchinfo']['suggestion'])) {
                $suggestedQuery = $response['query']['searchinfo']['suggestion'];
                return $this->dispatch($suggestedQuery);
            }
            return $response['query']['search'];
        }

        return '';
    }

    private function makeRequest($url)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $output = curl_exec($ch);

        if (curl_errno($ch)) {
            return null;
        }

        curl_close($ch);

        return json_decode($output, true);
    }

    private function formatResults(array $results): string
    {
        $formattedResults = '';

        foreach ($results as $result) {
            $formattedResults .= 'Title: ' . $result['title'] . PHP_EOL;
            $formattedResults .= 'Snippet: ' . strip_tags($result['snippet']) . PHP_EOL;
            $formattedResults .= PHP_EOL;
        }

        return $formattedResults;
    }

    private function getPageContents(array $pageIds): string
    {
        $params = [
            'action' => 'query',
            'pageids' => implode('|', $pageIds),
            'prop' => 'extracts',
            'explaintext' => true,
            'format' => 'json',
        ];

        $url = $this->apiUrl . '?' . http_build_query($params);

        $response = $this->makeRequest($url);

        $pageContents = '';

        if ($response && isset($response['query']['pages'])) {
            foreach ($response['query']['pages'] as $page) {
                $pageContents .= 'Page ID: ' . $page['pageid'] . PHP_EOL;
                $pageContents .= 'Title: ' . $page['title'] . PHP_EOL;
                $pageContents .= 'Content: ' . PHP_EOL;
                $pageContents .= array_key_exists('extract', $page) ? $page['extract'] : '';
            }
        }

        return $pageContents;
    }
}
