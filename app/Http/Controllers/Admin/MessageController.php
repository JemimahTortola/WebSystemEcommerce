<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        $conversations = Conversation::with(['user', 'lastMessage'])
            ->where('admin_id', Auth::guard('admin')->id())
            ->orWhere('user_id', Auth::guard('admin')->id())
            ->latest('last_message_at')
            ->get();

        return view('admin.messages.index', compact('conversations'));
    }

    public function show($id)
    {
        $conversation = Conversation::with(['user', 'admin', 'messages.sender'])->findOrFail($id);
        
        Message::where('conversation_id', $id)
            ->where('sender_id', '!=', Auth::guard('admin')->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('admin.messages.show', compact('conversation'));
    }

    public function startConversation(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'content' => 'required|string',
            'subject' => 'nullable|string|max:255',
        ]);

        $user = User::findOrFail($request->user_id);
        $admin = Auth::guard('admin')->user();

        $conversation = Conversation::create([
            'user_id' => $user->id,
            'admin_id' => $admin->id,
            'subject' => $request->subject ?? 'Message from Admin',
            'reference_type' => 'review',
            'reference_id' => $request->review_id,
            'last_message_at' => now(),
        ]);

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $admin->id,
            'content' => $request->content,
        ]);

        return redirect()->route('admin.messages.show', $conversation->id)->with('success', 'Message sent successfully');
    }

    public function sendMessage(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $conversation = Conversation::findOrFail($id);

        Message::create([
            'conversation_id' => $id,
            'sender_id' => Auth::guard('admin')->id(),
            'content' => $request->content,
        ]);

        $conversation->update(['last_message_at' => now()]);

        return redirect()->back();
    }
}
