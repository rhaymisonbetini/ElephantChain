<?php

namespace Rhaymison\ElephantChain\Chains;

use Psr\Http\Client\ClientExceptionInterface;

class ChatMemoryChain extends Chain
{
    private string $filePath;

    public function __construct($llm, string $room)
    {
        Parent::__construct($llm);
        $this->filePath = __DIR__ . "/memory/chat/chat_memory_$room.json";
        if (!file_exists($this->filePath)) {
            file_put_contents($this->filePath, json_encode([]));
        }
    }

    /**
     * @param array $prompt
     * @return string
     * @throws ClientExceptionInterface
     */
    public function dispatchChainMemory(array $prompt): string
    {
        $memory = json_encode($this->getConversation());
        $userOriginalMessage = $prompt['user'];
        $prompt['user'] = "\n\n" . "## History conversation: " . "\n\n" . $memory . "\n\n" . "User : " . $prompt['user'];
        $response = $this->model->inference($prompt);
        $this->addMessage($userOriginalMessage, $response);
        return $response;
    }

    /**
     * @param $userMessage
     * @param $botResponse
     * @return void
     */
    public function addMessage($userMessage, $botResponse): void
    {
        $conversation = $this->getConversation();
        $conversation[] = ['user' => $userMessage, 'IA' => $botResponse];
        file_put_contents($this->filePath, json_encode($conversation));
    }

    /**
     * @return mixed
     */
    public function getConversation(): mixed
    {
        return json_decode(file_get_contents($this->filePath), true);
    }

    /**
     * @return void
     */
    public function clearMemory(): void
    {
        if (file_exists($this->filePath)) {
            file_put_contents($this->filePath, json_encode([]));
        }
    }

    /**
     * @return void
     */
    public function deleteMemory(): void
    {
        if (file_exists($this->filePath)) {
            unlink($this->filePath);
        }
    }
}