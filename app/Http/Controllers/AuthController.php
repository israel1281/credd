<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\PasswordValidationRules;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use PasswordValidationRules;

    public function register(Request $request)
    {
        $attr = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            // 'phone' => 'required|string|size:11|unique:users,phone',
            'email' => 'required|string|email|unique:users,email|max:255',
            'password' => $this->passwordRules(),
            'terms' => 'required|boolean|in:1',
        ], [
            'terms.required' => 'You need to tick this box to proceed',
            'terms.in' => 'You need to tick this box to proceed',
            'password.confirmed' => 'Passwords do not match',
            'email.unique' => 'This email address is already registered',
            // 'phone.unique' => 'This phone number is already registered'
        ]);

        $user = User::create([
            'first_name' => $attr['first_name'],
            'last_name' => $attr['last_name'],
            // 'phone' => $attr['phone'],
            'password' => Hash::make($attr['password']),
            'email' => $attr['email'],
            'status_id' => status_pending_id(),
            'role_id' => role_user()
        ]);

        $user->sendEmailVerificationNotification();

        return apiSuccess([
            'token' => $user->createToken('Auth Token')->plainTextToken
        ], 'Registration successful! You can now login to your account.', 200);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:6',
            'device_name' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken($request->device_name)->plainTextToken;

        return apiSuccess([
            'token' => $token,
        ], 'Login successful!');
    // ], 'Login successful!')->cookie('laravel_token', "${token}", 120, '', '', false, true);
    }

    public function isAuthenticated() {
        return apiSuccess([
            'isAuthenticated' => auth()->user()
        ]);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Tokens revoked!'
        ];
    }
}
