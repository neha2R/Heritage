<?php

namespace App\Http\Controllers;

use App\FeedContent;
use Illuminate\Http\Request;
use App\Theme;
use App\Domain;
use App\Subdomain;
use App\Feed;
use App\FeedMedia;
use App\FeedAttachment;
use App\FeedCollection;
use Illuminate\Support\Facades\Validator;
use App\SaveFeed;
use App\Jobs\FeedMediaUploadJob;
use App\Traits\NotificationToUser;

class FeedContentController extends Controller
{
    use NotificationToUser;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $feedContents =  FeedContent::query();
        if ($request->search) {
            $searchTerm = $request->search;
            $feedContents =  $feedContents->whereHas('theme', function ($query) use ($searchTerm) {
                return $query->where('title', 'LIKE', '%' . $searchTerm . '%');
            })->orWhereHas('feedtype', function ($query) use ($searchTerm) {
                return $query->where('title', 'LIKE', '%' . $searchTerm . '%');
            })
                ->orWhere('title', 'LIKE', "%{$searchTerm}%");
        }
        $themes = Theme::OrderBy('id', 'DESC')->get();
        $feedContents = $feedContents->OrderBy('id', 'DESC')->paginate(10);
        $domains = Domain::OrderBy('id', 'DESC')->get();
        $feeds = Feed::OrderBy('id', 'ASC')->get();
        return view('feed-content.list', compact('feedContents', 'themes', 'feeds', 'domains'));
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

