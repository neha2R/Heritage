<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Faq;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $faq = Faq::OrderBy('id', 'DESC')->get();
        return view('faqs.list', compact('faq'));
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
            'title' => 'required|unique:faqs,title,NULL,id,deleted_at,NULL',
            'content' => 'required|max:255',
            
        ]);

        $data = new Faq;
        $data->title = $request->title;
        $data->content = $request->content;
        $data->save();

        if ($data->id) {
            return redirect()->back()->with(['success' => 'Faq saved Successfully', 'model' => 'model show']);
        } else {
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again Later']);
        }

    }

    
    public function show(Faq $faq)
    {
        if ($faq->status == '1') {
            $faq->status = '0';
        } else {
            $faq->status = '1';

        }
        $faq->save();

        if ($faq->id) {
            return redirect()->back()->with(['success' => 'Status updated Successfully']);
        } else {
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again Later']);
        }
    }

    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $faq)
    {
        $data = Faq::find($faq);

        $validatedData = $request->validate([
            'title' => 'required|unique:faqs,title,'.$data->id,
            'content' => 'required|max:255',
           
        ]);
        $data->title = $request->title;
        $data->content = $request->content;
        $data->save();

        if ($data->id) {
            return redirect()->back()->with(['success' => 'FAQs updated Successfully']);
        } else {
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again Later']);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function destroy($faq)
    {
        $faq = Faq::find($faq);
        $faq->delete();
        if ($faq->id) {
            return redirect()->back()->with(['success' => 'FAQs Deleted Successfully']);
        } else {
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again Later']);
        }
    }
}
