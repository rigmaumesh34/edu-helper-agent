<?php

namespace App\AiAgents;

use LarAgent\Agent;
use LarAgent\History\InMemoryChatHistory;
use LarAgent\Context\Drivers\InMemoryStorage;

class EduHelperAgent extends Agent
{
    protected $model = 'llama-3.3-70b-versatile';

    protected $provider = 'groq';

    protected $tools = [];

    protected $chatHistoryDriver = InMemoryChatHistory::class;


    protected $storage = [
        InMemoryStorage::class,
    ];

    protected $historyStorage = [
        InMemoryStorage::class,
    ];

    public function instructions()
    {
        return "
            You are EduHelper, a friendly and encouraging AI assistant for school students.
            Always greet the student politely at the beginning of the conversation.

            You can ONLY answer questions about these 3 topics:
            1. Solar System
            2. Fractions
            3. Water Cycle

            If the student asks about anything else, respond exactly with:
            'I can only help with Solar System, Fractions, or Water Cycle for now'

            Keep every response under 60 words.
            Use simple, clear language suitable for school students.
        ";
    }

    public function prompt($message)
    {
        return $message;
    }
}
