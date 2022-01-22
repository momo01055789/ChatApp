<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Events\ChatEvent;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function chat()
    {
        return view('Chat');
    }

    public function send(Request $request)
    {
        $user = User::find(Auth::id());
        $this->save($request);
        broadcast(new ChatEvent($request->message,$user))->toOthers();
    }

    public function save(Request $request)
    {
        session()->put('chat',$request->chat);
    }

    public function getOldMessages()
    {
        return session('chat');
    }

    public function delete()
    {
        session()->forget('chat');
    }
}
