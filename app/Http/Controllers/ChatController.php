<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', Auth::id())->orderBy('name')->get();

        return view('chat.index', compact('users'));
    }

    public function getMessages(User $user): JsonResponse
    {
        $messages = Message::where(function ($q) use ($user) {
            $q->where('sender_id', Auth::id())->where('receiver_id', $user->id);
        })->orWhere(function ($q) use ($user) {
            $q->where('sender_id', $user->id)->where('receiver_id', Auth::id());
        })->with('sender')->orderBy('created_at')->get();

        return response()->json(
            $messages->map(fn($m) => [
                'id'          => $m->id,
                'body'        => $m->body,
                'sender_id'   => $m->sender_id,
                'sender_name' => $m->sender->name,
                'created_at'  => $m->created_at->format('g:i A'),
                'mine'        => $m->sender_id === Auth::id(),
            ])
        );
    }

    public function send(Request $request, User $user): JsonResponse
    {
        $request->validate(['body' => 'required|string|max:1000']);

        $message = Message::create([
            'sender_id'   => Auth::id(),
            'receiver_id' => $user->id,
            'body'        => $request->body,
        ]);

        $message->load('sender');

        broadcast(new MessageSent($message))->toOthers();

        return response()->json([
            'id'          => $message->id,
            'body'        => $message->body,
            'sender_id'   => $message->sender_id,
            'sender_name' => $message->sender->name,
            'created_at'  => $message->created_at->format('g:i A'),
            'mine'        => true,
        ]);
    }
}
