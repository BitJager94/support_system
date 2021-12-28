<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;

use Carbon\Carbon;



use App\Models\User;
use App\Models\Room;
use App\Models\Question;


class QuestionController extends Controller
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
            $questions = Question::where('customer_id', $user_id)->with('room')->get();
        }
        else
        {
            $questions = Question::with('room')->get();
        }
        
        return response()->json($questions);
    }


    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'parameter'   => ['required','in:status,customer','max:1500'],
            'status'      => ['required_if:parameter,status', 'in:not-answered,in-progress-answered'],
            'name'        => ['required_if:parameter,name', 'string',],
        ]);
        
        
        if($validator->fails())
        {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        $questions = null;

        if($request->parameter == 'customer')
        {
            $ids = [];

            $customers = User::where('first_name', 'like', '%' . $request->name . '%')
            ->orWhere('first_name', 'like', '%' . $request->name . '%')
            ->get();
            
            foreach ($customers as $cust) 
            {
                array_push($ids, $cust->id);
            }
            
            $questions = Question::whereIn('customer_id', $ids)
            ->with('customer')
            ->get();
        }
        else
        {
            $questions = Question::where('status', $request->status)
            ->with('customer')
            ->get();
        }


        return response()->json($questions);

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
        $validator = Validator::make($request->all(), [
            'text'              => ['required','string','max:1500'],
        ]);
        
        
        if($validator->fails())
        {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        $new_question = new Question();
        $new_question->text = $request->text;
        $new_question->latest_reply_date = Carbon::now();
        $new_question->customer_id = $this->user_id;
        $new_question->save();


        //open a room after a question to chat about it
        $new_room                = new Room();
        $new_room->question_id   = $new_question->id;
        $new_room->customer_id   = $this->user_id;
        $new_room->save();


        return response()->json(['message' => 'Question Created Successfully']);

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


    public function mark_as_spam($id)
    {
        try 
        {
            $question = Question::findOrFail($id);
            $question->spam = true;
            $question->save();

            return response()->json(['message' => 'Question Marked As Spam']);
        } 
        catch (\Throwable $th) 
        {
            return response()->json(['error' => ['entity' => [$th->getMessage()]]], 404);
        }
    }
}
