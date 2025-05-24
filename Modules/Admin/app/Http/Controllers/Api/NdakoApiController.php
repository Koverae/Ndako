<?php

namespace Modules\Admin\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company\Company;
use App\Models\Team\Team;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
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

        // Create a new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'company' => $request->company,
            'phone' => $request->phone,
        ]);

        $team = Team::create([
            'user_id' => $user->id
        ]);

        // Generate a unique APP key
        $appKey = 'ndako_' . Str::random(32);

        // Create Ndako APP key record
        $ndakoAppKey = NdakoAppKey::create([
            'user_id' => $user->id,
            'app_key' => $appKey,
        ]);

        // Send email with APP key and user information
        try {

            // Send email
            Mail::to($user->email)->send(new NdakoAppKeyMail($user, $appKey));
        } catch (Exception $e) {
            // Log the error (in a real app, use Laravel's logging)
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to send email, but account created',
                'user_id' => $user->id,
                'app_key' => $appKey,
            ], 500);
        }

        // Return success response
        return response()->json([
            'status' => 'success',
            'message' => 'User registered, APP key generated, and email sent',
            'user_id' => $user->id,
            'app_key' => $appKey,
        ], 201);
    }

    /**
     * Handle the download of the Ndako on-premise zip file.
     * Validates the APP key and serves the file.
     *
     * @param Request $request
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
        $filePath = config('app.ndako_zip_path');

        // Check if the file exists
        if (!Storage::exists($filePath)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Zip file not found',
            ], 404);
        }

        // Return the file as a download response
        return Storage::download($filePath, 'ndako-on-premise.zip');
    }
}
