<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use App\Models\Usertype;
use App\Http\Traits\HttpResponses;
use Auth;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Foundation\Application;

class AuthController extends Controller
{

    use HttpResponses;
    /**
     * Handle an incoming login request.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function login(Request $request)
    {
        try {
            // Validate the incoming request data
            $request->validate([
                'email' => "required|exists:users,email",
                'password' => 'required'
            ]);

            // Find the user by email
            $user = User::where('email', $request->email)->first();

            // Check if the user exists and the password is correct
            if (!$user || !Hash::check($request->password, $user->password)) {
                return $this->error('Incorrect credentials', '', 401); // Use a 401 Unauthorized status code for failed authentication
            }

            $user_type = UserType::where('usertype_id', $user->usertype_id)->firstOrFail()->usertype_name;
            $company = Company::where('company_id', $user->company_id)->firstOrFail()->name;

            // Log the user for debugging purposes
            Log::info('User authenticated successfully', [$user]);

            // Create a new internal request for the password grant.
            $tokenRequest = Request::create('/oauth/token', 'POST', [
                'grant_type' => 'password',
                'client_id' => env('CLIENT_ID'),
                'client_secret' => env('CLIENT_SECRET'),
                'username' => $request->email,
                'password' => $request->password,
                'scope' => '',
            ]);

            // Dispatch the internal request through the application's pipeline to the token endpoint.
            // This is the correct way to trigger the token generation internally without a network call.
            $tokenResponse = resolve(Application::class)->handle($tokenRequest);
            $tokens = json_decode($tokenResponse->getContent(), true);

            $response_user = [
                'name' => $user->name,
                'email' => $user->email,
                'epf_number' => $user->epf_number,
                'designation' => $user->designation,
                'user_type' => $user_type,
                'company' => $company,
            ];
            // Check if the token was successfully issued
            if (isset($tokens['access_token'])) {
                return $this->success([
                    'user' => $response_user,
                    'token_type' => $tokens['token_type'],
                    'access_token' => $tokens['access_token'],
                    'expires_in' => $tokens['expires_in'],
                    'refresh_token' => $tokens['refresh_token'],
                ]);
            }

            // Handle failed token request
            return $this->error('Could not authenticate with Passport', '', 500);
        } catch (ValidationException $e) {
            return $this->error($e->errors(), '', 422);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), '', 500);
        }
    }

    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'epf_number' => 'required|string|max:20',
                'usertype_name' => 'required|string',
                'designation' => 'required|string|max:255',
                'company_name' => 'required|string|max:255',
                'date_of_append' => 'required',
                'date_of_birth' => 'required'
            ]);


            $user_type_id = Usertype::where('usertype_name', $request->usertype_name)->firstOrFail()->usertype_id;
            $company_id = Company::where('name', $request->company_name)->firstOrFail()->company_id;

            $user = [
                "name" => $request->name,
                "email" => $request->email,
                "password" => Hash::make($request->password),
                "epf_number" => $request->epf_number,
                "usertype_id" => $user_type_id,
                "designation" => $request->designation,
                "company_id" => $company_id,
                "date_of_append" => $request->date_of_append,
                "date_of_birth" => $request->date_of_birth
            ];

            $result = User::create($user);

            return [
                'user' => $result
            ];
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    // Refresh Token function
    public function refreshToken(Request $request)
    {

        try {

            $request->validate([
                'refresh_token' => 'required',
            ]);
            $tokenRequest = Request::create('/oauth/token', 'POST', [
                'grant_type' => 'refresh_token',
                'refresh_token' => $request->refresh_token,
                'client_id' => env('CLIENT_ID'),
                'client_secret' => env('CLIENT_SECRET'),
                'scope' => '',
            ]);
            $tokenResponse = resolve(Application::class)->handle($tokenRequest);
            $tokens = json_decode($tokenResponse->getContent(), true);
            //  dd($tokens->json());
            if (isset($tokens['access_token'])) {
                return response()->json([
                    'token_type' => $tokens['token_type'],
                    'access_token' => $tokens['access_token'],
                    'expires_in' => $tokens['expires_in'],
                    'refresh_token' => $tokens['refresh_token'],
                ]);
            }

            // Handle the case where the token refresh fails
            return response()->json([
                'error' => 'Failed to refresh token.',
                'details' => $tokens,
            ], 401);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function userProfile(Request $request)
    {
        try {

            $user = User::where('email', $request->email)->firstOrFail()->name;

            return response()->json([

                'name' => $user
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Summary of logout
     * @param \Illuminate\Http\Request $request
     */
    public function logout(Request $request)
    {
        try {
            $token = $request->user()->token();

            // Revoke the token
            $result = $token->revoke();

            if ($result) {
                // Use a proper success response format
                return $this->success([]);
            }
            if ($result) {
                return $this->success([]);
            }
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
