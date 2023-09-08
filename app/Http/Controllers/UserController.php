<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       return User::latest()->paginate(10);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ], 400);
        } else {
            $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

            if ($user) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Register Successfully',
                    'data'=>$user,
                ], 200);
            } else {
                return response()->json([
                    'status' => 500,
                    'message' => 'Register Failed',
                ], 500);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ], 400);
        }
        else {
                $user->name=$request->name;
                $user->email=$request->email;
                $user->phone=$request->phone;
                $user->update();
            if ($user) {
                return response()->json([
                    'status' => 200,
                    'message' => 'User Update Successfully',
                    'data'=>$user,
                ], 200);
            } else {
                return response()->json([
                    'status' => 500,
                    'message' => 'User Update Failed',
                ], 500);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
            if ($user) {
                return response()->json([
                    'status' => 200,
                    'message' => 'User Deleted Successfully',
                    'data'=>$user,
                ], 200);
            } else {
                return response()->json([
                    'status' => 500,
                    'message' => 'User Delete Failed',
                ], 500);
            }
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ], 400);
        } 
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            return response()->json(['message' => 'Login successful', 'data' => $user]);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
    public function logout(Request $request)
    {
        $user = Auth::user();
        $user->token()->revoke(); // Revoke the user's access token

        return response()->json(['message' => 'Successfully logged out']);
    }
}
