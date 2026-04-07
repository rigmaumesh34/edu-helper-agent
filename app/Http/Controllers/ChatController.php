<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AiAgents\EduHelperAgent;
use Illuminate\Support\Facades\Log;
use OpenAI\Exceptions\RateLimitException;
use Throwable;
class ChatController extends Controller
{
    public function index()
    {
        return view('chat');
    }

    public function chat(Request $request)
    {
        $message = $request->input('message');
        $sessionId = session()->getId();
        Log::info("Received message: $message from session: $sessionId");


        $maxAttempts = 3;
        $attempt = 0;
        $response = null;

        while ($attempt < $maxAttempts) {
            try {
                // Create agent with session ID so history is remembered
                $response = EduHelperAgent::for($sessionId)->respond($message);
                break;
            } catch (RateLimitException $e) {
                $attempt++;
                Log::warning("OpenAI rate limit (attempt {$attempt}/{$maxAttempts}): " . $e->getMessage());
                if ($attempt >= $maxAttempts) {
                    return response()->json(['error' => 'Rate limit exceeded. Please try again in a moment.'], 429);
                }
                // exponential backoff: 1s, 2s, 4s
                sleep((int) pow(2, $attempt - 1));
            } catch (Throwable $e) {
                Log::error('Chat error: ' . $e->getMessage());
                return response()->json(['error' => 'Internal error. Please try again later.'], 500);
            }
        }

        return response()->json(['reply' => $response]);
    }
}
