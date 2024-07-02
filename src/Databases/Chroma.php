<?php

namespace Rhaymison\ElephantChain\Databases;

use Codewithkyrian\ChromaDB\ChromaDB;
use Codewithkyrian\ChromaDB\Client;
use Rhaymison\ElephantChain\Embeddings\OpenAIEmbeddingFunction;
use Codewithkyrian\ChromaDB\Generated\Responses\QueryItemsResponse;
use Codewithkyrian\ChromaDB\Resources\CollectionResource;
use Rhaymison\ElephantChain\Embeddings\EmbeddingFunction;
use Rhaymison\ElephantChain\Embeddings\GeminiEmbeddingsFunction;
use Rhaymison\ElephantChain\Embeddings\MixtralEmbeddingFunction;
use Rhaymison\ElephantChain\Embeddings\OpenAIEmbeddingFunction as ElephantOpenAiEmbeddingsFunction;

class Chroma
{
    private string $url;
    private int $port;
    private string $database;
    private string $tenant;
    private string|null $authToken;
    private Client $chroma;

    public function __construct(string $url, int $port, string $database, string $tenant, string|null $authToken = null)
    {
        $this->url = $url;
        $this->port = $port;
        $this->database = $database;
        $this->tenant = $tenant;
        $this->authToken = $authToken;
        $this->chroma = $authToken ? $this->authChroma() : $this->simpleChroma();
    }

    /**
     * @return Client
     */
    public function simpleChroma(): Client
    {
        return ChromaDB::factory()
            ->withHost($this->url)
            ->withPort($this->port)
            ->withDatabase($this->database)
            ->withTenant($this->tenant)
            ->connect();
    }

    /**
     * @return Client
     */
    public function authChroma(): Client
    {
        return ChromaDB::factory()
            ->withHost($this->url)
            ->withPort($this->port)
            ->withDatabase($this->database)
            ->withTenant($this->tenant)
            ->withAuthToken($this->authToken)
            ->connect();
    }

    /**
     * @param string $apiKey
     * @param string $model
     * @param string $organization
     * @return OpenAIEmbeddingFunction
     */
    public function openAIEmbeddingsFunction(string $apiKey, string $model, string $organization = ''): OpenAIEmbeddingFunction
    {
        return new OpenAIEmbeddingFunction($apiKey, $organization, $model);
    }

    /**
     * @param string $apiKey
     * @return GeminiEmbeddingsFunction
     */
    public function geminiEmbeddingsFunction(string $apiKey): GeminiEmbeddingsFunction
    {
        return new GeminiEmbeddingsFunction($apiKey);
    }


    /**
     * @param string $collection
     * @param EmbeddingFunction|null $embeddings
     * @return CollectionResource
     */
    public function getOrCreateCollection(string $collection, ?EmbeddingFunction $embeddings): CollectionResource
    {
        return $this->chroma->getOrCreateCollection($collection, null, $embeddings);
    }

    /**
     * @param CollectionResource $collection
     * @param array $ids
     * @param array $metadata
     * @param array $documents
     * @return void
     */
    public function addVectors(CollectionResource $collection, array $ids, array $metadata, array $documents): void
    {
        $collection->add(
            ids: $ids,
            metadatas: $metadata,
            documents: $documents
        );
    }

    /**
     * @param CollectionResource $collection
     * @param array $ids
     * @param array $metadata
     * @param array $documents
     * @return void
     */
    public function updateVectors(CollectionResource $collection, array $ids, array $metadata, array $documents): void
    {
        $collection->update(
            ids: $ids,
            metadatas: $metadata,
            documents: $documents
        );
    }

    /**
     * @param CollectionResource $collection
     * @param array $queryTexts
     * @param int $results
     * @param array $query
     * @return QueryItemsResponse
     */
    public function retriever(CollectionResource $collection, array $queryTexts, int $results, array $query = []): QueryItemsResponse
    {
        return $collection->query(
            queryTexts: $queryTexts,
            nResults: $results,
            include: ['metadatas', 'documents']
        );
    }

    /**
     * @param string $collection
     * @return void
     */
    public function deleteCollection(string $collection): void
    {
        $this->chroma->deleteCollection($collection);
    }

}