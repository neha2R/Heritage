<?php

namespace App\Http\Controllers;

use App\Unverified;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public $successStatus = 200;
    // api authentication things

    /**
     * User login for android.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function generateRandomString($length = 10)
    {
        return substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
    }
    public function login(Request $request)
    {

        if ($request->is_social == 1) {
            $user = User::where('email', '=', request('email'))->first();

            if (Auth::loginUsingId($user->id)) {
                $user = Auth::user();

                if ($user->profile_complete == 0) {
                    return response()->json(['status' => 202, 'message' => "Your profile is not completed", 'data' => ''], 200);
                }

                $token = $user->createToken($user->email)->plainTextToken;
                // $token = $this->generateRandomString();
                // $user->app_id = $request->app_id;
                // $user->save();

                // if ($user->avatar) {
                //     $user_avatar = Storage::url($user->avatar);
                // } else {
                //     $user_avatar = "http://via.placeholder.com/50X50";
                // }

                return response()->json(['status' => 200,
                    'message' => "Authenticated Successfully.",
                    'token' => $token,
                    'data' => $user,
                    'profile_complete' => $user->profile_complete,
                    'avatar' => $user_avatar], 200);
            } else {

                return response()->json(['status' => 202, 'message' => "User not found.", 'data' => ''], 200);
            }
        } else {
            if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
                $user = Auth::user();
                $token = $user->createToken($user->email)->plainTextToken;

                if ($user->profile_complete == 0) {
                    return response()->json(['status' => 202, 'message' => "Your profile is not completed", 'profile_complete' => '0', 'token' => $token,
                        'data' => $user], 200);
                }

                return response()->json(['status' => 200,
                    'message' => "Authenticated Successfully.",
                    'token' => $token,
                    'profile_complete' => $user->profile_complete,
                    'data' => $user], 200);
            } else {
                return response()->json(['status' => 202, 'message' => "Email or password is invalid.", 'data' => ''], 200);
            }
        }

    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'email' => 'required|email|unique:users',
            // 'username' => 'required|unique:users',
            'first_name' => 'required',
            'dob' => 'required',
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'data' => '', 'message' => $validator->errors()]);
        }

        $age = date_diff(date_create($request->dob), date_create('today'))->y;

        $user = User::find($request->user_id);
        $user->name = $request->first_name . ' ' . $request->last_name;
        // $user->email = $request->email;
        $user->age = $age;
        $user->dob = date('Y-m-d', strtotime($request->dob));
        // $user->password = bcrypt($request->password);
        $user->mobile = $request->mobile;
        $user->type = '2';
        $user->state_id = $request->state_id;
        $user->city_id = $request->city_id;
        $user->profile_complete = '1';
        $user->subscribe_newslater = $request->newsletter;

        $user->save();
        // if ($request->is_social == 1) {
        //     User::where('id', $user->id)->update(['is_social' => '1', 'email_verified_at' => date('Y-m-d H:i:s')]);
        //     // $user->otp = '';
        // }
        // else {
        //     $user->otp = '9876';
        // }

        $user = $user->toArray();

        return response()->json(['status' => 200, 'message' => 'User updated successfully', 'data' => $user]);

    }

    public function stepone(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'username' => 'unique:users',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'data' => '', 'message' => $validator->errors()]);
        }
        $otp = rand(100000, 999999);
        $user = new Unverified;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->password = bcrypt($request->password);
        $user->otp = $otp;
        $user->save();

        $user = $user->toArray();

        return response()->json(['status' => 200, 'message' => 'Please verify email', 'data' => $otp]);

    }

    public function email_verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            // 'otp' => 'required',
            'is_social' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'data' => '', 'message' => $validator->errors()]);
        }
        if ($request->is_social == 0) {
            $user = Unverified::where('email', $request->email)->where('otp', $request->otp)->first();
            if (empty($user)) {
                return response()->json(['status' => 200, 'message' => "Otp not verified.", 'data' => ''], 200);
            } else {
                if ($user->otp != $request->otp) {
                    return response()->json(['status' => 200, 'message' => "Otp not verified.", 'data' => ''], 200);
                } else {
                    $userdata = new User;
                    $userdata->name = '';
                    $userdata->email = $user->email;
                    $userdata->password = $user->password;
                    $userdata->username = $user->username;
                    $userdata->dob = date('Y-m-d');
                    $userdata->email_verified_at = date('Y-m-d H:i:s');
                    $userdata->save();

                    $userdata = $userdata->toArray();
                }
            }
        } else {
            $userdata = new User;
            $userdata->name = '';
            $userdata->email = $request->email;
            $userdata->username = $request->email;
            $userdata->password = '';
            $userdata->dob = date('Y-m-d');
            $userdata->email_verified_at = date('Y-m-d H:i:s');
            $userdata->is_social = '1';
            $userdata->save();
            $userdata = $userdata->toArray();
        }
        return response()->json(['status' => 200, 'message' => 'Email verified succesfully', 'data' => $userdata]);
    }

    public function index()
    {
        $users = User::where('type', '2')->get();
        return view('users.list', compact('users'));
    }

}
