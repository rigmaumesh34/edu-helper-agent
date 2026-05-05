<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;

Route::get('/', [ChatController::class, 'index']);
Route::post('/chat', [ChatController::class, 'chat']);
Route::get('/chat-history', function () {
    return response()->json([
        'history' => session('chat_history', [])
    ]);
});
Route::post('/clear-chat', function () {
    session()->forget('chat_history'); // remove history
    return response()->json(['message' => 'Chat cleared']);
});
