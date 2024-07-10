<?php

namespace Rhaymison\ElephantChain\Enuns;

enum PromptsEnum: string
{
    case SYSTEM_SIMPLE_PROMPT_RETRIEVER = "You are an expert in Retrieval-Augmented Generation (RAG). Your task is to answer the user's question based on the provided context.";
    case SYSTEM_SIMPLE_PROMPT_SUMMARY_INTERAL_RETRIEVER = "As an expert in text analysis and summarization, please analyze the user's question based on the provided text. If relevant, provide a brief summary of the text.";
    case SYSTEM_USER_PROMPT_SUMMARY_INTERAL_RETRIEVER = "Below is the text that you should check if it makes sense with the user's response. If so, create a short summary with as much relevant information linked to the question as possible and return only the summary. Always create the summary in the native language provided in the context. If the context information is unrelated to the question, just return a '' without giving any explanation.";
    case SYSTEM_TABULAR_FUN_CREATE = " You are a PHP expert. You are receiving a sample of data from a CSV/XLSX file. Based on this data, create a PHP function that performs exactly what the user wants. The function should be able to filter the data based on specific criteria provided by the user. Return only the function as a string, and nothing else, so that it can be executed in an `eval()`.
        The parameter of the funcion must the name \$datas\. Here is the sample data structure:
        [
            {
                \"Year\": \"<year>\",
                \"Industry_aggregation_NZSIOC\": \"<aggregation level>\",
                \"Industry_code_NZSIOC\": \"<industry code>\",
                \"Industry_name_NZSIOC\": \"<industry name>\",
                \"Units\": \"<units>\",
                \"Variable_code\": \"<variable code>\",
                \"Variable_name\": \"<variable name>\",
                \"Variable_category\": \"<variable category>\",
                \"Value\": \"<value>\",
                \"Industry_code_ANZSIC06\": \"<ANZSIC code>\"
            }
        ]
        Please write the necessary PHP function to filter this data based on a specific criterion (for example, return only rows where \"Year\" is \"2016\"). The function should be named `filterData` and should accept an array of data as an argument. Return only the function as a string.
        ";

    case WIKIPEDIA_PROMPT_THEME = "You are an expert in identifying research topics. Given the user's question, respond with a 3  keywords expressly linked to the topic. Answer only this list of words in the language of the question. Do not provide any explanation or additional text.";
}
