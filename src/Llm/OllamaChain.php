<?php

namespace Rhaymison\ElephantChain\Llm;

use GuzzleHttp\Client;

class OllamaChain
{
    private string $url;
    private string $model;
    private int $temperature;
    private Client $client;


    public function __construct(string $url, string $model, float $temperature = 0.5)
    {
        $this->url = $url;
        $this->model = $model;

        $this->client = new Client([
            'base_uri' => $this->url,
            'headers' => [
                'Content-Type' => 'application/json',
            ]
        ]);
    }

    public function inference(array $prompt)
    {
        $response = $this->client->post('api/embeddings', [
            'json' => [
                'model' => $this->model,
                'options' => [
                    'temperature' => $this->temperature
                ],
                'messages' => [
                    ['role' => 'system', 'content' => $prompt['system']],
                    ['role' => 'user', 'content' => $prompt['user']],
                ]
            ]
        ]);
    }

}