        if ($request->feed_id == '1') {
            $validatedData = $request->validate([
                'theme_id' => 'required',
                'domain_id' => 'required',
                'feed_id' => 'required',
                'title' => 'required|max:200',
                'tags' => 'required',
                'external_link' => 'required',
                'title' => 'required',
                'description' => 'required'
            ]);

            $data = new FeedContent;
            $data->theme_id = $request->theme_id;
            $data->domain_id = $request->domain_id;
            $data->feed_id = $request->feed_id;
            $data->tags = $request->tags;

            $data->title = $request->title;
            $data->description = $request->description;
            $data->save();
            $imagemimes = ['image/png', 'image/jpg', 'image/jpeg', 'image_gif']; //Add more mimes that you want to support
            $videomimes = ['video/mp4']; //Add more mimes that you want to support
            $media = new FeedMedia;
            $media->feed_content_id = $data->id;
            $media->title = $request->title;
            $media->description = $request->description;
            $media->external_link = $request->external_link;

            if ($request->hasfile('media_name')) {



                $media->video_link = isset($request->video_link) ? $request->video_link : '';
                $media->save();

                foreach ($request->file('media_name') as $key => $file) {

                    $type = '0';
                    // FeedMediaUploadJob::dispatchNow($file,$media->id,$type);
                    $name = $file->store('feed', 'public');
                    $attachment = new FeedAttachment;
                    $attachment->feed_media_id = $media->id;
                    $attachment->media_name = $name;
                    $attachment->media_type = $type;
                    // dd($attachment);
                    $attachment->save();
                }
            } else {
                if ($request->has('media_video')) {

                    if ($request->media_video->getClientOriginalName() != null) {
                        if (in_array($request->media_video->getMimeType(), $videomimes)) {
                            $type = '1';

                            $v_name = $request->media_video->store('feed', 'public');
                            $place_holder = $request->placeholder_image->store('feed', 'public');

                            // $media = new FeedMedia;
                            // $media->feed_content_id = $data->id;
                            // $media->title = $request->title;
                            // $media->description=$request->description;
                            //$media->external_link=$request->external_link;
                            $media->video_link = isset($request->video_link) ? $request->video_link : '';
                            $media->placholder_image = $place_holder;
                            $media->save();

                            $attachment = new FeedAttachment;
                            $attachment->feed_media_id = $media->id;
                            $attachment->media_name = $v_name;
                            $attachment->media_type = $type;
                            $attachment->save();
                        }
                    }
                }
            }
        } elseif ($request->feed_id == '2') {

            $validatedData = $request->validate([

                'title.*' => 'required',
                'description.*' => 'required',
                // 'media_video'=>'required',
                'placeholder_image.*' => 'required'
            ]);

            $data = new FeedContent;
            $data->theme_id = $request->theme_id;
            $data->domain_id = $request->domain_id;
            $data->feed_id = $request->feed_id;
            $data->tags = $request->tags;
            $data->title = $request->title;
            $data->description = $request->description;
            $data->save();



            if (isset($request->card)) {
                foreach ($request->card as $key => $value) {

                    // $file = $value['media_video'][$key];

                    // dd($value['media_video'][$key]);
                    // dd($value['media_video'][0]->store('feed','public')); 
                    $imagemimes = ['image/png', 'image/jpg', 'image/jpeg', 'image_gif']; //Add more mimes that you want to support
                    $videomimes = ['video/mp4']; //Add more mimes that you want to support

                    $media = new FeedMedia;
                    $media->feed_content_id = $data->id;
                    // array
                    $media->title = $value['title'];

                    $media->description = $value['description'];

                    $media->external_link = $value['external_link'];

                    $media->video_link = $value['video_link'];

                    $media->save();



                    foreach ($value['media_video'] as $files) {

                        if ($files->getClientOriginalName() != null) {
                            if (in_array($files->getMimeType(), $imagemimes)) {
                                $type = '0';
                            }
                            //validate audio
                            if (in_array($files->getMimeType(), $videomimes)) {
                                $type = '1';
                                $place = $value['placeholder_image']->store('feed', 'public');
                                $feedplace = FeedMedia::find($media->id);
                                $feedplace->placholder_image =  $place;
                                $feedplace->save();
                            }

                            $name = $files->store('feed', 'public');
                            // FeedMediaUploadJob::dispatch($files,$media->id,$type)->delay(Carbon::now()->addMinutes(1));
                            $attachment = new FeedAttachment;
                            $attachment->feed_media_id = $media->id;
                            $attachment->media_name = $name;
                            $attachment->media_type = $type;
                            $attachment->save();
                        }
                    }
                }
            }


            //    if($request->hasfile('media_name'))
            //    {
            //          foreach($request->file('media_name') as $key=>$file)
            //          {

            //              //     if (in_array($file->getMimeType(), $imagemimes)) {
            //              //     $type = '0';
            //              // }
            //              // //validate audio
            //              // if (in_array($file->getMimeType(), $videomimes)) {
            //              //     $type = '1';
            //              // }
            //              $type = '1';
            //              $name = $file->store('feed','public');

            //              $attachment = new FeedAttachment;
            //              $attachment->feed_media_id = $media->id;
            //              $attachment->media_name = $name;
            //              $attachment->media_type = $type;
            //              $attachment->save();
            //          }
            //      }

        } else {
            $validatedData = $request->validate([

                'title.*' => 'required',
                'description.*' => 'required',
                // 'media_video'=>'required',
                'placeholder_image.*' => 'required'
            ]);

            $data = new FeedContent;
            $data->theme_id = $request->theme_id;
            $data->domain_id = $request->domain_id;
            $data->feed_id = $request->feed_id;
            $data->tags = $request->tags;
            $data->title = $request->title;
            $data->description = $request->description;
            $data->save();


            foreach ($request->card as $key => $value) {

                // $file = $value['media_video'][$key];


                // dd($value['media_video'][0]->store('feed','public')); 
                $imagemimes = ['image/png', 'image/jpg', 'image/jpeg', 'image_gif']; //Add more mimes that you want to support
                $videomimes = ['video/mp4']; //Add more mimes that you want to support

                $media = new FeedMedia;
                $media->feed_content_id = $data->id;
                // array
                $media->title = $value['title1'];

                $media->description = $value['description1'];

                $media->external_link = $value['external_link1'];

                $media->video_link = $value['video_link1'];

                $media->save();



                foreach ($value['media_video1'] as $files) {

                    if ($files->getClientOriginalName() != null) {
                        if (in_array($files->getMimeType(), $imagemimes)) {
                            $type = '0';
                        }
                        //validate audio
                        if (in_array($files->getMimeType(), $videomimes)) {
                            $type = '1';
                            $place = $value['placeholder_image1']->store('feed', 'public');
                            $feedplace = FeedMedia::find($media->id);
                            $feedplace->placholder_image =  $place;
                            $feedplace->save();
                        }

                        $name = $files->store('feed', 'public');
                        // FeedMediaUploadJob::dispatch($files,$media->id,$type)->delay(Carbon::now()->addMinutes(1));
                        $attachment = new FeedAttachment;
                        $attachment->feed_media_id = $media->id;
                        $attachment->media_name = $name;
                        $attachment->media_type = $type;
                        $attachment->save();
                    }
                }
            }
        }
        // send new post notification
        $this->NewPost();

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
    public function show($id)
    {
        $feedContent = FeedContent::whereId($id)->first();
        if ($feedContent->status == '1') {
            $feedContent->status = '0';
        } else {
            $feedContent->status = '1';
        }
        $feedContent->save();

        if ($feedContent->id) {
            return redirect()->back()->with(['success' => 'Status updated Successfully']);
        } else {
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again Later']);
        }
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
    public function update(Request $request, $id)
    {

        if ($request->feed_id == "1") {


            $validatedData = $request->validate([
                'theme_id' => 'required',
                'domain_id' => 'required|',
                'feed_id' => 'required',
                'title' => 'required|max:200',
                'tags' => 'required',
                'external_link' => 'required',
                'title' => 'required',
                'description' => 'required'
            ]);

            $data = FeedContent::whereId($id)->first();
            $data->theme_id = $request->theme_id;
            $data->domain_id = $request->domain_id;
            $data->feed_id = $request->feed_id;
            $data->tags = $request->tags;

            $data->title = $request->title;
            $data->description = $request->description;
            $data->save();
            $imagemimes = ['image/png', 'image/jpg', 'image/jpeg', 'image_gif']; //Add more mimes that you want to support
            $videomimes = ['video/mp4']; //Add more mimes that you want to support


            if ($request->media_type == '0') {


                $media = FeedMedia::where('feed_content_id', $data->id)->first();
                $media->feed_content_id = $data->id;
                $media->title = $request->title;
                $media->description = $request->description;
                $media->external_link = $request->external_link;
                $media->video_link = isset($request->video_link) ? $request->video_link : '';
                $media->save();


                if (isset($request->old_images) && count($request->old_images) > 0) {

                    if (FeedAttachment::where('feed_media_id', $media->id)->first()) {
                        FeedAttachment::where('feed_media_id', $media->id)->delete();
                    }
                    if (isset($request->media_name) && count($request->media_name) > 0) {
                        foreach ($request->media_name as $files) {
                            if ($files->getClientOriginalName() != null) {
                                if (in_array($files->getMimeType(), $imagemimes)) {
                                    $type = '0';
                                }



                                $name = $files->store('feed', 'public');
                                $attachment = new FeedAttachment;
                                $attachment->feed_media_id = $media->id;
                                $attachment->media_name = $name;
                                $attachment->media_type = $type;
                                $attachment->save();
                            }
                        }
                    }



                    foreach ($request->old_images as $files) {


                        $attachment = new FeedAttachment;
                        $attachment->feed_media_id = $media->id;
                        $attachment->media_name = $files;
                        $attachment->media_type = '0';
                        $attachment->save();
                    }

                    return redirect()->back()->with(['success' => 'Feed Updated Successfully']);
                } else {

                    $media = FeedMedia::where('feed_content_id', $data->id)->first();
                    $media->feed_content_id = $data->id;
                    $media->title = $request->title;
                    $media->description = $request->description;
                    $media->external_link = $request->external_link;
                    $media->video_link = isset($request->video_link) ? $request->video_link : '';
                    $media->save();

                    if (FeedAttachment::where('feed_media_id', $media->id)->first()) {
                        FeedAttachment::where('feed_media_id', $media->id)->delete();
                    }
                    foreach ($request->media_name as $files) {
                        if ($files->getClientOriginalName() != null) {
                            if (in_array($files->getMimeType(), $imagemimes)) {
                                $type = '0';
                            }


                            $name = $files->store('feed', 'public');
                            $attachment = new FeedAttachment;
                            $attachment->feed_media_id = $media->id;
                            $attachment->media_name = $name;
                            $attachment->media_type = $type;
                            $attachment->save();
                        }
                    }
                }
            } else {

                if (FeedAttachment::where('feed_media_id', $id)->first()) {
                    FeedAttachment::where('feed_media_id', $id)->delete();
                }

                if ($request->media_video->getClientOriginalName() != null) {
                    if (in_array($request->media_video->getMimeType(), $videomimes)) {


                        $type = '1';
                        $v_name = $request->media_video->store('feed', 'public');
                        $place_holder = $request->placeholder_image->store('feed', 'public');

                        $media = FeedMedia::where('feed_content_id', $data->id)->first();
                        $media->title = $request->title;
                        $media->description = $request->description;
                        $media->external_link = $request->external_link;
                        $media->video_link = isset($request->video_link) ? $request->video_link : '';
                        $media->placholder_image = $place_holder;
                        $media->save();

                        $attachment = new FeedAttachment;
                        $attachment->feed_media_id = $media->id;
                        $attachment->media_name = $v_name;
                        $attachment->media_type = $type;
                        $attachment->save();
                    }
                }
                return redirect()->back()->with(['success' => 'Feed Updated Successfully']);
            }
        } else {

            $data = FeedContent::where('id', $id)->first();
            $data->theme_id = $request->theme_id;
            $data->domain_id = $request->domain_id;
            $data->feed_id = $request->feed_id;
            $data->tags = $request->tags;
            $data->title = $request->title;
            $data->description = $request->description;
            $data->save();

            if (isset($request->more_title) && count($request->more_title) > 0) {


                foreach ($request->more_title as $key => $one) {
                    if (FeedMedia::where('feed_content_id', $id)->first()) {
                        FeedMedia::where('feed_content_id', $id)->delete();
                    }

                    $media = new FeedMedia;
                    $media->feed_content_id = $id;
                    $media->title = $one;
                    $media->description = $request->more_description[$key];
                    $media->external_link = $request->more_external_link[$key];
                    $media->save();

                    if ($request->more_type[$key] == '0') {

                        if (isset($request->$request->more_media_name[$key]) && count($request->more_media_name[$key]) > 0) {
                            foreach ($request->media_name[$key] as $image) {
                                $name = $image->store('feed', 'public');
                                $media = new FeedAttachment;
                                $media->feed_media_id = $media->id;
                                $media->media_type = $type;
                                $media->media_name = $name;
                                $media->save();
                            }
                        }
                    } else {
                        //video
                    }
                }
                return redirect()->back()->with(['success' => 'Feed Updated Successfully']);
            } else {
                foreach ($request->title as $key => $one) {
                    foreach ($request->card as $key => $one) {
                        if (FeedMedia::where('feed_content_id', $id)->first()) {
                            FeedMedia::where('feed_content_id', $id)->delete();
                        }

                        $media = new FeedMedia;
                        $media->feed_content_id = $id;
                        $media->title = $one;
                        $media->description = $request->description[$key];
                        $media->external_link = $request->external_link[$key];
                        $media->save();

                        if ($request->media_type[$key] == '0') {
                            return $request;
                            if (count($request->more_media_image)) {
                                foreach ($request->more_media_image[$key] as $image) {
                                    $name = $files->store('feed', 'public');
                                    $media = new FeedAttachment;
                                    $media->feed_media_id = $media->id;
                                    $media->media_type = $type;
                                    $media->media_name = $name;
                                    $media->save();
                                }
                            }
                        } else {
                            //video
                        }

                        return redirect()->back()->with(['success' => 'Feed Updated Successfully']);
                    }
                }
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FeedContent  $feedContent
     * @return \Illuminate\Http\Response
     */
    public function destroy(FeedContent $feedContent)
    {
        $feedContent = FeedContent::find($feedContent->id);

        if ($feedContent) {
            $feedContent->delete();
            // if($feedContent->feed_id!='1')
            // {
            //     $feedContent->feed_medium()->delete();
            // }
            // else
            // {
            //     $feedContent->feed_media()->delete();
            // }

        }

        if ($feedContent->id) {
            return redirect()->back()->with(['success' => 'Feed content Deleted Successfully']);
        } else {
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again Later']);
        }
    }


    public function feed(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'theme_id' => 'required',
            // 'domain_id' => 'required',
            // 'feed_type_id' => 'required',
            'feed_page_id' => 'required',
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'data' => '', 'message' => $validator->errors()]);
        }


        $feedContents = FeedContent::select('id', 'feed_id', 'type', 'tags', 'title', 'description');

        // $savefeeds = SaveFeed::where('user_id',$request->user_id)->pluck('feed_contents_id');

        if ($request->theme_id) {

            $id = explode(',', $request->theme_id);
            $feedContents = $feedContents->whereIn('theme_id', $id);
        }

        if ($request->feed_type_id) {

            $feed_id = explode(',', $request->feed_type_id);
            $feedContents = $feedContents->whereIn('feed_id', $feed_id);
        }
        if ($request->domain_id) {

            $domain_id = explode(',', $request->domain_id);
            $feedContents = $feedContents->whereIn('domain_id', $domain_id);
        }
         $feedContents = $feedContents->where('status', '1');

        // $feedContents2 = FeedContent::select('id','type','tags','title','description')->with('feedtype')->whereIn('feed_id',$feed_id)->whereIn('domain_id',$domain_id)->with(array('feed_media'=>function($query){$query->select('id','feed_content_id','title','description','external_link','video_link');}))->get(15);

        if ($request->feed_page_id == 0) {
            $feedContents = $feedContents->orderBy('id', 'DESC')->take(5)->get();
        } else {
            $feedContents = $feedContents->where('id', '<', $request->feed_page_id)->orderBy('id', 'DESC')->take(5)->get();
        }


        $data = [];
        $last_page = '';
        $i = 1;
        foreach ($feedContents as $cont) {
            $mydata['id'] = $cont->id;
            $mydata['type'] = $cont->feedtype->title;
            $mydata['tags'] = explode(",", $cont->tags);
            if (isset($cont->feed_media_single->title)) {
                $title = $cont->feed_media_single->title;
            } else {
                $title = '';
            }
            $mydata['title'] = $title;
            if (isset($cont->feed_media_single->description)) {
                $description = $cont->feed_media_single->description;
            } else {
                $description = '';
            }
            $mydata['description'] = $description;
            $mydata['external_link'] = !empty($cont->feed_media_single) ? $cont->feed_media_single->external_link : '';
            $mydata['video_link'] = !empty($cont->feed_media_single) ? $cont->feed_media_single->video_link : '';
            if (!empty($cont->feed_media_single) && isset($cont->feed_media_single->placholder_image)) {
                $place = $this->imageurl($cont->feed_media_single->placholder_image);
            } else {
                $place = null;
            }
            $mydata['placeholder_image'] = $place;

            $savefeeds = SaveFeed::where('feed_contents_id', '=', $cont->id)->get();
            $mydata['savepost'] = count($savefeeds->toArray());

            $save = SaveFeed::where('feed_contents_id', '=', $cont->id)->where('user_id', '=', $request->user_id)->first();

            if ($save) {
                $mysave = 1;
            } else {
                $mysave = 0;
            }


            $mydata['is_saved'] = $mysave;
            $mydata['share'] = $this->sharepath($cont->id);
            if (isset($cont->feed_media_single->feed_attachments_single)) {
                $media_type = $cont->feed_media_single->feed_attachments_single->media_type;
            } else {
                $media_type =  '';
            }
            $mydata['media_type'] = $media_type;
            $imagename = [];
            $imgdata = [];
            if (!empty($cont->feed_media_single)) {
                foreach ($cont->feed_media_single->feed_attachments_name as $image) {

                    $imagename[] = $this->imageurl($image->media_name);
                    $imgdata = $imagename;
                }
            }



            $mydata['media'] = $imgdata;
            $data[] = $mydata;
            $last_page = $cont->id;
            $i++;
        }


        //for pagination 

        // $paginate = 5;
        // $page=isset($request->page)?$request->page:'1';
        // $offset= ($page * $paginate) - $paginate;
        // $item= array_slice($feedContents->toArray(),$offset,$paginate,true);
        // $result= new\Illuminate\Pagination\LengthAwarePaginator($item, count($feedContents).$paginate, $page);

        // $result= $result->toArray();
        if (empty($feedContents)) {
            return response()->json(['status' => 200, 'message' => 'Feed not available', 'data' => '']);
        }
        return response()->json(['status' => 200, 'message' => 'Domain data', 'last_id' => $last_page, 'data' => $data]);
    }

    function imageurl($image)
    {
        try {
            return url('/storage') . '/' . $image;
        } catch (\Throwable $th) {
            return '';
        }
    }

    function sharepath($id)
    {
        try {
            return url('/feed') . '/' . $id;
        } catch (\Throwable $th) {
            return '';
        }
    }

    function savepost(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'feed_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'data' => '', 'message' => $validator->errors()]);
        }
        //feed_contents_id

        if ($request->type == '1') {
            $data = SaveFeed::where('user_id', $request->user_id)->where('feed_contents_id', $request->feed_id)->first();
            if ($data) {
                return response()->json(['status' => 200, 'data' => '', 'message' => 'Feed already saved']);
            }
            $data = new SaveFeed;
            $data->feed_contents_id = $request->feed_id;
            $data->user_id = $request->user_id;
            $data->save();
        }
        if ($request->type == '0') {
            SaveFeed::where('user_id', $request->user_id)->where('feed_contents_id', $request->feed_id)->delete();
            return response()->json(['status' => 200, 'data' => '', 'message' => 'Feed unsaved']);
        }
        return response()->json(['status' => 200, 'data' => '', 'message' => 'Feed saved']);
    }



