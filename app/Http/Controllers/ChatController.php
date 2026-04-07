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
        $response = EduHelperAgent::for($sessionId)->respond($message);
        return response()->json(['reply' => $response]);
    }
}
