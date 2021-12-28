<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;

use Carbon\Carbon;

use App\Events\AnsweredEvent;

use App\Models\Room;
use App\Models\Message;
use App\Models\User;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $user_id;
    private $user_role;

    public function __construct()
    {
        $this->user_id = auth()->user() != null ? auth()->user()->id : null;
        $this->user_role = auth()->user() != null? auth()->user()->role : null;
    }



    public function index()
    {
        if($this->user_role == 'customer')
        {
            $rooms = Room::where('customer_id', $user_id)->with('messages')->get();
        }
        else
        {
            $rooms = Room::with('messages')->get();
        }
        
        return response()->json($rooms);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function reply(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'room_id'        => ['required','integer','exists:rooms,id'],
            'text'           => ['required','string','max:1500'],
        ]);
        
        
        if($validator->fails())
        {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        $new_message            = new Message();
        $new_message->text      = $request->text;
        $new_message->sender_id = $this->user_id;
        $new_message->room_id   = $request->room_id;
        $new_message->save();

        //update latest message date related to the question
        $room = Room::with('question')->findOrFail($request->room_id);
        $room->question->latest_reply_date = Carbon::now();
        $room->question->save();



        //if this reply was sent by employee then notify the customer
        if($this->user_role == 'employee')
        {
            $customer = User::find($room->customer_id);
            
            AnsweredEvent::dispatch($customer);
        }


        return response()->json(['message' => 'Message Sent']);
    }
}
