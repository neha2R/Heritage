<?php

namespace App\Http\Controllers;

use App\Dispute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DisputeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            'user_id' => 'required',
            'type' => 'required',
            'dispute' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'data' => '', 'message' => $validator->errors()]);
        }
        $data = new Dispute;
        $data->tournament_id = $request->tournament_id;
        $data->session_id = $request->session_id;
        $data->quiz_id = $request->quiz_id;
        $data->type = $request->type;
        $data->details = $request->dispute;
        // $data->status = '1';
        $data->save();
        return response()->json(['status' => 200, 'message' => 'Data found succesfully', 'data' => $data]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Dispute  $dispute
     * @return \Illuminate\Http\Response
     */
    public function show(Dispute $dispute)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Dispute  $dispute
     * @return \Illuminate\Http\Response
     */
    public function edit(Dispute $dispute)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Dispute  $dispute
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Dispute $dispute)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Dispute  $dispute
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dispute $dispute)
    {
        //
    }
}
