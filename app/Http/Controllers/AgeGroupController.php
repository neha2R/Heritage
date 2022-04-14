<?php

namespace App\Http\Controllers;

use App\AgeGroup;
use Illuminate\Http\Request;

class AgeGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ages = AgeGroup::OrderBy('id', 'DESC')->get();
        return view('age.list', compact('ages'));
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
        $validatedData = $request->validate([
            'name' => 'required|unique:age_groups,name,NULL,id,deleted_at,NULL',
            'from' => 'required|numeric|min:1|max:99',
            'to' => 'required|numeric|min:1|max:99',
        ]);

        $agegroup = AgeGroup::where('from', $request->from)->where('to', $request->to)->first();
        if ($agegroup) {
            return redirect()->back()->with(['error' => 'Age group already exists']);
        }
         
       
        $data = new AgeGroup;
        $data->name = strtolower($request->name);
        $data->from = $request->from;
        $data->to = $request->to;
        $data->status = '1';
        $data->save();

        if ($data->id) {
            return redirect('admin/agegroup')->with(['success' => 'Age Group saved Successfully', 'model' => 'model show']);
        } else {
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again Later']);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\AgeGroup  $ageGroup
     * @return \Illuminate\Http\Response
     */
    public function show($ageGroup)
    {
        $ageGroup = AgeGroup::find($ageGroup);
        if ($ageGroup->status == '1') {
            $ageGroup->status = '0';
        } else {
            $ageGroup->status = '1';

        }
        $ageGroup->save();

        if ($ageGroup->id) {
            return redirect()->back()->with(['success' => 'Status updated Successfully']);
        } else {
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again Later']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AgeGroup  $ageGroup
     * @return \Illuminate\Http\Response
     */
    public function edit(AgeGroup $ageGroup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AgeGroup  $ageGroup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $ageGroup)
    {
        $ageGroup = AgeGroup::find($ageGroup);
        $validatedData = $request->validate([
            'name' => 'required|unique:age_groups,name,' . $ageGroup->id,
            'from' => 'required|numeric|min:1|max:99',
            'to' => 'required|numeric|min:1|max:99',
        ]);
        $ageGroup->name = strtolower($request->name);
        $ageGroup->from = $request->from;
        $ageGroup->to = $request->to;
        $ageGroup->save();
        if ($ageGroup->id) {
            return redirect()->back()->with(['success' => 'Age Group Updated Successfully']);
        } else {
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again Later']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AgeGroup  $ageGroup
     * @return \Illuminate\Http\Response
     */
    public function destroy($ageGroup)
    {
        $ageGroup = AgeGroup::find($ageGroup);
        $ageGroup->delete();
        if ($ageGroup->id) {
            return redirect()->back()->with(['success' => 'Age Group Deleted Successfully']);
        } else {
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again Later']);
        }
    }
}
