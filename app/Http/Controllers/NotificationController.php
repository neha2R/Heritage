<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\NotificationSetting;
use App\Notification;
use App\NotificationDetail;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{

// ======== Api function for notification start here.===== 

     /**
     * Get all notification and check which notification is present in user seeting.
     *
     * @param  \Illuminate\Http\Request  $id of user
     * @return \Illuminate\Http\Response
     */
    public function fetchNotification(Request $request){
     
        if (!isset($request->id)) {
            return response()->json(['status' => 202, 'data' => '', 'message' => 'User Id is required']);
        }

        $userId = $request->id;

        $notifications = Notification::select('id','title','hint')->get();
        $data=[];
        foreach($notifications as $noti){
            $details= NotificationDetail::where('notification_id',$noti->id)->get();
            $data['id'] = $noti->id;
            $data['title'] = $noti->title;
            $data['hint'] = $noti->hint;
            $assigndata=[];
            foreach($details as $detail){
                      
            $otherdata['id']=$detail->id;
            $otherdata['title']=$detail->title;
           $check = NotificationSetting::where('notification_details_id',$detail->id)->where('user_id',$userId)->first();
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

      return response()->json(['status' => 200, 'message' => 'Notification data', 'data' => $response]);

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
            'notifications_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 422, 'data' => '', 'message' => $validator->errors()]);
        }
       $olddata = NotificationSetting::where('user_id',$request->user_id)->delete();
    //   if($olddata->count()>0){
    //    $olddata->delete();
    //   }
      $notifications_id = explode(',',$request->notifications_id);
       foreach($notifications_id as $id){
        $savedata = new NotificationSetting;
        $savedata->user_id = $request->user_id;
        $savedata->notification_details_id = $id;
        $savedata->save();
       }
       return response()->json(['status' => 200, 'data' => 'success', 'message' => 'Notification update succesfully..']);

       
    }

/*======== Api function for notification ENDS here.===== */

}
