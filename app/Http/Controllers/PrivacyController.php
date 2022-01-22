<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Privacy;
use App\PrivacyDetail;
use App\PrivacySetting;

use Illuminate\Support\Facades\Validator;
class PrivacyController extends Controller
{
    
    // ======== Api function for notification start here.===== 

     /**
     * Get all notification and check which notification is present in user seeting.
     *
     * @param  \Illuminate\Http\Request  $id of user
     * @return \Illuminate\Http\Response
     */
    public function fetchPrivacy(Request $request){
     
        if (!isset($request->id)) {
            return response()->json(['status' => 202, 'data' => '', 'message' => 'User Id is required']);
        }

        $userId = $request->id;

        $notifications = Privacy::select('id','title')->get();
        $data=[];
        foreach($notifications as $noti){
            $details= PrivacyDetail::where('privacy_id',$noti->id)->get();
            $data['id'] = $noti->id;
            $data['title'] = $noti->title;
            $assigndata=[];
            foreach($details as $detail){
                      
            $otherdata['id']=$detail->id;
            $otherdata['title']=$detail->title;
           $check = PrivacySetting::where('privacy_details_id',$detail->id)->where('user_id',$userId)->first();
           if(isset($check)){
               $is_checked = 1;
           }else{
            $is_checked = 0; 
           }
           $otherdata['is_checked']=$is_checked;  
           
           $assigndata[] = $otherdata;
          
        }

        $data['data'] = $assigndata;
        $response[] = $data;
        
      }

      return response()->json(['status' => 200, 'message' => 'Privacy data', 'data' => $response]);

    }
/**
     * Save notification of particular user
     *
     * @param  \Illuminate\Http\Request  
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request){


        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'privacy_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 422, 'data' => '', 'message' => $validator->errors()]);
        }
       $olddata = PrivacySetting::where('user_id',$request->user_id)->get();
      if($olddata->count()>0){
       $olddata->delete();
      }
      $notifications_id = explode(',',$request->privacy_id);
       foreach($notifications_id as $id){
        $savedata = new PrivacySetting;
        $savedata->user_id = $request->user_id;
        $savedata->privacy_details_id = $id;
        $savedata->save();
       }
       return response()->json(['status' => 200, 'data' => 'success', 'message' => 'Privacy update succesfully..']);

       
    }

/*======== Api function for notification ENDS here.===== */

}
