<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'fullname' => 'required|string|max:255',
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users', 'regex:/^[a-zA-Z0-9._%+-]+@ssct\.edu\.ph$/i'],  // Only allow @ssct.edu.ph
                'studentid' => 'required|string|unique:users',
                'courseSection' => 'required|string|max:255',
                'gender' => 'required|in:Male,Female,Other',
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $user = User::create([
                'fullname' => $request->fullname,
                'email' => $request->email,
                'studentid' => $request->studentid,
                'courseSection' => $request->courseSection,
                'gender' => $request->gender,
                'password' => Hash::make($request->password),
                'phone_number' => $request->phone_number,
                'role' => 'user'
            ]);

            event(new Registered($user));

            Auth::login($user);

            return response()->json([
                'success' => true,
                'message' => 'Registration successful'
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => ['general' => 'An error occurred during registration.']
            ], 500);
        }
    }
}