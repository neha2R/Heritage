<?php

namespace App\Http\Controllers;

use App\UserReport;
use Illuminate\Http\Request;

class UserReportController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\UserReport  $userReport
     * @return \Illuminate\Http\Response
     */
    public function show(UserReport $userReport)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\UserReport  $userReport
     * @return \Illuminate\Http\Response
     */
    public function edit(UserReport $userReport)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UserReport  $userReport
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserReport $userReport)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UserReport  $userReport
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserReport $userReport)
    {
        //
    }


    /**
     * Save a report by user in app.
     *
     * @param  \App\UserReport  $userReport
     * @return \Illuminate\Http\Response
     */
    public function userreport(Request $request)
    {
        

        $data = new UserReport;
        $data->user_id = $request->user_id;
        $data->type_id = $request->id;
        $data->type = $request->type;
        $data->title = $request->title;
        $data->description = $request->description;
        $data->save();
        
    return response()->json(['status' => 200, 'message' => 'Report saved succesfully', 'data' => $data]);
    }


}
