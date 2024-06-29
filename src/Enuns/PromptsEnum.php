<?php

namespace Rhaymison\ElephantChain\Enuns;

enum PromptsEnum: string
{
    case SYSTEM_SIMPLE_PROMPT_RETRIEVER = "You are a helpful assistant. Please answer the following question politely and informatively based on the provided text.";
    case SYSTEM_SIMPLE_PROMPT_SUMMARY_INTERAL_RETRIEVER = "As an expert in text analysis and summarization, please analyze the user's question based on the provided text. If relevant, provide a brief summary of the text.";
    case SYSTEM_USER_PROMPT_SUMMARY_INTERAL_RETRIEVER = "Below is the text that you should check if it makes sense with the user's response. If so, create a short summary with as much relevant information linked to the question as possible and return only the summary. Always create the summary in the native language provided in the context. If the context information is unrelated to the question, just return a '' without giving any explanation.";

}
