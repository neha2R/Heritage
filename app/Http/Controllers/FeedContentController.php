<?php

namespace App\Http\Controllers;

use App\FeedContent;
use Illuminate\Http\Request;
use App\Theme;
use App\Domain;
use App\Feed;
use App\FeedMedia;
use App\FeedAttachment;
use Illuminate\Support\Facades\Validator;

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
    //   dd($request);
        $validatedData = $request->validate([
            'theme_id' => 'required',
            'domain_id' => 'required|',
            'feed_id' => 'required',
            'title' => 'required',
        ]);
        // single value for all feed 
         //dd($request);

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
            $data->title = $request->title_fix;
            $data->description = $request->description_fix;
        }
        
        // $data->type = '1';
     
        $data->save();
       // dd('hello');
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
             
                    //     if (in_array($file->getMimeType(), $imagemimes)) {
                    //     $type = '0';
                    //     }
                    // //validate audio
                    // if (in_array($file->getMimeType(), $videomimes)) {
                    //     $type = '1';
                    // }

                    $type = '0';
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
           // dd($request->title);
            foreach($request->title as $key=>$value)
            {
             // echo "manish";
                
            
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
                 // dd($files);
                //foreach($files as $file)
                   //  {
                        
              
                         //     if (in_array($file->getMimeType(), $imagemimes)) {
                         //     $type = '0';
                         // }
                         // //validate audio
                         // if (in_array($file->getMimeType(), $videomimes)) {
                         //     $type = '1';
                         // }
                         $type = '1';
                         $name = $files->store('feed','public');
 
                         $attachment = new FeedAttachment;
                         $attachment->feed_media_id = $media->id;
                         $attachment->media_name = $name;
                         $attachment->media_type = $type;
                         $attachment->save();
                   //  }
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
        

       
       

        // $imagemimes = ['image/png', 'image/jpg', 'image/jpeg', 'image_gif']; //Add more mimes that you want to support
        // $videomimes = ['video/mp4']; //Add more mimes that you want to support
        // $media = new FeedMedia;
        // $media->feed_content_id = $data->id;
        // $media->title = $request->title;
        // $data->description=$request->description;
        // $media->external_link=$request->external_link;
        // $media->video_link=$request->video_link;
        // $media->save();

        // if($request->hasfile('media_name'))
        // {
        //    foreach($request->file('media_name') as $key=>$file)
        //    {
             
        //     if (in_array($file->getMimeType(), $imagemimes)) {
        //         $type = '0';
        //     }

        //     //validate audio
        //     if (in_array($file->getMimeType(), $videomimes)) {
        //         $type = '1';
        //     }
        //     $name = $file->store('feed','public');

        //     $attachment = new FeedAttachment;
        //      $attachment->feed_media_id = $media->id;
        //      $attachment->media_name = $name;
        //      $attachment->media_type = $type;
        //      $attachment->save();
        //     }
        

       

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
}
