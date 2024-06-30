# Elephant Chain 🐘 - A Powerful Library for PHP Environments with LLMs

Welcome to **Elephant Chain 🐘**, a robust library crafted to seamlessly integrate Large Language Models (LLMs) into PHP
environments. Whether you're developing sophisticated natural language processing applications or enhancing your PHP
projects with AI capabilities, Elephant Chain equips you with the tools and functionalities to achieve this effortlessly
and efficiently.

## Key Features

- **Seamless Integration:** Easily incorporate LLMs into your existing PHP projects with minimal setup.
- **High Performance:** Optimized for performance, ensuring fast and responsive AI-powered applications.
- **Flexibility:** Supports a variety of LLMs, allowing you to select the model that best suits your needs.
- **User-Friendly:** Straightforward and intuitive design, making it accessible for developers of all skill levels.
- **Comprehensive Documentation:** Detailed documentation and examples to help you quickly get started and fully utilize
  the library's capabilities.

## Table of Contents

1. [Installation](#installation)
2. [Basic Usage](#basic-usage)
3. [Available Models](#available-models)
4. [Chains](#chains)
    - [Chain](#chain)
    - [RetrieverChain](#retrieverchain)
    - [SequentialChain](#sequentialchain)
5. [Text Loaders](#text-loaders)
    - [TXT Files](#txt-files)
    - [PDF Loaders](#pdf-loaders)
6. [Vector Databases](#vector-databases)
    - [ElephantVectors](#elephantvectors)
    - [ChromaDB](#chromadb)

## Installation

To get started with Elephant Chain, follow these simple steps:

1. **Installation:** Install the library using Composer:
   ```bash
   composer require
   ```

## Basic Usage

```php
use Rhaymison\ElephantChain\Llm\OpenAiChain;
use Rhaymison\ElephantChain\Chains\Chain;
use Rhaymison\ElephantChain\Prompts\PromptTemplate;

$openAi = new OpenAiChain('OPEN_AI_KEY', 'gpt-3.5-turbo');
$chain = new Chain($openAi);

$prompt = "Create a text about PHP";

$prompt = PromptTemplate::createPromptTemplate($prompt, []);
$response = $chain->run($prompt);
print($response);

$prompt = "Create a text about {theme}, especially about the model {model}";

$prompt = PromptTemplate::createPromptTemplate($prompt, ['theme' => 'AI', 'model' => 'gpt']);
$response = $chain->run($prompt);
print($response);
```

## Available Models
Elephant Chain currently supports the use of models from OpenAI and Gemini.
Integration with Mixtral and LlamaCPP is currently being implemented. Here are examples of how to initialize and use these models:

```PHP
use Rhaymison\ElephantChain\Llm\GeminiChain;
use Rhaymison\ElephantChain\Llm\OpenAiChain;

// OpenAI Model
$openAi = new OpenAiChain('OPEN_AI_KEY', 'MODEL');

// Gemini Model
$gemini = new GeminiChain('GEMINI_KEY');

```

## Chains

### Chain

The `Chain` class is the fundamental building block for creating and managing sequences of operations in Elephant Chain.

```PHP
use Rhaymison\ElephantChain\Chains\Chain;
use Rhaymison\ElephantChain\Llm\OpenAiChain;
$openAi = new OpenAiChain('OPEN_AI_KEY', 'gpt-3.5-turbo');
$chain = new Chain($openAi);

$prompt = "Create a text about PHP";

$prompt = PromptTemplate::createPromptTemplate($prompt, []);
$response = $chain->run($prompt);
print($response);

```

### RetrieverChain

The `RetrieverChain` class extends the functionality of `Chain` by incorporating mechanisms to retrieve relevant data based on provided prompts.

```PHP
use Rhaymison\ElephantChain\Chains\RetrieverChain;
use Rhaymison\ElephantChain\Llm\OpenAiChain;
use Rhaymison\ElephantChain\Databases\Chroma;
use Rhaymison\ElephantChain\Prompts\RetrieverPromptsTemplate;

$openAi = new OpenAiChain('OPEN_AI_KEY', 'gpt-3.5-turbo');
$chain = new RetrieverChain($openAi);
$retriever = $chroma->retriever($collection, [$question], 1);
$prompt = RetrieverPromptsTemplate::simpleRetrieverPromptTemplate($question);
$chain->dispatch($retriever->documents[0], $prompt);
print($response);
```

### SequentialChain

The `SequentialChain` class allows you to create a series of dependent operations, where the output of one operation serves as the input for the next.

```PHP
use Rhaymison\ElephantChain\Llm\OpenAiChain;
use Rhaymison\ElephantChain\Llm\GeminiChain;
use Rhaymison\ElephantChain\Chains\SequentialChain;
use Rhaymison\ElephantChain\Chains\Chain;

$openAi = new OpenAiChain('OPEN_AI_KEY', 'gpt-3.5-turbo');
$gemini = new GeminiChain('');

$chain1 = new Chain($openAi);
$chain2Callable = function () use ($chain1) {
    $text = "Write about Cristiano Ronaldo's life story";
    $prompt1 = PromptTemplate::createPromptTemplate($text, []);
    return $chain1->run($prompt2);
};

$chain2 = new Chain($openAi);
$chain2Callable = function ($input) use ($chain2) {
    $text = "Take the information given and create a poem on the topic. Information: {output}";
    $prompt2 = PromptTemplate::createPromptTemplate($text, ['output' => $input]);
    return $chain2->run($prompt2);
};

$chain3 = new Chain($gemini);
$chain3Callable = function ($input) use ($chain3) {
    $text = "Evaluate the past poem and say which period of history it fits into. Poem: {output}";
    $prompt2 = PromptTemplate::createPromptTemplate($text, ['output' => $input]);
    return $chain3->run($prompt2);
};

$sequentialChain = new SequentialChain();
$response = $sequentialChain->dispatchSequence([
    $chain1Callable,
    $chain2Callable,
    $chain3Callable
    // ...
]);

echo $response;
```
## Text Loaders

### TXT Files

The `TxtLoader` class allows you to load and process text files for use within Elephant Chain.

The first parameter is the directory, the second is the chunk size and the third is the overlapping window.
```PHP
use Rhaymison\ElephantChain\DocumentLoaders\TextLoaders;
$textLoader = new TextLoaders;
$documents = $textLoader->dirTextLoaders('./samples', 500, 20);
```

If you want to load only one txt file you can use this method and the last two parameters remain chunk and overlaping

```PHP
use Rhaymison\ElephantChain\DocumentLoaders\TextLoaders;
$textLoader = new TextLoaders;
$documents = $textLoader->singleTextFileLoader('./samples/cristiano_ronaldo.txt', 500, 20);
```


### PDF Loaders

The `PdfLoader` class enables you to load and extract text from PDF documents, making it easy to integrate document data into your workflows.

```PHP
use Rhaymison\ElephantChain\DocumentLoaders\PdfLoaders;
$pdfLoader = new PdfLoaders;
$documents = $pdfLoader->dirPdfLoader('./samples', 500, 20);
```

```PHP
use Rhaymison\ElephantChain\DocumentLoaders\PdfLoaders;
$pdfLoader = new PdfLoaders;
$documents = $pdfLoader->singlePdfLoader('./samples/inicial.pdf', 500, 20);
```

## Vector Databases

### ElephantVectors

The `ElephantVectors` class provides functionalities to store, manage, and query vectorized data, enabling efficient similarity searches and advanced data retrieval operations.
If you don't want to have a vector database, you can use ElephantVectors which provides document embeddings and allows you to perform the searches directly to the model.
```PHP
use Rhaymison\ElephantChain\Chains\RetrieverChain;
use Rhaymison\ElephantChain\DocumentLoaders\TextLoaders;
use Rhaymison\ElephantChain\Databases\ElephantVectors;
use Rhaymison\ElephantChain\Embeddings\GeminiEmbeddingsFunction;
use Rhaymison\ElephantChain\Llm\GeminiChain;
use Rhaymison\ElephantChain\Prompts\RetrieverPromptsTemplate;

$textLoader = new TextLoaders;
$documents = $textLoader->dirTextLoaders('./samples', 500, 20);

$embeddingFunction = new GeminiEmbeddingsFunction('');
$elephantVectors = new ElephantVectors($embeddingFunction);
$vectors = $elephantVectors->generateEmbeddingsChunks($documents);

$question = 'What happened on July 28, the galactic player';

#embedding, question and k search
$texts = $elephantVectors->retriever($vectors, $question, 4);

$gemini = new GeminiChain('');
$chain = new RetrieverChain($gemini);

$prompt = RetrieverPromptsTemplate::simpleRetrieverPromptTemplate($question);

$response = $chain->dispatch($texts, $prompt);
print($response);

```

### ChromaDB

The `ChromaDB` class offers robust capabilities for handling vectorized data, including storage, management, and querying, optimized for high-performance vector similarity searches.

```Bash
docker pull chromadb/chroma &&
docker run -p 6666:8000 chromadb/chroma
```

Usage example with OpenAI model

```PHP
use Rhaymison\ElephantChain\Databases\Chroma;
use Rhaymison\ElephantChain\Prompts\RetrieverPromptsTemplate;
use Rhaymison\ElephantChain\Chains\RetrieverChain;
use Rhaymison\ElephantChain\Llm\OpenAiChain;

$question = 'On May 21, against rivals Chelsea, Ronaldo scored the first goal in the 26th minute, what happened next?';
$chroma = new Chroma('http://localhost', 6666, 'cr7', 'cr7');
$embeddings = $chroma->openAIEmbeddingsFunction('OPEN_AI_KEY', 'text-embedding-3-small');
$collection = $chroma->getOrCreateCollection('cristiano', $embeddings);
$retriever = $chroma->retriever($collection, [$question], 1);
$openAi = new OpenAiChain('OPEN_AI_KEY', 'gpt-3.5-turbo');
$chain = new RetrieverChain($openAi);
$prompt = RetrieverPromptsTemplate::simpleRetrieverPromptTemplate($question);
$chain->dispatch($retriever->documents[0], $prompt);
print($response);
```

Usage example with Gemini and passing the embeddings function

```PHP
$question = 'On May 21, against rivals Chelsea, Ronaldo scored the first goal in the 26th minute, what happened next?';
$chroma = new Chroma('http://localhost', 6666, 'cr7', 'cr7');
$embeddings = $chroma->geminiEmbeddingsFunction('GEMINI_KEY');
$collection = $chroma->getOrCreateCollection('cristiano', $embeddings);
$retriever = $chroma->retriever($collection, [$question], 1);
$gemini = new GeminiChain('GEMINI_KEY');
$chain = new RetrieverChain($gemini);
$prompt = RetrieverPromptsTemplate::simpleRetrieverPromptTemplate($question);
$chain->dispatch($retriever->documents[0], $prompt);
print($response);
```