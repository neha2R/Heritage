<?php

namespace App\Http\Controllers;

use App\FeedContent;
use Illuminate\Http\Request;
use App\Theme;
use App\Domain;
use App\Feed;
use App\FeedMedia;
use App\FeedAttachment;

class FeedContentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $themes = Theme::OrderBy('id', 'DESC')->get();
        $feedContents = FeedContent::OrderBy('id', 'DESC')->get();$domains = Domain::OrderBy('id', 'DESC')->get();
        $feeds = Feed::OrderBy('id', 'ASC')->get();
        return view('feed-content.list', compact('feedContents','themes','feeds','domains'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    //  dd($request->media_name);
        $validatedData = $request->validate([
            'theme_id' => 'required',
            'domain_id' => 'required|',
            'feed_id' => 'required',
            'title' => 'required',
        ]);


        $data = new FeedContent;
        $data->theme_id = $request->theme_id;
        $data->domain_id = $request->domain_id;
        $data->feed_id = $request->feed_id;
        $data->tags=$request->tags;
        // $data->type = '1';
        $data->save();

        $imagemimes = ['image/png', 'image/jpg', 'image/jpeg', 'image_gif']; //Add more mimes that you want to support
        $videomimes = ['video/mp4']; //Add more mimes that you want to support
        $media = new FeedMedia;
        $media->feed_content_id = $data->id;
        $media->title = $request->title;
        $data->description=$request->description;
        $media->external_link=$request->external_link;
        $media->video_link=$request->video_link;
        $media->save();

        if($request->hasfile('media_name'))
        {
           foreach($request->file('media_name') as $key=>$file)
           {
             
            if (in_array($file->getMimeType(), $imagemimes)) {
                $type = '0';
            }

            //validate audio
            if (in_array($file->getMimeType(), $videomimes)) {
                $type = '1';
            }
            $name = $file->store('feed','public');

            $attachment = new FeedAttachment;
             $attachment->feed_media_id = $media->id;
             $attachment->media_name = $name;
             $attachment->media_type = $type;
             $attachment->save();
            }
        }

       

        if ($data->id) {
            return redirect('admin/feed-content')->with(['success' => 'Feed saved Successfully']);
        } else {
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again Later']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\FeedContent  $feedContent
     * @return \Illuminate\Http\Response
     */
    public function show(FeedContent $feedContent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\FeedContent  $feedContent
     * @return \Illuminate\Http\Response
     */
    public function edit(FeedContent $feedContent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\FeedContent  $feedContent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FeedContent $feedContent)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FeedContent  $feedContent
     * @return \Illuminate\Http\Response
     */
    public function destroy(FeedContent $feedContent)
    {
        //
    }
}
