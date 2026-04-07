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

        // ✅ STEP 1: Get existing session history
        $history = session()->get('chat_history', []);

        // ✅ STEP 2: Store user message
        $history[] = [
            'role' => 'user',
            'content' => $message
        ];

        // ✅ STEP 3: Send full history to agent
        $conversationText = '';

        foreach ($history as $chat) {
            $conversationText .= strtoupper($chat['role']) . ': ' . $chat['content'] . "\n";
        }

        $conversationText .= "USER: " . $message;

        // Send full conversation instead of just message
        $response = EduHelperAgent::for($sessionId)->respond($conversationText);
        // ✅ STEP 4: Store bot response
        $history[] = [
            'role' => 'assistant',
            'content' => $response
        ];

        // ✅ STEP 5: Limit history (optional but recommended)
        $history = array_slice($history, -10);

        // ✅ STEP 6: Save back to session
        session(['chat_history' => $history]);

        return response()->json([
            'reply' => $response,
            'history' => $history // optional (for debugging/UI)
        ]);
    }
}
