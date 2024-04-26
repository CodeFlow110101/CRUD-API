<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserDetails;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function create(Request $request){

        $validator = Validator::make($request->json()->all(), [
            'firstname' => 'required|string|min:3',
            'lastname'=> 'required|string|min:3',
            'email' => 'required|unique:users|email',
            'password' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = [
            'firstname' =>  $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ];

        $user_details = new UserDetails(['address' => $request->address]);

        $user = User::create($data);
        $user->userdetails()->save($user_details);

        return response()->json(["status" => "User Successfully Created"]);
    }

    public function update(Request $request){
        $validator = Validator::make($request->json()->all(), [
            'firstname' => 'required|string|min:3',
            'lastname'=> 'required|string|min:3',
            'email' => 'required|exists:users|email',
            'password' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = [
            'firstname' =>  $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => $request->password,
        ];

        User::where('email',$request->email)->update(['firstname' =>  $request->firstname,'lastname' => $request->lastname,'password' => Hash::make($request->password)]);
        UserDetails::where('user_id',User::where('email',$request->email)->first()->id)->update(['address' => $request->address]);

        return response()->json(["status" => "User Successfully Updated"]);

    }

    public function index(){

        return response()->json(User::with('userdetails')->get()->toArray());
    
    }

    public function delete(Request $request){

        $validator = Validator::make($request->json()->all(), [
            'email' => 'required|exists:users|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('email',$request->email)->first();
        $user->userdetails()->delete();
        $user->delete();

        return response()->json(["status" => "User Successfully Deleted"]);

    }

}