    public function tagfilter(Request $request)
    {

        $feedContents = FeedContent::select('id', 'feed_id', 'type', 'tags', 'title', 'description')->where('status', '1');


        if ($request->type == '0') {
            $feedContents = $feedContents->where('tags', 'like', '%' . $request->searchkey . '%');
        } else {

            $feedContents = $feedContents->orWhere('tags', 'like', '%' . $request->searchkey . '%')->orWhere('title', 'like', '%' . $request->searchkey . '%');
        }


        $feedContents = $feedContents->get();
        $data = [];
        $last_page = 0;
        $i = 1;
        foreach ($feedContents as $cont) {
            $mydata['id'] = $cont->id;
            $mydata['type'] = $cont->feedtype->title;
            $mydata['tags'] = explode(",", $cont->tags);
            $mydata['title'] = $cont->feed_media_single->title;
            $mydata['description'] = $cont->feed_media_single->description;
            $mydata['external_link'] = $cont->feed_media_single->external_link;
            $mydata['video_link'] = $cont->feed_media_single->video_link;
            if (isset($cont->feed_media_single->placholder_image)) {
                $place = $this->imageurl($cont->feed_media_single->placholder_image);
            } else {
                $place = null;
            }
            $mydata['placeholder_image'] = $place;
            $savefeeds = SaveFeed::where('feed_contents_id', $cont->id)->pluck('feed_contents_id');
            $mydata['savepost'] = count($savefeeds);
            if (isset($cont->savefeed)) {
                $save = 1;
            } else {
                $save = 0;
            }
            $mydata['is_saved'] = $save;
            $mydata['share'] = $this->sharepath($cont->id);
            $mydata['media_type'] = ($cont->feed_media_single->feed_attachments_single) ? $cont->feed_media_single->feed_attachments_single->media_type : '';
            $imagename = [];
            foreach ($cont->feed_media_single->feed_attachments_name as $image) {

                $imagename[] = $this->imageurl($image->media_name);
                $imgdata = $imagename;
            }

            $mydata['media'] = $imgdata;
            $data[] = $mydata;
            $last_page = $cont->id;
            $i++;
        }

        if (empty($feedContents)) {
            return response()->json(['status' => 200, 'message' => 'Feed not available', 'data' => '']);
        }
        return response()->json(['status' => 200, 'message' => 'Feed data', 'last_id' => $last_page, 'data' => $data]);
    }

