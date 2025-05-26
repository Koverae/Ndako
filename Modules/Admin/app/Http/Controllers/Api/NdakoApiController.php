<?php

namespace Modules\Admin\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company\Company;
use App\Models\Team\Team;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Modules\Admin\Emails\NdakoAppKeyMail;
use Modules\Admin\Models\NdakoAppKey;
use Modules\App\Emails\Template;

class NdakoApiController extends Controller
{
    /**
     * Handle the form submission to create a user, generate an APP key, and send an email.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'company' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Check if user already exists
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            // Create new user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make("Ndako")
            ]);

            // Create associated team
            $team = Team::create([
                'user_id' => $user->id
            ]);
        }

        // Check if app key already exists
        $ndakoAppKey = NdakoAppKey::where('user_id', $user->id)->first();

        if (!$ndakoAppKey) {
            $appKey = 'ndako_' . Str::random(32);

            $ndakoAppKey = NdakoAppKey::create([
                'user_id' => $user->id,
                'app_key' => $appKey,
            ]);

            // Send email with APP key and user info
            try {
                Mail::to($user->email)->send(new NdakoAppKeyMail($user, $appKey));
            } catch (Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to send email, but user/app key exists or was created',
                    'user_id' => $user->id,
                    'app_key' => $ndakoAppKey->app_key,
                ], 500);
            }
        }

        // Return success response
        return response()->json([
            'status' => 'success',
            'message' => '🎉 Success! Your account is verified and the Ndako App Key has been securely delivered to your email.',
            'user_id' => $user->id,
            'app_key' => $ndakoAppKey->app_key,
        ], 200);
    }


    /**
     * Handle fallback downloads using the APP key.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function download(Request $request)
    {
        // Validate the APP key
        $validator = Validator::make($request->all(), [
            'app_key' => 'required|string|exists:ndako_app_keys,app_key',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid or missing APP key',
                'errors' => $validator->errors(),
            ], 401);
        }

        // Get the zip file path from config
        $relativePath = config('app.ndako_zip_path', 'private/ndako-on-premise.zip');
        $absolutePath = storage_path('app/' . $relativePath);

        // Log the path for debugging
        Log::info('Attempting to access zip file at: ' . $absolutePath);

        // Check if the file exists
        if (!file_exists($absolutePath)) {
            Log::error('Zip file not found at: ' . $absolutePath);
            return response()->json([
                'status' => 'error',
                'message' => 'Zip file not found at: ' . $relativePath,
            ], 404);
        }

        // Return the zip file as a download response
        return response()->download($absolutePath, 'ndako-on-premise-v1.zip');
    }

    /**
     * Verify a Ndako App Key and return user information.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkNdakoApp(Request $request)
    {
        // Validate the APP key
        $validator = Validator::make($request->all(), [
            'app_key' => 'required|string|exists:ndako_app_keys,app_key',
        ]);

        if ($validator->fails()) {
            Log::warning('Invalid APP key verification attempt: ' . json_encode($request->all()));
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid or missing APP key',
                'errors' => $validator->errors(),
            ], 401);
        }

        // Find the APP key record with its associated user
        $ndakoAppKey = NdakoAppKey::where('app_key', $request->app_key)
            ->with('user')
            ->first();

        // Check if the APP key is active
        if ($ndakoAppKey->status !== 'active') {
            Log::warning('Inactive APP key attempted: ' . $request->app_key);
            return response()->json([
                'status' => 'error',
                'message' => 'APP key is inactive',
            ], 403);
        }

        // Log successful verification
        Log::info('APP key verified successfully: ' . $request->app_key . ' for user ID ' . $ndakoAppKey->user_id);

        // Return success response with user details
        return response()->json([
            'status' => 'success',
            'message' => 'Ndako App Key verified successfully',
            'data' => [
                'app_key' => $ndakoAppKey->app_key,
                'status' => $ndakoAppKey->status,
                'user' => [
                    'id' => $ndakoAppKey->user->id,
                    'name' => $ndakoAppKey->user->name,
                    'email' => $ndakoAppKey->user->email,
                    'company' => $ndakoAppKey->user->company,
                    'phone' => $ndakoAppKey->user->phone,
                ],
            ],
        ], 200);
    }
    
}
