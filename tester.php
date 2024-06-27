<?php
require_once __DIR__ . '/vendor/autoload.php';

use Rhaymison\ElephantChain\Databases\Chroma;
use Rhaymison\ElephantChain\DocumentLoaders\TextLoaders;

$chroma = new Chroma('http://localhost', 6666, 'cbmes', 'cbmes');
$embeddings = $chroma->openAIEmbeddingsFunction('', 'text-embedding-3-small');
$collection = $chroma->getOrCreateCollection('cristiano', $embeddings);
$retriever = $chroma->retriever($collection, ['O que aconteceu no dia 28 de julho, o jogador galáctico'], 5);
print(json_encode($retriever));


//LOADERS TEXT
//$textLoader = new TextLoaders;
//$documents = $textLoader->textLoaders('./samples', 500, 20);
//$chroma->addVectors($collection, $documents[0], $documents[1], $documents[2]);