    public function feed_collection_view()
    {
        $single_posts = FeedContent::where('feed_id', '=', '1')->where('status', '1')->get();
        //dd($single_posts);
        $themes = Theme::OrderBy('id', 'DESC')->get();
        $domains = Domain::OrderBy('id', 'DESC')->get();
        $sub_domains = Subdomain::OrderBy('id', 'DESC')->get();
        $types = Feed::OrderBy('id', 'DESC')->get();
        //dd($themes);
        //dd($domains);
        //dd($sub_domains);
        //dd($types);
        return view('feed-content.feed-collection', compact('domains', 'sub_domains', 'themes', 'types', 'single_posts'));
    }

    public function feed_collection_store(Request $request)
    {


        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required|max:255'
        ]);


        $newFeedContent = new FeedContent;
        $newFeedContent->theme_id = $request->theme_id;
        $newFeedContent->domain_id = $request->domain_id;
        // $newFeedContent->sub_domain_id = $request->sub_domain_id;
        $newFeedContent->type = $request->type;
        $newFeedContent->feed_id = $request->feed_id;
        $newFeedContent->title = $request->title;
        $newFeedContent->description = $request->description;

        $newFeedContent->save();


        foreach ($request->single_post as $single_post) {
            $newCollection = new FeedCollection;
            $newCollection->feed_content_id = $newFeedContent->id;
            $newCollection->single_post_id = $single_post;
            $newCollection->save();
        }
        //dd($request);
        return redirect('admin/feed-content')->with(['success' => 'Feed saved Successfully']);
    }

    // get user save all feed for api

    public function save_feed(Request $request)
    {

        $feeds = SaveFeed::where('user_id', $request->id)->pluck('feed_contents_id');


        $feedContents = FeedContent::select('id', 'feed_id', 'type', 'tags', 'title', 'description')->whereIn('id', $feeds)->where('status', '1')->get();

        $last_page = '';
        $i = 1;
        $data = [];
        foreach ($feedContents as $cont) {
            $mydata['id'] = $cont->id;
            $mydata['type'] = $cont->feedtype->title;
            $mydata['tags'] = explode(",", $cont->tags);
            $mydata['title'] = $cont->feed_media_single->title;
            $mydata['description'] = $cont->feed_media_single->description;
            $mydata['external_link'] = $cont->feed_media_single->external_link;
            $mydata['video_link'] = $cont->feed_media_single->video_link;
            if (isset($cont->feed_media_single->placholder_image)) {
                $place = $this->imageurl($cont->feed_media_single->placholder_image);
            } else {
                $place = null;
            }
            $mydata['placeholder_image'] = $place;
            $savefeeds = SaveFeed::where('feed_contents_id', $cont->id)->pluck('feed_contents_id');
            $mydata['savepost'] = count($savefeeds);
            //   if(in_array($cont->id,$feeds->toArray())){
            //       $issave =1;
            //   }else{
            //     $issave =0; 
            //   }
            if (isset($cont->savefeed)) {
                $save = 1;
            } else {
                $save = 0;
            }
            $mydata['is_saved'] = $save;
            $mydata['share'] = $this->sharepath($cont->id);
            if (isset($cont->feed_media_single->feed_attachments_single)) {
                $mediatype = $cont->feed_media_single->feed_attachments_single->media_type;
            } else {
                $mediatype = '';
            }
            $mydata['media_type'] = $mediatype;
            $imagename = [];
            $imgdata = [];
            foreach ($cont->feed_media_single->feed_attachments_name as $image) {

                $imagename[] = $this->imageurl($image->media_name);
                $imgdata = $imagename;
            }

            $mydata['media'] = $imgdata;
            $data[] = $mydata;
            $last_page = $cont->id;
            $i++;
        }

        if (empty($feedContents)) {
            return response()->json(['status' => 200, 'message' => 'Feed not available', 'data' => '']);
        }
        return response()->json(['status' => 200, 'message' => 'Domain data', 'data' => $data]);
    }





    public function module(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'theme_id' => 'required',
            // 'domain_id' => 'required',
            'type' => 'required',
            'module_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'data' => '', 'message' => $validator->errors()]);
        }
        $feedContents = FeedContent::select('id', 'feed_id', 'type', 'tags', 'title', 'description')->with('feed_media');
        $feedContents = $feedContents->where('id', $request->module_id)->first();
        if (empty($feedContents)) {
            return response()->json(['status' => 200, 'message' => 'Feed not available', 'data' => '']);
        }
        $data = [];
        $last_page = '';
        $i = 1;
        foreach ($feedContents->feed_media as $cont) {
            $mydata['id'] = $feedContents->id;
            $mydata['type'] = $feedContents->feedtype->title;
            $mydata['tags'] = explode(",", $feedContents->tags);
            $mydata['title'] = $cont->title;
            $mydata['description'] = $cont->description;
            $mydata['external_link'] = $cont->external_link;
            $mydata['video_link'] = $cont->video_link;
            if (isset($feedContents->placholder_image)) {
                $place = $this->imageurl($feedContents->feed_media_single->placholder_image);
            } else {
                $place = null;
            }
            $mydata['placeholder_image'] = $place;
            $savefeeds = SaveFeed::where('feed_contents_id', $cont->id)->pluck('feed_contents_id');
            $mydata['savepost'] = count($savefeeds);
            $mydata['is_saved'] = fmod($i, 2);
            $mydata['share'] = $this->sharepath($cont->id);
            $mydata['media_type'] = $feedContents->feed_media_single->feed_attachments_single->media_type;
            $imagename = [];
            foreach ($cont->feed_attachments as $image) {

                $imagename[] = $this->imageurl($image->media_name);
                $imgdata = $imagename;
            }

            $mydata['media'] = $imgdata;
            $data[] = $mydata;
            $last_page = $feedContents->id;
            $i++;
        }


        return response()->json(['status' => 200, 'title' => $feedContents->title, 'message' => 'Feed data', 'last_id' => $last_page, 'data' => $data]);
    }



    // get feed content data according feed_content id
    public function get_feed_content_by_id($id)
    {
        $page = $_GET['page'];

        $themes = Theme::OrderBy('id', 'DESC')->get();
        $domains = Domain::OrderBy('id', 'DESC')->get();
        $feeds = Feed::OrderBy('id', 'DESC')->get();
        $feed = FeedContent::whereId($id)->first();
        $feedContents = FeedMedia::where('feed_content_id', $id)->orderByDesc('id')->get();
        return view('feed-content.feed-edit', compact('feed', 'themes', 'domains', 'feeds', 'feedContents', 'page'));
        //     $data = [];

        //     $feedContent =  FeedContent::find($id);
        //     $feed_type = $feedContent->feed_id;
        //     if($feedContent->feed_id == '1')
        //     {   $data['id'] = $feedContent->id;
        //         $data['theme_id'] = $feedContent->theme_id;
        //         $data['theme_name'] = $feedContent->theme->title;
        //         $data['domain_id'] = $feedContent->domain_id;
        //         $data['domain_name'] = $feedContent->domain->name;
        //         $data['feed_id'] = $feedContent->feed_id;
        //         $data['feed_name'] = $feedContent->feedtype->title;
        //         $data['tags'] = $feedContent->tags;
        //         $data['fix_title'] = $feedContent->title;
        //         $data['fix_description'] = $feedContent->description;


        //         $feed_mediaes = FeedMedia::where('feed_content_id','=',$feedContent->id)->get()->first();
        //         $data['external_link'][] = $feed_mediaes->external_link;
        //         $data['medai_id'] = $feed_mediaes->id;
        //        // $data['video_link'][] = $feed_media->video_link;

        //         $feed_attachmentes = FeedAttachment::where('feed_media_id','=',$feed_mediaes->id)->get();
        //         foreach($feed_attachmentes as $feed_attachment)
        //         {

        //             $data['media_names'][] = $feed_attachment->media_name;
        //             $data['media_ids'][] = $feed_attachment->id;
        //             $data['images_url'][] = $this->imageurl($feed_attachment->media_name);

        //         }
        //   // dd($data);
        //         return view('feed-content.feed-edit',compact('feed_type','themes','domains','feeds','data'));
        //     }
        //     else if($feedContent->feed_id == '2')
        //     {

        //             $data['id'] = $feedContent->id;
        //             $data['theme_id'] = $feedContent->theme_id;
        //             $data['theme_name'] = $feedContent->theme->title;
        //             $data['domain_id'] = $feedContent->domain_id;
        //             $data['domain_name'] = $feedContent->domain->name;
        //             $data['feed_id'] = $feedContent->feed_id;
        //             $data['feed_name'] = $feedContent->feedtype->title;
        //             $data['tags'] = $feedContent->tags;
        //             $data['fix_title'] = $feedContent->title;
        //             $data['fix_description'] = $feedContent->description;
        //             $feed_type = $feedContent->feed_id;
        //            //dd($feed_type);

        //             $feed_mediaes = FeedMedia::where('feed_content_id','=',$feedContent->id)->get();
        //             $x=0;
        //             foreach($feed_mediaes as $feed_media)
        //             {

        //                 $data['media'][$feed_media->id]['title'] = $feed_media->title;
        //                 $data['media'][$feed_media->id]['description'] = $feed_media->description;
        //                 $data['media'][$feed_media->id]['external_link'] = $feed_media->external_link;
        //                 $data['media'][$feed_media->id]['video_link'] = $feed_media->video_link;
        //                 $data['media'][$feed_media->id]['placholder_image'] = $this->imageurl($feed_media->placholder_image);
        //                 $feed_attachmentes = FeedAttachment::where('feed_media_id','=',$feed_media->id)->get();
        //                 foreach($feed_attachmentes as $feed_attachment)
        //                 {
        //                     $data['media'][$feed_media->id]['media_name'][$feed_attachment->id] = $this->imageurl($feed_attachment->media_name);

        //                     $data['media'][$feed_media->id]['media_type'][$feed_attachment->id] = $feed_attachment->media_type;
        //                     //$data['media'][$feed_media->id]['medai_id'][] = ;
        //                 }
        //                 $x++;
        //             }
        //           //dd($data);
        //             return view('feed-content.feed-edit',compact('feed_type','themes','domains','feeds','data'));

        //     }
        //     else
        //     {

        //     }

        //    // dd($data);
        //     //return $feedContent->id;
        //     $feedMedia = FeedMedia::where('feed_content_id','=',$feedContent->id)->get();

        //     $feedMediaIds = FeedMedia::where('feed_content_id','=',$feedContent->id)->pluck('id');

        //     $feedAtachment = FeedAttachment::whereIn('feed_media_id',$feedMediaIds)->get();

        //    return $feedMedia;
    }


    public function update_feed_attachment(Request $req)
    {

        if ($req->type == "1") {

            $feed = FeedContent::whereId($req->feed_content_id)->first();
            $feed->title = $req->fix_title;
            $feed->theme_id = $req->theme_id;
            $feed->domain_id = $req->domain_id;
            $feed->tags = $req->tags;
            $feed->description = $req->description;
            $feed->save();

            $media = FeedMedia::where('feed_content_id', $req->feed_content_id)->first();

            if ($req->media_type == '0') {

                if (FeedAttachment::where('feed_media_id', $media->id)->where('media_type', '0')->first()) {

                    FeedAttachment::where('feed_media_id', $media->id)->where('media_type', '0')->delete();
                    if (isset($req->old_images)) {
                        foreach ($req->old_images as $image) {
                            $images = new FeedAttachment;
                            $images->feed_media_id = $media->id;
                            $images->media_type = '0';
                            $images->media_name = $image;
                            $images->save();
                        }
                    }
                }

                if (FeedAttachment::where('feed_media_id', $media->id)->where('media_type', '1')->first()) {
                    FeedAttachment::where('feed_media_id', $media->id)->where('media_type', '1')->delete();
                }

                $media->description = $req->description;
                $media->video_link = "";
                $media->placholder_image = "";
                $media->description = $req->description;
                $media->external_link = $req->external_link;
                $media->save();
                if ($req->hasfile('files')) {
                    foreach ($req->file('files') as $key => $file) {
                        $type = '0';
                        $name = $file->store('feed', 'public');
                        $attachment = new FeedAttachment;
                        $attachment->feed_media_id = $media->id;
                        $attachment->media_name = $name;
                        $attachment->media_type = $type;
                        $attachment->save();
                    }
                }
            } else {


                if ($req->hasfile('placeholder_image')) {
                    $placeholder_name = $req->placeholder_image->store('feed', 'public');
                } else {
                    $placeholder_name = $req->old_placeholder;
                }
                $media->description = $req->description;
                $media->video_link = $req->video_link;
                $media->placholder_image = $placeholder_name;
                $media->save();

                if (isset($req->video)) {
                    if (FeedAttachment::where('feed_media_id', $media->id)->where('media_type', '0')->first()) {
                        FeedAttachment::where('feed_media_id', $media->id)->where('media_type', '0')->delete();
                    }


                    if (FeedAttachment::where('feed_media_id', $media->id)->where('media_type', '1')->first()) {
                        FeedAttachment::where('feed_media_id', $media->id)->where('media_type', '1')->delete();
                    }

                    $name = $req->video->store('feed', 'public');
                    $attachment = new FeedAttachment;
                    $attachment->feed_media_id = $media->id;
                    $attachment->media_name = $name;
                    $attachment->media_type = '1';
                    $attachment->save();
                } else {
                    if (FeedAttachment::where('feed_media_id', $media->id)->where('media_type', '0')->first()) {
                        FeedAttachment::where('feed_media_id', $media->id)->where('media_type', '0')->delete();
                    }


                    if (FeedAttachment::where('feed_media_id', $media->id)->where('media_type', '1')->first()) {
                        FeedAttachment::where('feed_media_id', $media->id)->where('media_type', '1')->delete();
                    }


                    $attachment = new FeedAttachment;
                    $attachment->feed_media_id = $media->id;
                    $attachment->media_name = $req->old_video;
                    $attachment->media_type = '1';
                    $attachment->save();
                }
            }
        } elseif ($req->type == "2") {

            $feed = FeedContent::whereId($req->feed_content_id)->first();
            $feed->title = $req->fix_title;
            $feed->theme_id = $req->theme_id;
            $feed->domain_id = $req->domain_id;
            $feed->tags = $req->tags;
            $feed->description = $req->description;
            $feed->save();
        } else {
            $feed = FeedContent::whereId($req->feed_content_id)->first();
            $feed->title = $req->fix_title;
            $feed->theme_id = $req->theme_id;
            $feed->domain_id = $req->domain_id;
            $feed->tags = $req->tags;
            $feed->description = $req->description;
            $feed->save();
        }

        return redirect("admin/feed-content?page=$req->page")->with('success', 'feed content has been updated successfully.');
        //    dd($request);
        //     if($request->feed_id == 1)
        //     {
        //         $feed_content  = FeedContent::find($request->feed_content_id);
        //        //dd($request->theme_id);
        //        $feed_content->theme_id=$request->theme_id;
        //        $feed_content->domain_id=$request->domain_id;
        //        $feed_content->tags = $request->tags;
        //        $feed_content->title = $request->fix_title;
        //        $feed_content->description = $request->description;
        //        $feed_content->save(); 

        //         $feed_media = FeedMedia::where('feed_content_id','=',$request->feed_content_id)->get()->first();

        //        // dd($feed_media);
        //         $feed_media->external_link = $request->external_link[0];
        //         $feed_media->title = $request->fix_title;
        //         $feed_media->description = $request->description[0];
        //         $feed_content->save();

        //         // $feed_media->update(['external_link'=>$request->external_link[0],'title'=>$request->fix_title,'description'=>$request->description[0]]);

        //         foreach($request->file('media_name') as $key=>$file)
        //         {
        //           //  dd($file);
        //             $feed_attachment = FeedAttachment::find($key);
        //            // unlink(storage_path('app/folder/'.$feed_attachment->media_name));
        //             $file_name = $file->store('feed','public'); 
        //             $feed_attachment->media_name = $file_name;

        //             $feed_attachment->save();
        //         }

        //         foreach($request->delete_media as $key=>$value)
        //         {
        //             $feed_attachment = FeedAttachment::find($key);
        //            // unlink(storage_path('app/folder/'.$feed_attachment->media_name));

        //             $feed_attachment->delete();
        //         }


        //     }
        //     else if($request->feed_id == 2)
        //     {
        //        // dd($request);
        //         $feed_content  = FeedContent::find($request->feed_content_id);
        //         //dd($request->theme_id);
        //         $feed_content->theme_id=$request->theme_id;
        //         $feed_content->domain_id=$request->domain_id;
        //         $feed_content->tags = $request->tags;
        //         $feed_content->title = $request->fix_title;
        //         $feed_content->description = $request->description;
        //         $feed_content->save(); 

        //         foreach($request['media'] as $media_ids =>$media)
        //         {
        //           //  dd($media);
        //             if(isset($request->placholder_image[$media_ids]))
        //             {
        //                 // update place hoder image 
        //             }
        //             $feed_media = FeedMedia::find($media_ids);
        //             $feed_media->title = $media['title'];
        //             $feed_media->description = $media['description'];
        //             $feed_media->external_link = $media['external_link'];
        //             $feed_media->video_link = $media['video_link'];

        //             $feed_media->save();





        //         }

        //         if(!empty($request->media) && count($request->media)>0)
        //         {
        //         foreach($request->file('media') as $key=>$file)
        //         {
        //           //  dd($file);
        //             $feed_attachment = FeedAttachment::find($key);
        //            // unlink(storage_path('app/folder/'.$feed_attachment->media_name));
        //             $file_name = $file->store('feed','public'); 
        //             $feed_attachment->media_name = $file_name;

        //             $feed_attachment->save();
        //         }
        //       }

        //         // dd($feed_media);
        //        //  $feed_media->external_link = $request->external_link[0];


        //          // $feed_media->update(['external_link'=>$request->external_link[0],'title'=>$request->fix_title,'description'=>$request->description[0]]);

        //          foreach($request->file('media_name') as $key=>$file)
        //          {
        //            //  dd($file);
        //              $feed_attachment = FeedAttachment::find($key);
        //             // unlink(storage_path('app/folder/'.$feed_attachment->media_name));
        //              $file_name = $file->store('feed','public'); 
        //              $feed_attachment->media_name = $file_name;

        //              $feed_attachment->save();
        //          }

        //          foreach($request->delete_media as $key=>$value)
        //          {
        //              $feed_attachment = FeedAttachment::find($key);
        //             // unlink(storage_path('app/folder/'.$feed_attachment->media_name));

        //              $feed_attachment->delete();
        //          }

        //     }
        //     else
        //     {

        //     }
    }
    public function filter_feed(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'serach' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'data' => '', 'message' => $validator->errors()]);
        }

        $feeds = SaveFeed::where('user_id', $request->user_id)->pluck('feed_contents_id');


        $feedContents = FeedContent::select('id', 'feed_id', 'type', 'tags', 'title', 'description')->whereIn('id', $feeds)->where('status', '1');

        $feedContents = $feedContents->where('title', 'like', '%' . $request->serach . '%')->get();

        $last_page = '';
        $i = 1;
        $data = [];
        foreach ($feedContents as $cont) {
            $mydata['id'] = $cont->id;
            $mydata['type'] = $cont->feedtype->title;
            $mydata['tags'] = explode(",", $cont->tags);
            $mydata['title'] = $cont->feed_media_single->title;
            $mydata['description'] = $cont->feed_media_single->description;
            $mydata['external_link'] = $cont->feed_media_single->external_link;
            $mydata['video_link'] = $cont->feed_media_single->video_link;
            if (isset($cont->feed_media_single->placholder_image)) {
                $place = $this->imageurl($cont->feed_media_single->placholder_image);
            } else {
                $place = null;
            }
            $mydata['placeholder_image'] = $place;
            $savefeeds = SaveFeed::where('feed_contents_id', $cont->id)->pluck('feed_contents_id');
            $mydata['savepost'] = count($savefeeds);
            if (isset($cont->savefeed)) {
                $save = 1;
            } else {
                $save = 0;
            }
            $mydata['is_saved'] = $save;
            $mydata['share'] = $this->sharepath($cont->id);
            if (isset($cont->feed_media_single->feed_attachments_single)) {
                $mediatype = $cont->feed_media_single->feed_attachments_single->media_type;
            } else {
                $mediatype = '';
            }
            $mydata['media_type'] = $cont->feed_media_single->feed_attachments_single->media_type;
            $imagename = [];
            $imgdata = [];
            foreach ($cont->feed_media_single->feed_attachments_name as $image) {

                $imagename[] = $this->imageurl($image->media_name);
                $imgdata = $imagename;
            }

            $mydata['media'] = $imgdata;
            $data[] = $mydata;
            $last_page = $cont->id;
            $i++;
        }

        if (empty($feedContents)) {
            return response()->json(['status' => 200, 'message' => 'Feed not available', 'data' => '']);
        }
        return response()->json(['status' => 200, 'message' => 'Domain data', 'data' => $data]);
    }
    public function edit_media(Request $req)
    {
        $feed = FeedMedia::where('id', $req->id)->first();
        return view('feed-content.edit_media', compact('feed'));
    }
    public function update_feed_media(Request $req)
    {

        $media = FeedMedia::whereId($req->id)->first();
        $media->title = $req->title;
        $media->description = $req->description;
        $media->external_link = $req->external_link;
        if ($req->media_type == '1') {
            if ($req->hasfile('placeholder_image')) {
                $media->placholder_image = $req->file('placeholder_image')->store('feed', 'public');
            } else {
                $media->placholder_image = $req->old_placeholder;
            }
            $media->video_link = $req->video_link;
        } else {
            $media->placholder_image = "";
            $media->video_link = "";
        }

        $media->save();

        if ($req->media_type == "0" && count($req->files)) {
            if (FeedAttachment::where('feed_media_id', $req->id)->where('media_type', '1')->first()) {
                FeedAttachment::where('feed_media_id', $req->id)->where('media_type', '1')->delete();
            }
            foreach ($req->file('files') as $file) {
                $type = '0';
                $name = $file->store('feed', 'public');
                $attachment = new FeedAttachment;
                $attachment->feed_media_id = $media->id;
                $attachment->media_name = $name;
                $attachment->media_type = $type;
                $attachment->save();
            }
        } else {
            if (FeedAttachment::where('feed_media_id', $req->id)->where('media_type', '0')->first()) {
                FeedAttachment::where('feed_media_id', $req->id)->where('media_type', '0')->delete();
            }

            if ($req->hasfile('video')) {

                $type = '1';
                $name = $req->file('video')->store('feed', 'public');
                $attachment = new FeedAttachment;
                $attachment->feed_media_id = $media->id;
                $attachment->media_name = $name;
                $attachment->media_type = $type;
                $attachment->save();
            }
        }

        $media->save();
        return redirect("admin/get-feed-content-by-id/$req->feed_content_id");
    }
    public function add_media(Request $req)
    {
        $id = $req->id;
        return view('feed-content.add_media', compact('id'));
    }
    public function add_feed_media(Request $req)
    {

        $media = new FeedMedia;
        $media->feed_content_id = $req->feed_content_id;
        $media->title = $req->title;
        $media->description = $req->description;
        $media->external_link = $req->external_link;

        if ($req->hasfile('placeholder_image')) {
            $media->placholder_image = $req->file('placeholder_image')->store('feed', 'public');
            $media->video_link = $req->video_link;
        } else {
            $media->placholder_image = "";
            $media->video_link = "";
        }

        $media->save();


        if ($req->media_type == "0" && count($req->files)) {

            foreach ($req->file('files') as $file) {
                $type = '0';
                $name = $file->store('feed', 'public');
                $attachment = new FeedAttachment;
                $attachment->feed_media_id = $media->id;
                $attachment->media_name = $name;
                $attachment->media_type = $type;
                $attachment->save();
            }
        } else {

            if ($req->hasfile('video')) {

                $type = '1';
                $name = $req->file('video')->store('feed', 'public');
                $attachment = new FeedAttachment;
                $attachment->feed_media_id = $media->id;
                $attachment->media_name = $name;
                $attachment->media_type = $type;
                $attachment->save();
            }
        }


        return redirect("admin/get-feed-content-by-id/$req->feed_content_id");
    }
}
