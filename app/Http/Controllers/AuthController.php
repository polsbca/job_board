<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Register a new user.
     */
    public function register(Request $request)
    {
        try {
            // Get raw JSON input
            $json = $request->getContent();
            \Log::info('Raw JSON received:', ['json' => $json]);
            
            // Parse JSON manually
            $data = json_decode($json, true);
            \Log::info('Parsed data:', ['data' => $data]);

            // Validate the request
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8'],
                'password_confirmation' => ['required', 'string', 'same:password'],
                'role' => ['required', 'in:employer,applicant'], // Admin registration should be handled separately
            ], [
                'password_confirmation.required' => 'The confirm password field is required.',
                'password_confirmation.same' => 'The password confirmation does not match.',
            ]);

            // Log validation success
            \Log::info('Registration validation passed:', [
                'validated_data' => $validated,
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
            ]);

            // Log user creation
            \Log::info('User created successfully:', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);

            // Create a new Sanctum token for the user
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'User registered successfully',
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer'
            ], 201);
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Registration failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Get validation errors if they exist
            $validationErrors = null;
            if ($e instanceof \Illuminate\Validation\ValidationException) {
                $validationErrors = $e->errors();
            }

            // Get detailed error information
            $errorDetails = [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'class' => get_class($e)
            ];

            // Get validation errors if they exist
            $validationErrors = null;
            if ($e instanceof \Illuminate\Validation\ValidationException) {
                $validationErrors = $e->errors();
            } elseif ($e instanceof \Illuminate\Http\JsonResponse) {
                $validationErrors = json_decode($e->getContent(), true);
            }

            // Log the error with detailed information
            \Log::error('Registration failed with detailed error:', [
                'error_details' => $errorDetails,
                'validation_errors' => $validationErrors,
                'request_payload' => $request->all(),
                'request_headers' => $request->headers->all()
            ]);

            return response()->json([
                'message' => 'Registration failed',
                'error' => $errorDetails,
                'validation_errors' => $validationErrors ?? null
            ], 422);
        }
    }

    /**
     * Login a user.
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean',
        ]);

        if (!Auth::attempt([
            'email' => $validated['email'],
            'password' => $validated['password'],
        ])) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    /**
     * Logout a user.
     */
    public function logout(Request $request)
    {
        // Revoke the token that was used to authenticate the current request
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout successful',
        ]);
    }
}
