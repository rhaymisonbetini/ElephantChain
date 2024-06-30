<?php
require_once __DIR__ . '/vendor/autoload.php';

use Rhaymison\ElephantChain\Chains\TabularChain;
use Rhaymison\ElephantChain\DocumentLoaders\TabularLoaders;
use Rhaymison\ElephantChain\Llm\GeminiChain;

$gemini = new GeminiChain('AIzaSyAkpjscFNpCN-MAwk40GC98ZlULiE3ufnw');
$tabular = new TabularLoaders();
$dataTabular = $tabular->csvLoader('./samples/samples.csv');

$chain = new TabularChain($gemini);

$question = "Pegue os 10 primeiros dados onde o code da industria e GH134,level è 4 e ano de 2016. Depois faça uma analise";
$response = $chain->dispatchTabularChain($dataTabular, $question);
print($response);