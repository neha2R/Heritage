<?php

namespace App\Http\Controllers;

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
    public function login(Request $request)
    {

        if ($request->is_social == 1) {
            $user = User::where('email', '=', request('email'))->first();

            if (Auth::loginUsingId($user->id)) {
                $user = Auth::user();

                if ($user->profile_complete == 0) {
                    return response()->json(['status' => 203, 'message' => "Your profile is not completed", 'data' => ''], 400);
                }

                $token = $user->createToken('Android')->accessToken;

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
                    'profile' => $user,
                    'avatar' => $user_avatar], 200);
            } else {
                return response()->json(['status' => 400, 'message' => "Email is invalid."], 400);
            }
        } else {
            if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
                $user = Auth::user();

                if ($user->profile_complete == 0) {
                    return response()->json(['status' => 203, 'message' => "Your profile is not completed", 'data' => ''], 400);
                }

                $token = $user->createToken('Android')->accessToken;

                return response()->json(['status' => 200,
                    'message' => "Authenticated Successfully.",
                    'token' => $token,
                    'profile' => $user], 200);
            } else {
                return response()->json(['status' => 400, 'message' => "Email or password is invalid."], 400);
            }
        }

    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'name' => 'required',
            'dob' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'data' => '', 'message' => $validator->errors()]);
        }

        $age = date_diff(date_create($request->dob), date_create('today'))->y;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'dob' => date('Y-m-d', strtotime($request->dob)),
            'password' => bcrypt($request->password),
            'mobile' => $request->mobile,
            'type' => '2',
            'age' => $age,
        ]);

        if ($request->is_social == 1) {
            User::where('id', $user->id)->update(['is_social' => '1', 'email_verified_at' => date('Y-m-d H:i:s')]);
        }

        $user = $user->toArray();

        return response()->json(['status' => 200, 'message' => 'Status succesfully saved', 'data' => $user]);

    }

}
