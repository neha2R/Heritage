<?php

namespace App\Http\Controllers;

use App\Agent;
use App\Organisation;
use App\OrganisationTest;
use App\PaymentTransaction;
use App\QuickTest;
use App\Role;
use App\Room_student;
use App\SMS89;
use App\Student;
use App\Subject;
use App\Teacher;
use App\Test;
use App\Traits\CommunicationTrait;
use App\User;
use Auth;
use Crypt;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Session;
use Validator;

class UserController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | User   Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the User Related  Functionality.
    |
    | updated by: Suresh rajpurohit
    | updated by: =================
    |
    | updated on: 24/06/2019
    | updated on: =================
    |
    | Comment: for Commenting & Code Indenting
    | Comment:======================
    |
     */
    use CommunicationTrait;

    public $successStatus = 200;
    // api authentication things

    /**
     * User login for android.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {

        if ($request->is_social == 1) {$user = User::where('email', '=', request('email'))->first();

            if (Auth::loginUsingId($user->id)) {
                $user = Auth::user();

                if ($user->mobile_verified == 0) {
                    $otp_details = $this->sendOtp($user, false);
                    $user->OTP = $otp_details['OTP'];
                    $user->save();

                    return response()->json(['status' => 201, 'message' => "Mobile is not verified. We just send a verification code on your registered mobile, please verify your mobile.", "user_id" => $user->id], 400);

                } elseif ($user->email_verified == 0) {
                    return response()->json(['status' => 203, 'message' => "Your email is not verified, please check your email for verification link."], 400);
                } elseif ($user->status == 0) {
                    return response()->json(['status' => 203, 'message' => "Your account is blocked or not activated. Try after sometime."], 400);
                }

                if (Auth::user()->hasRole('student')) {
                    $user_type = 1;
                } elseif (Auth::user()->hasRole('teacher')) {
                    $user_type = 2;
                } elseif (Auth::user()->hasRole('organisation')) {
                    $user_type = 3;
                } else {
                    return response()->json(['status' => 400, 'message' => "Email is invalid."], 400);
                }

                $token = $user->createToken('Android')->accessToken;

                $user->device_id = $request->device_id;
                $user->save();

                if ($user->avatar) {
                    $user_avatar = Storage::url($user->avatar);
                } else {
                    $user_avatar = "http://via.placeholder.com/50X50";
                }

                return response()->json(['status' => 200,
                    'message' => "Authenticated Successfully.",
                    'token' => $token,
                    'user_type' => $user_type,
                    'profile' => $user,
                    'avatar' => $user_avatar], 200);
            } else {
                return response()->json(['status' => 400, 'message' => "Email is invalid."], 400);
            }
        } else {
            if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
                $user = Auth::user();

                // if(Auth::user()->hasRole('student')==true && $user->signup_payment_verified==0)
                // {

                //     $role = Role::where('name','student')->get();
                //     // create transaction log
                //     $new_transaction = new PaymentTransaction;
                //     $new_transaction->type_of_transaction = 1;
                //     $new_transaction->user_id = $user->id;
                //     $new_transaction->user_type = $role[0]->id;
                //     $new_transaction->amount = 250*100;
                //     $new_transaction->save();

                //     return response()->json(['status'=>202,'message'=>"Your signUp fee is due.","user_data"=>$user,'fee'=>250,'parakh_transaction_id'=>$new_transaction->id], 400);

                // }else

                if ($user->mobile_verified == 0) {
                    $otp_details = $this->sendOtp($user, false);
                    $user->OTP = $otp_details['OTP'];
                    $user->save();

                    return response()->json(['status' => 201, 'message' => "Mobile is not verified. We just send a verification code on your registered mobile, please verify your mobile.", "user_id" => $user->id], 400);

                } elseif ($user->email_verified == 0) {
                    return response()->json(['status' => 203, 'message' => "Your email is not verified, please check your email for verification link."], 400);
                } elseif ($user->status == 0) {
                    return response()->json(['status' => 203, 'message' => "Your account is blocked or not activated. Try after sometime."], 400);
                }

                if (Auth::user()->hasRole('student')) {
                    $user_type = 1;
                } elseif (Auth::user()->hasRole('teacher')) {
                    $user_type = 2;
                } elseif (Auth::user()->hasRole('organisation')) {
                    $user_type = 3;
                } else {
                    return response()->json(['status' => 400, 'message' => "Email or password is invalid."], 400);
                }

                $token = $user->createToken('Android')->accessToken;

                $user->device_id = $request->device_id;
                $user->save();

                if ($user->avatar) {
                    $user_avatar = Storage::url($user->avatar);
                } else {
                    $user_avatar = "http://via.placeholder.com/50X50";
                }

                return response()->json(['status' => 200,
                    'message' => "Authenticated Successfully.",
                    'token' => $token,
                    'user_type' => $user_type,
                    'profile' => $user,
                    'avatar' => $user_avatar], 200);
            } else {
                return response()->json(['status' => 400, 'message' => "Email or password is invalid."], 400);
            }
        }

    }

    /**
     * Upgrade Student
     *
     * @return \Illuminate\Http\Response
     */
    public function upgradeStudent()
    {
        $user = Auth::user();
        if (Auth::user()->hasRole('student') == true && $user->signup_payment_verified == 0) {

            $role = Role::where('name', 'student')->get();
            $new_transaction = new PaymentTransaction;
            $new_transaction->type_of_transaction = 1;
            $new_transaction->user_id = $user->id;
            $new_transaction->user_type = $role[0]->id;
            $new_transaction->amount = 250 * 100;
            $new_transaction->save();

            return response()->json(['status' => true, 'message' => "Upgrade to Paid Student.", "user_data" => $user, 'fee' => 250, 'parakh_transaction_id' => $new_transaction->id]);

        } else {
            return response()->json(['status' => false, 'message' => "Already Paid Student"]);
        }
    }

    /**
     * Send otp for free registeation.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function free_register_send_otp_mail(Request $request)
    {
        $user_detail = User::find($request->user_id);
        if ($user_detail->mobile_verified == 0) {
            $otp_details = $this->sendOtp($user_detail, false);
            $user_detail->OTP = $otp_details['OTP'];
            $user_detail->save();
        }
        if ($user_detail->email_verified == 0) {
            $this->sendWelcomeMail($user_detail);
        }
        return response()->json(['status' => true, 'message' => "sent successfully."]);
    }
    // api authentication things

    /**
     * Display user listing for android.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = User::with('roles')->get();

        return view('users.list', ['data' => $data]);
    }
    /**
     * display form of Create user.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('display_name', 'id')->all();
        return view('users.create', ['roles' => $roles]);
    }
    /**
     * Store new created user data.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required|confirmed',
            'phone' => 'required',
            'role_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('users/create')
                ->withErrors($validator)
                ->withInput();
        } else {
            $data = new User;
            $data->name = $request->name;
            $data->email = $request->email;
            $data->phone = $request->phone;
            $data->password = bcrypt($request->password);
            $data->dob = null;
            $data->save();
            if ($data->id) {
                // get role
                $user_now = User::findOrFail($data->id);
                foreach ($request->role_id as $rkey => $rvalue) {
                    $role = Role::findOrFail($rvalue);
                    $user_now->attachRole($role);
                }
            }
        }
        return redirect('users')->with('success', 'User Added Successfully');
    }
    /**
     * display edit form of users.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $roles = Role::pluck('display_name', 'id')->all();
        $data = User::find(Crypt::decrypt($id));
        $rdata = User::find(Crypt::decrypt($id))->roles->pluck('id')->toArray();

        return view('users.edit', ['data' => $data, 'roles' => $roles, 'rdata' => $rdata]);
    }
    /**
     * Update User's data.
     *
     * @param \$id
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'role_id' => 'required',

        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $data = User::find(Crypt::decrypt($id));
            $data->name = $request->name;
            $data->email = $request->email;
            $data->phone = $request->phone;
            $data->status = 0;
            $data->email_verified = 0;
            $data->mobile_verified = 0;
            $data->save();

            return redirect('users')->with('success', 'User Updated Successfully');
        }
    }
    /**
     * Change Teacher status.
     *
     * @param $teacher
     * @param $status
     * @return \Illuminate\Http\Response
     */
    public function change_teacher_status($teacher_user_id, $status)
    {
        User::where('id', Crypt::decrypt($teacher_user_id))
            ->update(['status' => $status]);

        return redirect()->back()->with('success', 'Status updated Successfully');
    }
    /**
     * Change Organisation status.
     *
     * @param $org_user
     * @param $status
     * @return \Illuminate\Http\Response
     */
    public function change_org_status($org_user_id, $status)
    {
        User::where('id', Crypt::decrypt($org_user_id))
            ->update(['status' => $status]);

        return redirect()->back()->with('success', 'Status updated Successfully');
    }

    /**
     * Registration.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    { // dd($request->all());
        $messages = [
            'org_name.required' => 'The :attribute field is required.',
            // 'org_pan.required' => 'The :attribute field is required.',
            //'org_bank.required' => 'The :attribute field is required.',
            // 'org_ac.required' => 'The :attribute field is required.',
            // 'org_pphoto.required' => 'The :attribute field is required.',
            'email.unique' => 'Email is Already in use, Please Try With Other Email.',
        ];
        $validator = Validator::make($request->all(), [
            'who' => 'required',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'required|digits:10',
            //'aadhar' => 'required_if:who,2|required_if:who,3',
            // 'org_name' => 'required_if:who,3',
            // 'org_pan' => 'required_if:who,3',
            //'org_bank' => 'required_if:who,3',
            // 'org_ac' => 'numeric|required_if:who,3',
            //    'org_ifsc' => 'required_if:who,3',
        ], $messages);

        if ($validator->fails()) {
            //dd($validator->errors());
            return response()->json(['ok' => false, 'errors' => "Registration information is invaild."], 422);

        } else {

            $validator1 = Validator::make($request->all(), [
                'email' => 'unique:users',
            ], $messages);

            if ($validator1->fails()) {
                return response()->json(['ok' => false, 'errors' => "Registration Email is already used. Please try with another Email"], 422);
            }

            $user = User::create([
                'name' => $request->name,
                'last_name' => $request->last_name,
                'gender' => $request->gender,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'phone' => $request->phone,
            ]);

            if ($request->is_social == 1) {
                User::where('id', $user->id)->update(['email_verified' => 1]);
            } else {
                $this->sendWelcomeMail($user);
            }
            switch ($request->who) {
                case 1:
                    {
                        $role = Role::where('name', 'student')->first();
                        $user->attachRole($role);

                        $new_student = new Student;
                        $new_student->user_id = $user->id;
                        $new_student->save();
                        if ($request->mobile_verified == 1) {
                            User::where('id', $user->id)->update(['mobile_verified' => 1, 'status' => 1]);
                        } else {
                            $otp_details = $this->sendOtp($user, false);
                            $user->OTP = $otp_details['OTP'];
                            $user->save();
                        }
                        //
                        // // // call email

                        $role = Role::where('name', 'student')->get();
                        // create transaction log
                        $new_transaction = new PaymentTransaction;
                        $new_transaction->type_of_transaction = 1;
                        $new_transaction->user_id = $user->id;
                        $new_transaction->user_type = $role[0]->id;
                        $new_transaction->amount = 250 * 100;
                        $new_transaction->save();

                        return response()->json(['ok' => true, 'user_id' => $user->id, 'sign_up_fee' => 250, 'parakh_transaction_id' => $new_transaction->id], $this->successStatus);

                        break;
                    }
                case 2:
                    {
                        $role = Role::where('name', 'teacher')->first();
                        $user->attachRole($role);
                        $new_teacher = new Teacher;
                        $new_teacher->user_id = $user->id;
                        $new_teacher->bank = $request->bank_name;
                        $new_teacher->bank_ac = $request->bank_ac_number;
                        $new_teacher->bank_ifsc = $request->bank_ifsc;
                        $new_teacher->bank_pphoto = $request->file_name;
                        //$new_teacher->aadhar = $request->aadhar;

                        $new_teacher->save();
                        if ($request->mobile_verified == 1) {
                            User::where('id', $user->id)->update(['mobile_verified' => 1, 'status' => 1]);
                        } else {
                            $otp_details = $this->sendOtp($user, false);
                            $user->OTP = $otp_details['OTP'];
                            $user->save();
                        }
                        //  $this->sendWelcomeMail($user);
                        return response()->json(['ok' => true, 'user_id' => $user->id], $this->successStatus);
                        break;
                    }
                case 3:
                    {
                        //return ($request->org_pphoto->store('test'));
                        $role = Role::where('name', 'organisation')->first();
                        $user->attachRole($role);

                        $new_org = new Organisation;
                        $new_org->user_id = $user->id;
                        // $new_org->aadhar = $request->aadhar;
                        $new_org->org_name = $request->org_name;
                        $new_org->org_pan = $request->org_pan;
                        $new_org->org_bank = $request->org_bank;
                        $new_org->org_bank_ac = $request->org_ac;
                        $new_org->org_bank_ifsc = $request->org_ifsc;
                        $new_org->org_bank_pphoto = $request->file_name;
                        $new_org->commision_rate = 0;
                        $new_org->save();
                        if ($request->mobile_verified == 1) {
                            User::where('id', $user->id)->update(['mobile_verified' => 1, 'status' => 1]);
                        } else {
                            $otp_details = $this->sendOtp($user, false);
                            $user->OTP = $otp_details['OTP'];
                            $user->save();
                        }
                        // $this->sendWelcomeMail($user);
                        return response()->json(['ok' => true, 'user_id' => $user->id], $this->successStatus);

                        break;
                    }
                case 4:
                    {
                        $role = Role::where('name', 'agent')->first();
                        $user->attachRole($role);

                        $agent = new Agent;
                        $agent->user_id = $user->id;
                        $agent->current_credit_score = 0;
                        $agent->per_id_rate = 0;
                        $agent->save();
                        if ($request->mobile_verified == 1) {
                            User::where('id', $user->id)->update(['mobile_verified' => 1, 'status' => 1]);
                        } else {
                            $this->sendOtp($user);
                            // call email
                        }
                        //  $this->sendWelcomeMail($user);

                        if ($request->wantsJson()) {
                            return Crypt::encrypt($user->id);
                        } else {
                            return redirect('registration/mobile/confirmation/' . Crypt::encrypt($user->id));
                        }

                        break;
                    }

                default:
                    # code...
                    break;
            }

            //return response()->json(['id' => 11], $this->successStatus);
        }
    }
    /**
     * Api forgot passoword.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function api_forgot_password(Request $request)
    {

        $user = User::where('email', $request->email)->get();

        if (count($user) == 0) {
            return response()->json(['status' => 404, 'message' => "User not found."], 404);
        }
        $user = $user[0];

        $otp = rand(100000, 999999);
        $sms89 = new SMS89;
        $msg91Response = $sms89->sendSMS([$user->phone], "Your one time password (OTP) for reset password of PARAKH account is " . $otp . " which is valid till next 10 minutes.");
        if ($msg91Response['error']) {
            return response()->json(['status' => 500, 'message' => "Internal error."], 500);
        }
        $user->OTP = $otp;
        $user->save();

        return response()->json(['status' => 200, 'message' => "OTP sent successfully on your resgistered mobile."], 200);
    }
    /**
     * Password rest otp conformation.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function confirm_reset_password_otp(Request $request)
    {
        $user = User::where('email', $request->email)->get();

        if (count($user) == 0) {
            return response()->json(['status' => 404, 'message' => "User not found."], 404);
        }

        $user = $user[0];

        if ($request->otp != $user->OTP) {
            return response()->json(['status' => 404, 'message' => "OTP not matched."], 404);
        }

        $user->OTP = null;
        $user->save();
        Auth::loginUsingId($user->id);
        $user = Auth::user();
        $token = $user->createToken('Android')->accessToken;

        return response()->json(['status' => 200, 'token' => $token, 'message' => "OTP matched successfully."], 200);

    }

    /**
     * Reset password.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function reset_password(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => "Password didn't match or length must be in minimum."], 422);
        }

        $user = User::where('email', $request->email)->get();

        if (count($user) == 0) {
            return response()->json(['status' => 404, 'message' => "User not found."], 404);
        }

        $user = $user[0];
        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json(['status' => 200, 'message' => "Password reset successfully."], 200);
    }

    /**
     * Api Logout account.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function api_logout(Request $request)
    {

        if (Auth::user()) {
            try {
                $user = Auth::user();
                $user->device_id = null;
                $user->save();

                $accessToken = Auth::user()->token();
                DB::table('oauth_refresh_tokens')
                    ->where('access_token_id', $accessToken->id)
                    ->update([
                        'revoked' => true,
                    ]);
                $accessToken->revoke();
            } catch (Exception $e) {
                return response()->json(['status' => 200, 'message' => "You are logged out successfully."], 200);
            }
        }

        return response()->json(['status' => 200, 'message' => "You are logged out successfully."], 200);
    }
    /**
     * notificaitons.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function notification(Request $request)
    {
        if ($request->t_code) {
            $type = explode('#', $request->t_code);
            $title = '';
            if ($type[0] == 'QT' || $type[0] == 'ET' || $type[0] == 'OT') {
                switch ($type[0]) {
                    case 'QT':{
                            $search_result = QuickTest::where('code', $type[1])->first();
                            if ($search_result) {
                                $subject = Subject::where('id', $search_result->subject_id)->first();
                                $title = $subject->name;
                            }
                        }
                    case 'ET':{
                            $search_result = Test::where('code', $type[1])->first();
                            if ($search_result) {
                                $subject = Subject::where('id', $search_result->subject_id)->first();
                                $title = $subject->name;
                            }
                        }
                    case 'OT':{
                            $search_result = OrganisationTest::where('code', $type[1])->first();
                            if ($search_result) {
                                $subject = Subject::where('id', $search_result->subject_id)->first();
                                $title = $subject->name;
                            }
                        }
                    default:{

                        }
                }

            } else {
                $title = 'New';
            }
            //dd($title);

            $noti = array();
            $unread = 0;
            if (!empty($request->room)) {
                ////get room students
                $list = Room_student::whereIn('teacher_room_id', $request->room)->where('status', 1)->with('student_details')->get();
                //dd($list);
                if (count($list) > 0) {
                    foreach ($list as $row) {
                        if (Auth::user()->gender == 1) {
                            $end = ' Madam';
                        } else if (Auth::user()->gender == 2) {
                            $end = ' Sir';
                        } else {
                            $end = ' Ji';
                        }

                        //$unread[]=Notification::where('user_id',$row->student_details->id)->where('read_status','N')->count();
                        $noti[] = array(
                            'user_id' => $row->student_details->id,
                            'sender_id' => Auth::Id(),
                            'title' => 'Quick Test',
                            'test_id' => Crypt::decrypt($request->t_id),
                            'test_code' => $request->t_code,
                            'msg' => Auth::user()->name . ' ' . Auth::user()->last_name . $end . ' created a New Test  (' . $request->t_code . ') ' . date('d M H:i'),
                            'device_id' => $row->student_details->device_id,

                        );
                    }
                    //dd($noti);

                } else {
                    return redirect()->back()->with('error', 'No Student Avilable in Selected Rooms');
                }
            } else {
                return redirect()->back()->with('error', 'No Room Selected, Please Select Room');
            }
            if ($this->send_notification($noti, $unread)) {
                return redirect()->back()->with('success', 'Notification Send Successfully');
            } else {
                return redirect()->back()->with('error', 'No Student Avilable in Selected Rooms');
            }

        }

    }

    /**
     * Api Notifications.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function notification_api(Request $request)
    {
        if ($request->t_code) {
            $rooms = $request->room;
            $noti = array();
            $unread = 0;
            if (!empty($rooms)) {
                ////get room students
                //DB::enableQueryLog();
                $list = Room_student::whereIn('teacher_room_id', (explode(',', $rooms)))->where('status', 1)->with('student_details')->get();
                //dd($list,array($rooms),DB::getQueryLog());
                if (count($list) > 0) {
                    foreach ($list as $row) {
                        if (Auth::user()->gender == 1) {
                            $end = ' Madam';
                        } else if (Auth::user()->gender == 2) {
                            $end = ' Sir';
                        } else {
                            $end = ' Ji';
                        }

                        //$unread[]=Notification::where('user_id',$row->student_details->id)->where('read_status','N')->count();
                        $noti[] = array(
                            'user_id' => $row->student_details->id,
                            'sender_id' => Auth::Id(),
                            'title' => 'Quick Test',
                            'test_id' => $request->t_id,
                            'test_code' => $request->t_code,
                            'msg' => Auth::user()->name . ' ' . Auth::user()->last_name . $end . ' created a New Test  (' . $request->t_code . ') ' . date('d M H:i'),
                            'device_id' => $row->student_details->device_id,

                        );

                    }
                    // dd($noti);

                } else {
                    return response()->json(['status' => 400, 'message' => "No Student Avilable in Selected Rooms"], 400);
                }
            } else {
                return response()->json(['status' => 400, 'message' => "No Room Selected, Please Select Room"], 400);

            }
            if ($this->send_notification($noti, $unread)) {
                return response()->json(['status' => 200, 'message' => "Notification Send Successfully"], 200);
            } else {
                return response()->json(['status' => 400, 'message' => "No Student Avilable in Selected Rooms"], 400);
            }

        }

    }
    /**
     * regisatration next step.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function reg_next_step(Request $request)
    {
        //$request->session()->get('name')
        $request->session()->put('reg_email', $request->reg_email);
        $request->session()->put('reg_type', $request->reg_type);
        //dd($request->session()->get('reg_type'));
    }

    /**
     * first login.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function first_login_T_O(Request $request)
    {

        $data = User::select('msg')->where('id', $request->id)->first();
        if ($data) {
            if ($data->msg == 'N') {
                User::where('id', $request->id)->update(['msg' => 'Y']);
                return response()->json(['status' => true]);
            } else {return response()->json(['status' => false]);}

        }

    }
}
