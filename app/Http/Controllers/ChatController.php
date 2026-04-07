<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AiAgents\EduHelperAgent;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    public function index()
    {
        return view('chat');
    }

    public function chat(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $message = $request->input('message');
        $sessionId = session()->getId();
        $history = session()->get('chat_history', []);
        $history[] = [
            'role' => 'user',
            'content' => $message
        ];
        $conversationText = '';

        foreach ($history as $chat) {
            $conversationText .= strtoupper($chat['role']) . ': ' . $chat['content'] . "\n";
        }

        $conversationText .= "USER: " . $message;

        $response = EduHelperAgent::for($sessionId)->respond($conversationText);
        $history[] = [
            'role' => 'assistant',
            'content' => $response
        ];

        $history = array_slice($history, -10);
        session(['chat_history' => $history]);

        return response()->json([
            'reply' => $response,
            'history' => $history
        ]);
    }
}
