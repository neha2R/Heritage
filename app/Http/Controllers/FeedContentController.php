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
       
        if($request->feed_id == 1)
        {
                $validatedData = $request->validate([
                    'theme_id' => 'required',
                    'domain_id' => 'required|',
                    'feed_id' => 'required',
                    'title' => 'required|max:200',
                    'tags'=>'required',
                    'external_link' => 'required|url',
                    'title'=>'required',
                    'description' =>'required' 
                ]);
                        
                $data = new FeedContent;
                $data->theme_id = $request->theme_id;
                $data->domain_id = $request->domain_id;
                $data->feed_id = $request->feed_id;
                $data->tags=$request->tags;

            $data->title = $request->title;
            $data->description = $request->description;            
            $imagemimes = ['image/png', 'image/jpg', 'image/jpeg', 'image_gif']; //Add more mimes that you want to support
             $videomimes = ['video/mp4']; //Add more mimes that you want to support
            $media = new FeedMedia;
            $media->feed_content_id = $data->id;
            $media->title = $request->title;
            $media->description=$request->description;
            $media->external_link=$request->external_link;
            $media->save();

            if($request->hasfile('media_name'))
            {
                foreach($request->file('media_name') as $key=>$file)
                {
                    $type = '0';
                    // FeedMediaUploadJob::dispatchNow($file,$media->id,$type);
                    $name = $file->store('feed','public');
                    $attachment = new FeedAttachment;
                    $attachment->feed_media_id = $media->id;
                    $attachment->media_name = $name;
                    $attachment->media_type = $type;
                    $attachment->save();
                }
            }
        }
        else
        {
            $validatedData = $request->validate([
                
                'title.*'=>'required',
                'description.*' =>'required', 
                // 'media_video'=>'required',
                'placeholder_image.*'=>'required'
            ]);

            $data = new FeedContent;
            $data->theme_id = $request->theme_id;
            $data->domain_id = $request->domain_id;
            $data->feed_id = $request->feed_id;
            $data->tags=$request->tags;
            $data->title = $request->title;
            $data->description = $request->description;    
             $data->save();
  
           // dd($request->title);
           foreach($request->card as $key=>$value)
           {
            
            // $file = $value['media_video'][$key];
           
            // dd($value['media_video'][$key]);
            // dd($value['media_video'][0]->store('feed','public')); 
            $imagemimes = ['image/png', 'image/jpg', 'image/jpeg', 'image_gif']; //Add more mimes that you want to support
             $videomimes = ['video/mp4']; //Add more mimes that you want to support
             
             $media = new FeedMedia;
             $media->feed_content_id = $data->id;           
             // array
             $media->title = $value['title'];
             
             $media->description=$value['description'];
             
             $media->external_link=$value['external_link'];
           
             $media->video_link=$value['video_link'];           
              
              $media->save();
      

         
             foreach($value['media_video'] as $files)
             {
                
                 if($files->getClientOriginalName() != null){
                        if (in_array($files->getMimeType(), $imagemimes)) {
                            $type = '0';
                        }
                        //validate audio
                        if (in_array($files->getMimeType(), $videomimes)) {
                            $type = '1';
                            $place = $value['placeholder_image']->store('feed','public');
                            $feedplace = FeedMedia::find($media->id);
                            $feedplace->placholder_image =  $place;
                            $feedplace->save();

                        }
                        
                    $name = $files->store('feed','public');
                   // FeedMediaUploadJob::dispatch($files,$media->id,$type)->delay(Carbon::now()->addMinutes(1));
                    $attachment = new FeedAttachment;
                    $attachment->feed_media_id = $media->id;
                    $attachment->media_name = $name;
                    $attachment->media_type = $type;
                    $attachment->save();
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
        
        $feedContents = $feedContents->where('id','>=',$request->feed_page_id)->take(5)->OrderBy('id', 'DESC')->get();
        $data=[];
        $last_page='';
        $i=1;
        foreach($feedContents as $cont){
          $mydata['id'] = $cont->id; 
          $mydata['type'] = $cont->feedtype->title; 
          $mydata['tags'] =explode(",",$cont->tags); 
          if(isset($cont->feed_media_single->title)){
            $title = $cont->feed_media_single->title;
        }else{
            $title='';
        }
          $mydata['title'] = $title;  
          if(isset($cont->feed_media_single->description)){
            $description = $cont->feed_media_single->description;
        }else{
            $description='';
        }
          $mydata['description'] =$description ; 
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
                if(isset($cont->savefeed)){
                    $save = 1;
                }else{
                    $save=0;
                }
        $mydata['is_saved'] = $save; 
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
        return redirect('admin/feed-content')->with(['success' => 'Feed saved Successfully']);
        
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
              $mydata['is_saved'] = 1; 
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
       
        $data = [];
        $themes = Theme::OrderBy('id', 'DESC')->get();
        $domains= Domain::OrderBy('id','DESC')->get();
        $feeds = Feed::OrderBy('id','DESC')->get();
        $feedContent =  FeedContent::find($id);
        $feed_type = $feedContent->feed_id;
        if($feedContent->feed_id == '1')
        {
            $data['theme_id'] = $feedContent->theme_id;
            $data['theme_name'] = $feedContent->theme->title;
            $data['domain_id'] = $feedContent->domain_id;
            $data['domain_name'] = $feedContent->domain->name;
            $data['feed_id'] = $feedContent->feed_id;
            $data['feed_name'] = $feedContent->feedtype->title;
            $data['tags'] = $feedContent->tags;
            $data['fix_title'] = $feedContent->title;
            $data['fix_description'] = $feedContent->description;
           
        
            $feed_mediaes = FeedMedia::where('feed_content_id','=',$feedContent->id)->get()->first();
            $data['external_link'][] = $feed_mediaes->external_link;
           // $data['video_link'][] = $feed_media->video_link;

            $feed_attachmentes = FeedAttachment::where('feed_media_id','=',$feed_mediaes->id)->get();
            foreach($feed_attachmentes as $feed_attachment)
            {
                
                $data['media_names'][] = $feed_attachment->media_name;
                $data['media_ids'][] = $feed_attachment->id;
                $data['images_url'][] = $this->imageurl($feed_attachment->media_name);
                
            }
      //  dd($data);
            return view('feed-content.feed-edit',compact('feed_type','themes','domains','feeds','data'));
        }
        else if($feedContent->feed_id == '2')
        {
          
                $data['theme_id'] = $feedContent->theme_id;
                $data['theme_name'] = $feedContent->theme->title;
                $data['feed_id'] = $feedContent->feed_id;
                $data['feed_name'] = $feedContent->feedtype->title;
                $data['tags'] = $feedContent->tags;
                $data['fix_title'] = $feedContent->title;
                $data['fix_description'] = $feedContent->description;
               
               
                $feed_mediaes = FeedMedia::where('feed_content_id','=',$feedContent->id)->get();
                $x=0;
                foreach($feed_mediaes as $feed_media)
                {
                    $data['title'][$x] = $feed_media->title;
                    $data['description'][$x] = $feed_media->description;
                    $data['external_link'][$x] = $feed_media->external_link;
                    $data['video_link'][$x] = $feed_media->video_link;
                    $feed_attachmentes = FeedAttachment::where('feed_media_id','=',$feed_media->id)->get();
                    foreach($feed_attachmentes as $feed_attachment)
                    {
                        $data['media_name'][$x][] = $feed_attachment->media_name;
                        $data['medai_id'][$x][] = $feed_attachment->id;
                    }
                    $x++;
                }
                return view('feed-content.feed-edit',compact('feed_type','themes','domains','feeds','data'));

        }
        else
        {

        }

        dd($data);
        //return $feedContent->id;
        $feedMedia = FeedMedia::where('feed_content_id','=',$feedContent->id)->get();
      
        $feedMediaIds = FeedMedia::where('feed_content_id','=',$feedContent->id)->pluck('id');
        
        $feedAtachment = FeedAttachment::whereIn('feed_media_id',$feedMediaIds)->get();
       
       return $feedMedia;
    }

    
    public function update_feed_attachment(Request $request)
    {
        dd($request);
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

        $feeds = SaveFeed::where('user_id',$request->user_id)->pluck('feed_contents_id');
        
     
        $feedContents = FeedContent::select('id','feed_id','type','tags','title','description')->whereIn('id',$feeds);

        $feedContents = $feedContents->where('title','like','%' . $request->serach . '%')->get();
      
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
          $mydata['is_saved'] = 1; 
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
      
}
