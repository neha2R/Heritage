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
       
        $validatedData = $request->validate([
            'theme_id' => 'required',
            'domain_id' => 'required|',
            'feed_id' => 'required',
            'title' => 'required|max:200',
            'tags'=>'required',
            'external_link.*' => 'required|url',
            'title.*'=>'required',
            'description.*' =>'required' 
        ]);
        
        $data = new FeedContent;
        $data->theme_id = $request->theme_id;
        $data->domain_id = $request->domain_id;
        $data->feed_id = $request->feed_id;
        $data->tags=$request->tags;

        if($request->feed_id == 1)
        {
            $data->title = $request->title['0'];
            $data->description = $request->description['0'];
        }
        else
        {
            $validatedData = $request->validate([
                
                'title_fix.*'=>'required',
                'description_fix.*' =>'required', 
                'media_name_'=>'required',
                'placeholder_image'=>'required'
            ]);
            $data->title = $request->title_fix;
            $data->description = $request->description_fix;
        }
        
     
        $data->save();
        // check feed is single than only single value store 
        if($request->feed_id == 1)
        { 


            $imagemimes = ['image/png', 'image/jpg', 'image/jpeg', 'image_gif']; //Add more mimes that you want to support
            // $videomimes = ['video/mp4']; //Add more mimes that you want to support
            $media = new FeedMedia;
            $media->feed_content_id = $data->id;
            $media->title = $request->title['0'];
            $media->description=$request->description['0'];
            $media->external_link=$request->external_link['0'];
            $media->video_link=$request->video_link['0'];
            $media->save();

            if($request->hasfile('media_name'))
            {
                foreach($request->file('media_name') as $key=>$file)
                {
                    $type = '0';
                    FeedMediaUploadJob::dispatchNow($file,$media->id,$type);
                    // $name = $file->store('feed','public');
                    // $attachment = new FeedAttachment;
                    // $attachment->feed_media_id = $media->id;
                    // $attachment->media_name = $name;
                    // $attachment->media_type = $type;
                    // $attachment->save();
                }
            }

        }
        else 
        {   
           // dd($request->title);
            foreach($request->title as $key=>$value)
            {
                // $imagemimes = ['image/png', 'image/jpg', 'image/jpeg', 'image_gif']; //Add more mimes that you want to support
              $videomimes = ['video/mp4']; //Add more mimes that you want to support
              
              $media = new FeedMedia;
              $media->feed_content_id = $data->id;
            
              // array
              $media->title = $request->title[$key];
              
              $media->description=$request->description[$key];
              
              $media->external_link=$request->external_link[$key];
            
              $media->video_link=$request->video_link[$key];
                
              // paceholder image 
              $media->placholder_image = $request->file('placeholder_image')[$key]->store('feed','public');
               
              $media->save();

              //dd($request->file('media_name_')[$key]);
              //dd($request->files->placeholder_image);
              foreach($request->file('media_name_')[$key] as $files)
              {
                    $type = '1';
                    // $name = $files->store('feed','public');
                    FeedMediaUploadJob::dispatch($files,$media->id,$type)->delay(Carbon::now()->addMinutes(1));
                    //  $attachment = new FeedAttachment;
                    //  $attachment->feed_media_id = $media->id;
                    //  $attachment->media_name = $name;
                    //  $attachment->media_type = $type;
                    //  $attachment->save();
                   
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


    public function feed(Request $request)
    {  
        $validator = Validator::make($request->all(), [
            // 'theme_id' => 'required',
            // 'domain_id' => 'required',
            // 'feed_type_id' => 'required',
            'feed_page_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'data' => '', 'message' => $validator->errors()]);
        }

        
        $feedContents = FeedContent::select('id','feed_id','type','tags','title','description');
        
           
        if ($request->theme_id) {
           
            $id = explode(',', $request->theme_id);
            $feedContents = $feedContents->orWhereIn('theme_id', $id);
        }

        if ($request->feed_type_id) {
           
            $feed_id = explode(',', $request->feed_type_id);
            $feedContents = $feedContents->orWhereIn('feed_id',$feed_id);
        }
        if ($request->domain_id) {
          
            $domain_id = explode(',', $request->domain_id);
            $feedContents = $feedContents->orWhereIn('domain_id',$domain_id);
        }
       
       
        // $feedContents2 = FeedContent::select('id','type','tags','title','description')->with('feedtype')->whereIn('feed_id',$feed_id)->whereIn('domain_id',$domain_id)->with(array('feed_media'=>function($query){$query->select('id','feed_content_id','title','description','external_link','video_link');}))->get(15);
        
        $feedContents = $feedContents->where('id','>=',$request->feed_page_id)->take(2)->get();
        $data=[];
        $last_page='';
        $i=1;
        foreach($feedContents as $cont){
          $mydata['id'] = $cont->id; 
          $mydata['type'] = $cont->feedtype->title; 
          $mydata['tags'] =explode(",",$cont->tags); 
          $mydata['title'] = $cont->feed_media_single->title;  
          $mydata['description'] = $cont->feed_media_single->description; 
          $mydata['external_link'] = $cont->feed_media_single->external_link; 
          $mydata['video_link'] = $cont->feed_media_single->video_link; 
          if(isset($cont->feed_media_single->placholder_image)) { $place = $this->imageurl($cont->feed_media_single->placholder_image);
              }
          else{
            $place =null;
              }
          $mydata['placeholder_image'] =$place;  
          $mydata['savepost'] = 20; 
                if(isset($cont->savefeed)){
                    $save = 1;
                }else{
                    $save=0;
                }
        $mydata['is_saved'] = $save; 
          $mydata['share'] = $this->sharepath($cont->id); 
        if(isset($cont->feed_media_single->feed_attachments_single))
          { 
              $media_type = $cont->feed_media_single->feed_attachments_single->media_type ; 
        } else {
               $media_type =  null;
        } 
        $mydata['media_type'] =$media_type;
          $imagename=[];
          foreach($cont->feed_media_single->feed_attachments_name as $image){
             
           $imagename[] = $this->imageurl($image->media_name);
           $imgdata = $imagename;
          }
          
          $mydata['media'] = $imgdata; 
          $data[]=$mydata;
          $last_page = $cont->id;
          $i++;
        }
       
        if(empty($feedContents)){
            return response()->json(['status' => 200, 'message' => 'Feed not available', 'data' => '']);
        }
        return response()->json(['status' => 200, 'message' => 'Domain data', 'last_id'=>$last_page,'data' => $data]);

    }

    function imageurl($image)
    {
    try {
        return url('/storage').'/'.$image;
    } catch (\Throwable $th) {
        return '';
    }

     }

     function sharepath($id)
     {
     try {
         return url('/feed').'/'.$id;
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
       
        if($request->type=='1'){
            $data = SaveFeed::where('user_id',$request->user_id)->where('feed_contents_id',$request->feed_id)->first();
            if($data){
                return response()->json(['status' => 200, 'data' => '', 'message' => 'Feed already saved']); 
            }
        $data = new SaveFeed;
        $data->feed_contents_id = $request->feed_id;
        $data->user_id = $request->user_id;
        $data->save();
        }
        if($request->type=='0'){
            SaveFeed::where('user_id',$request->user_id)->where('feed_contents_id',$request->feed_id)->delete();
            return response()->json(['status' => 200, 'data' => '', 'message' => 'Feed unsaved']);
            }
            return response()->json(['status' => 200, 'data' => '', 'message' => 'Feed saved']);
       
       }



       function tagfilter(Request $request)
       {

      $feedContents = FeedContent::select('id','feed_id','type','tags','title','description');
        
           
      if ($request->type=='0') {
          $feedContents = $feedContents->where('tags', 'like', '%' . $request->searchkey . '%');
      }
      else {
 
          $feedContents = $feedContents->where('title','like','%' . $request->searchkey . '%');
      }


      $feedContents = $feedContents->get();
      $data=[];
      $last_page='';
      $i=1;
      foreach($feedContents as $cont){
        $mydata['id'] = $cont->id; 
        $mydata['type'] = $cont->feedtype->title; 
        $mydata['tags'] =explode(",",$cont->tags); 
        $mydata['title'] = $cont->feed_media_single->title;  
        $mydata['description'] = $cont->feed_media_single->description; 
        $mydata['external_link'] = $cont->feed_media_single->external_link; 
        $mydata['video_link'] = $cont->feed_media_single->video_link; 
        if(isset($cont->feed_media_single->placholder_image)) { $place = $this->imageurl($cont->feed_media_single->placholder_image);
            }
        else{
          $place =null;
            }
        $mydata['placeholder_image'] =$place;  
        $mydata['savepost'] = 20; 
        $mydata['is_saved'] = fmod($i,2); 
        $mydata['share'] = $this->sharepath($cont->id); 
        $mydata['media_type'] = $cont->feed_media_single->feed_attachments_single->media_type; 
        $imagename=[];
        foreach($cont->feed_media_single->feed_attachments_name as $image){
           
         $imagename[] = $this->imageurl($image->media_name);
         $imgdata = $imagename;
        }
        
        $mydata['media'] = $imgdata; 
        $data[]=$mydata;
        $last_page = $cont->id;
        $i++;
      }
     
      if(empty($feedContents)){
          return response()->json(['status' => 200, 'message' => 'Feed not available', 'data' => '']);
      }
      return response()->json(['status' => 200, 'message' => 'Feed data', 'last_id'=>$last_page,'data' => $data]);
    }

    public function feed_collection_view()
    {
        $single_posts = FeedContent::where('feed_id','=','1')->get();
        //dd($single_posts);
        $themes = Theme::OrderBy('id', 'DESC')->get();
        $domains = Domain::OrderBy('id', 'DESC')->get();
        $sub_domains = Subdomain::OrderBy('id', 'DESC')->get();
        $types = Feed::OrderBy('id', 'DESC')->get();
        //dd($themes);
        //dd($domains);
        //dd($sub_domains);
        //dd($types);
        return view('feed-content.feed-collection',compact('domains','sub_domains','themes','types','single_posts'));
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


        foreach($request->single_post as $single_post)
        {
            $newCollection = new FeedCollection;
            $newCollection->feed_content_id = $newFeedContent->id;
            $newCollection->single_post_id = $single_post;
            $newCollection->save();
        }
        //dd($request);
        
    }

    // get user save all feed for api
    
    public function save_feed(Request $request)
    {
        
        $feeds = SaveFeed::where('user_id',$request->id)->pluck('feed_contents_id');
        
     
            $feedContents = FeedContent::select('id','feed_id','type','tags','title','description')->whereIn('id',$feeds)->get();
          
            $last_page='';
            $i=1;
            $data = [];
            foreach($feedContents as $cont){
              $mydata['id'] = $cont->id; 
              $mydata['type'] = $cont->feedtype->title; 
              $mydata['tags'] =explode(",",$cont->tags); 
              $mydata['title'] = $cont->feed_media_single->title;  
              $mydata['description'] = $cont->feed_media_single->description; 
              $mydata['external_link'] = $cont->feed_media_single->external_link; 
              $mydata['video_link'] = $cont->feed_media_single->video_link; 
              if(isset($cont->feed_media_single->placholder_image)) { $place = $this->imageurl($cont->feed_media_single->placholder_image);
                  }
              else{
                $place =null;
                  }
              $mydata['placeholder_image'] =$place;  
              $mydata['savepost'] = 20; 
              $mydata['is_saved'] = fmod($i,2); 
              $mydata['share'] = $this->sharepath($cont->id); 
              $mydata['media_type'] = $cont->feed_media_single->feed_attachments_single->media_type; 
              $imagename=[];
              foreach($cont->feed_media_single->feed_attachments_name as $image){
                 
               $imagename[] = $this->imageurl($image->media_name);
               $imgdata = $imagename;
              }
              
              $mydata['media'] = $imgdata; 
              $data[]=$mydata;
              $last_page = $cont->id;
              $i++;
            }
           
            if(empty($feedContents)){
                return response()->json(['status' => 200, 'message' => 'Feed not available', 'data' => '']);
            }
            return response()->json(['status' => 200, 'message' => 'Domain data','data' => $data]);
    
        

       
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
        $feedContents = FeedContent::select('id','feed_id','type','tags','title','description')->with('feed_media');        
        $feedContents = $feedContents->where('id',$request->module_id)->first();
        if(empty($feedContents)){
            return response()->json(['status' => 200, 'message' => 'Feed not available', 'data' => '']);
        }
        $data=[];
        $last_page='';
        $i=1;
        foreach($feedContents->feed_media as $cont){
          $mydata['id'] = $feedContents->id; 
          $mydata['type'] = $feedContents->feedtype->title; 
          $mydata['tags'] =explode(",",$feedContents->tags); 
          $mydata['title'] = $cont->title;  
          $mydata['description'] = $cont->description; 
          $mydata['external_link'] = $cont->external_link; 
          $mydata['video_link'] = $cont->video_link; 
          if(isset($feedContents->placholder_image)) { 
              $place = $this->imageurl($feedContents->feed_media_single->placholder_image);
              }
          else{
            $place =null;
              }
          $mydata['placeholder_image'] =$place;  
          $mydata['savepost'] = 20; 
          $mydata['is_saved'] = fmod($i,2); 
          $mydata['share'] = $this->sharepath($cont->id); 
          $mydata['media_type'] = $feedContents->feed_media_single->feed_attachments_single->media_type; 
          $imagename=[];
          foreach($cont->feed_attachments as $image){
             
           $imagename[] = $this->imageurl($image->media_name);
           $imgdata = $imagename;
          }
          
          $mydata['media'] = $imgdata; 
          $data[]=$mydata;
          $last_page = $feedContents->id;
          $i++;
        }
       
     
        return response()->json(['status' => 200,'title'=>$feedContents->title, 'message' => 'Feed data', 'last_id'=>$last_page,'data' => $data]);

    }



    // get feed content data according feed_content id
    public function get_feed_content_by_id($id)
    {
        return "Hello Feed";
    }

      
}
