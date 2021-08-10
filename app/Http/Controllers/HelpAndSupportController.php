<?php

namespace App\Http\Controllers;

use App\HelpAndSupport;
use Illuminate\Http\Request;

class HelpAndSupportController extends Controller
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
        $data = new HelpAndSupport;
        $data->title = $request->title;
        $data->description = $request->description;
        $data->save();
        return response()->json(['status' => 200, 'message' => 'Thank you for your support. We will be back to you soon', 'data' => $data]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\HelpAndSupport  $helpAndSupport
     * @return \Illuminate\Http\Response
     */
    public function show(HelpAndSupport $helpAndSupport)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\HelpAndSupport  $helpAndSupport
     * @return \Illuminate\Http\Response
     */
    public function edit(HelpAndSupport $helpAndSupport)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\HelpAndSupport  $helpAndSupport
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, HelpAndSupport $helpAndSupport)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\HelpAndSupport  $helpAndSupport
     * @return \Illuminate\Http\Response
     */
    public function destroy(HelpAndSupport $helpAndSupport)
    {
        //
    }
}
