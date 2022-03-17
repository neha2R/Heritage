<?php

namespace App\Http\Controllers;

use App\Experince;
use Illuminate\Http\Request;
use App\ExperinceImage;

class ExperinceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $experinces = Experince::all();
        return view('experince.list', compact('experinces'));
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
        $validator = $request->validate([
            'name' => 'required',
            'price' => 'required',
            'link' => 'required',
            'description' => 'required|max:200',
            'images' => 'required',
            'images.*' => 'image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        $exp = new Experince;
        $exp->name = $request->name;
        $exp->price = $request->price;
        $exp->link = $request->link;
        $exp->description = $request->description;
        $exp->save();

        if ($request->hasfile('images')) {
            foreach ($request->file('images') as $key => $file) {

             
                $name = $file->store('exp', 'public');
                $image = new ExperinceImage;
                $image->experinces_id = $exp->id;
                $image->image = $name;
                $image->save();
            }
        }

        return redirect()->back()->with('success', "You have created experince successfully.");

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Experince  $experince
     * @return \Illuminate\Http\Response
     */
    public function show( $id)
    {
      
        $exp = Experince::whereId($id)->first();
        if ($exp->status == '1') {
            $exp->status = '0';
        } else {
            $exp->status = '1';
        }
        $exp->save();

        if ($exp->id) {
            return redirect()->back()->with(['success' => 'Status updated Successfully']);
        } else {
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again Later']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Experince  $experince
     * @return \Illuminate\Http\Response
     */
    public function edit(Experince $experince)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Experince  $experince
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        $validator = $request->validate([
            'name' => 'required',
            'price' => 'required',
            'link' => 'required',
            'description' => 'required|max:200',
            'images.*' => 'image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        $exp = Experince::whereId($id)->first();
        $exp->name = $request->name;
        $exp->price = $request->price;
        $exp->link = $request->link;
        $exp->description = $request->description;
        $exp->save();

        if (isset($request->old_images) && count($request->old_images) > 0) {
            if (ExperinceImage::where('experinces_id', $id)->first()) {
                ExperinceImage::where('experinces_id', $id)->delete();
            }
            foreach ($request->old_images as $key => $file) {
                $name = $file;
                $image = new ExperinceImage;
                $image->experinces_id = $exp->id;
                $image->image = $name;
                $image->save();
            }
        } else {
            if (ExperinceImage::where('experinces_id', $id)->first()) {
                ExperinceImage::where('experinces_id', $id)->delete();
            }
        }

        if ($request->hasfile('images')) {
            foreach ($request->file('images') as $key => $file) {
                $type = '0';
                $name = $file->store('exp', 'public');
                $image = new ExperinceImage;
                $image->experinces_id = $exp->id;
                $image->image = $name;
                $image->save();
            }
        }

        return redirect()->back()->with('success', "You have updated experince successfully.");

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Experince  $experince
     * @return \Illuminate\Http\Response
     */
    public function destroy(Experince $experince)
    {
        $exp = Experince::find($experince->id);

        if ($exp) {
            $exp->delete();
        }

        if ($exp->id) {
            return redirect()->back()->with(['success' => 'Experince Deleted Successfully']);
        } else {
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again Later']);
        }
    }


// Api functions start here

    public function exp(Request $req)
    {

        if ($req->search != "") {
            $str = $req->search;
            $exps = Experince::where('name', 'like', '%' . $str . '%')->get();
        } else {

            $exps = Experince::where('status', '1')->get();
        }


        $data = [];
        if (!empty($exps)) {
            foreach ($exps as $exp) {
                $expData['name'] = ucwords(strtolower($exp->name));
                $expData['price'] = "₹ " . $exp->price;
                $expData['description'] = ucwords(strtolower($exp->description));
                $expData['link'] = ucwords(strtolower($exp->link));

                $i = 1;

                if (count($exp->images) > 0) {
                    $expData['images'] = $this->get_files($exp->images);
                } else {
                    $expData['images'] = [];
                }
                $data[] = $expData;
            }
        } else {
            return response()->json(['status' => 200, 'message' => 'No experince found.', 'data' => array()]);
        }

        if (empty($data)) {
            return response()->json(['status' => 200, 'message' => 'No experince found.', 'data' => array()]);
        }
        return response()->json(['status' => 200, 'message' => 'Experince Found', 'data' => $data]);
    }
    public function get_files($files)
    {
        $productImages = [];
        foreach ($files as $image) {
            $productImages[] = url('/storage') . '/' . $image->image;
        }
        return $productImages;
    }
}
