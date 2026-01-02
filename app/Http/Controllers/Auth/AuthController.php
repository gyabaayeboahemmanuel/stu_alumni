<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Helpers\Fuction;
use App\Models\Alumni;
use App\Models\SISIntegration;
use App\Models\User;
use App\Models\Role;
use App\Notifications\AlumniRegistered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // Show SIS-based registration form (main registration page)
    public function register()
    {
        return view('auth.register');
    }

    // Show manual registration form
    public function showManualRegistration()
    {
        return view('auth.register-manual');
    }

    // AJAX: Verify SIS and return data
    public function verifySIS(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            // Log validation failures
            Log::channel('sis')->warning('SIS Verification Validation Failed', [
                'student_id' => $request->student_id,
                'errors' => $validator->errors()->toArray(),
                'ip' => $request->ip()
            ]);

            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Check if user already exists
            $existingUser = User::where('student_id', $request->student_id)->first();
            if ($existingUser) {
                // Log duplicate registration attempt
                Log::channel('sis')->notice('Duplicate Registration Attempt', [
                    'student_id' => $request->student_id,
                    'existing_user_id' => $existingUser->id,
                    'existing_email' => $existingUser->email,
                    'ip' => $request->ip()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'An account with this student ID already exists. Please login instead.'
                ], 409);
            }

            // Log verification request
            Log::channel('sis')->info('Initiating SIS Verification', [
                'student_id' => $request->student_id,
                'timestamp' => now()->toDateTimeString(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            // Call school system API using the helper function - EXACT same as IRMTS
            // IRMTS returns the raw response directly: return response()->json($result);
            $apiResponse = Fuction::pullData($request->student_id, 'student');
            
            // Log the full API response received
            Log::channel('sis')->info('School API Response Received', [
                'student_id' => $request->student_id,
                'response_type' => gettype($apiResponse),
                'is_array' => is_array($apiResponse),
                'is_zero' => ($apiResponse === 0),
                'full_response' => $apiResponse,
                'response_keys' => is_array($apiResponse) ? array_keys($apiResponse) : null,
            ]);
            
            // Check if response is valid (not 0 or false)
            if ($apiResponse && $apiResponse !== 0) {
                // Check if request reached school server
                // desc: "request success" means request hit the school server
                $reachedSchoolServer = isset($apiResponse['desc']) && $apiResponse['desc'] === 'request success';
                
                // IMPORTANT: Return the EXACT same structure as IRMTS
                // IRMTS returns: response()->json($result) where $result is the raw API response
                // So we should return the raw response, but we need to handle it for the frontend
                
                // Check status to determine if student was found
                if (isset($apiResponse['status']) && $apiResponse['status'] == 200) {
                    // Student found - log success
                    try {
                        SISIntegration::create([
                            'student_id' => $request->student_id,
                            'request_data' => $request->all(),
                            'response_data' => $apiResponse['detail'] ?? $apiResponse,
                            'status' => 'success',
                            'verified_at' => now(),
                        ]);
                    } catch (\Exception $dbError) {
                        Log::channel('sis')->error('Failed to log SIS integration', [
                            'error' => $dbError->getMessage(),
                            'student_id' => $request->student_id,
                        ]);
                    }

                    // Return response in IRMTS format but with success wrapper for frontend
                    return response()->json([
                        'success' => true,
                        'data' => $apiResponse, // Return full response like IRMTS
                        'from_school_server' => $reachedSchoolServer,
                    ]);
                } else {
                    // Student not found or error
                    $errorMessage = 'Student ID or Phone Number not found in school system.';
                    
                    if (!$reachedSchoolServer) {
                        $errorMessage = 'Unable to connect to school server. Please try again later or use alternative registration.';
                    } elseif (isset($apiResponse['detail']['state'])) {
                        $errorMessage = $apiResponse['detail']['state'];
                    } elseif (isset($apiResponse['message'])) {
                        $errorMessage = $apiResponse['message'];
                    }
                    
                    try {
                        SISIntegration::create([
                            'student_id' => $request->student_id,
                            'request_data' => $request->all(),
                            'response_data' => $apiResponse,
                            'status' => 'failed',
                            'verified_at' => null,
                            'error_message' => $errorMessage,
                        ]);
                    } catch (\Exception $dbError) {
                        Log::channel('sis')->error('Failed to log SIS integration', [
                            'error' => $dbError->getMessage(),
                            'student_id' => $request->student_id,
                        ]);
                    }

                    // Return response in IRMTS format
                    return response()->json([
                        'success' => false,
                        'message' => $errorMessage,
                        'data' => $apiResponse, // Return full response like IRMTS
                        'from_school_server' => $reachedSchoolServer,
                    ], 404);
                }
            } else {
                // No response or error (0 returned)
                // Log failed SIS integration (wrap in try-catch to prevent table errors)
                try {
                    SISIntegration::create([
                        'student_id' => $request->student_id,
                        'request_data' => $request->all(),
                        'response_data' => ['error' => 'No response from school system'],
                        'status' => 'failed',
                        'verified_at' => null,
                        'error_message' => 'No response from school system',
                    ]);
                } catch (\Exception $dbError) {
                    Log::channel('sis')->error('Failed to log SIS integration', [
                        'error' => $dbError->getMessage(),
                        'student_id' => $request->student_id,
                    ]);
                }

                // Log verification failure
                Log::channel('sis')->warning('SIS Verification Failed - No Response or Invalid Response', [
                    'student_id' => $request->student_id,
                    'ip_address' => $request->ip(),
                    'response_type' => gettype($apiResponse),
                    'response_value' => $apiResponse,
                    'is_zero' => ($apiResponse === 0),
                    'is_false' => ($apiResponse === false),
                    'is_null' => ($apiResponse === null),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Student ID not found in school system. Please use alternative registration or check your details.'
                ], 404);
            }
        } catch (\Exception $e) {
            // Log unexpected errors
            Log::channel('sis')->error('SIS Verification Unexpected Error', [
                'student_id' => $request->student_id,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'ip_address' => $request->ip()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'SIS service temporarily unavailable. Please try alternative registration.'
            ], 503);
        }
    }

    // Complete SIS registration (AJAX)
    public function completeSISRegistration(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|string|max:20|unique:users,student_id',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:15',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'other_names' => 'nullable|string|max:100',
            'programme' => 'required|string|max:200',
            'graduation_year' => 'required|integer|min:1990|max:' . (date('Y') + 1),
            'qualification' => 'required|string|max:100',
            'agree_terms' => 'required|accepted',
        ], [
            'agree_terms.accepted' => 'You must agree to the terms and conditions.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Generate a secure random password
            $autoGeneratedPassword = Str::random(12);

            // Create user
            $user = User::create([
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($autoGeneratedPassword),
                'student_id' => $request->student_id,
                'role_id' => Role::where('name', Role::ALUMNI)->first()->id,
                'email_verified_at' => now(), // Auto-verify for SIS registration
            ]);

            // Create alumni record
            $alumni = Alumni::create([
                'user_id' => $user->id,
                'student_id' => $request->student_id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'other_names' => $request->other_names,
                'email' => $request->email,
                'phone' => $request->phone,
                'year_of_completion' => $request->graduation_year, // Map graduation_year to year_of_completion
                'programme' => $request->programme,
                'qualification' => $request->qualification,
                'verification_status' => 'verified',
                'verification_source' => 'sis',
                'verified_at' => now(),
                'registration_method' => 'sis',
                'sis_data' => $request->sis_data ? json_encode($request->sis_data) : null,
            ]);

            // Log successful registration
            Log::channel('sis')->info('SIS Registration Completed', [
                'user_id' => $user->id,
                'student_id' => $request->student_id,
                'email' => $request->email,
                'registration_method' => 'sis',
                'ip_address' => $request->ip()
            ]);

            // Send notification with auto-generated password
            Notification::send($user, new AlumniRegistered($alumni, 'sis', $autoGeneratedPassword));

            // Log the user in
            Auth::login($user);

            return response()->json([
                'success' => true,
                'message' => 'Registration completed successfully! A secure password has been generated and sent to your email.',
                'redirect' => route('alumni.dashboard')
            ]);

        } catch (\Exception $e) {
            Log::channel('sis')->error('SIS Registration Error', [
                'student_id' => $request->student_id,
                'error_message' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Registration failed. Please try again.'
            ], 500);
        }
    }

    // Process manual registration (AJAX)
    public function processManualRegistration(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'other_names' => 'nullable|string|max:100',
            'email' => 'required|email|unique:users,email',
            'student_id' => 'nullable|string|max:20|unique:users,student_id',
            'phone' => 'required|string|max:15',
            'graduation_year' => 'required|integer|min:1968|max:2013',
            'programme' => 'required|string|max:200',
            'agree_terms' => 'required|accepted',
        ], [
            'agree_terms.accepted' => 'You must agree to the terms and conditions.',
            'graduation_year.max' => 'Manual registration is only available for alumni who graduated in 2013 or earlier. The school system started in 2014, so graduates from 2014 onwards must use SIS verification.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Generate student ID if not provided
            $studentId = $request->student_id ?? 'MANUAL_' . Str::random(8);

            // Generate a secure random password
            $autoGeneratedPassword = Str::random(12);

            // Create user
            $user = User::create([
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($autoGeneratedPassword),
                'student_id' => $studentId,
                'role_id' => Role::where('name', Role::ALUMNI)->first()->id,
                'email_verified_at' => now(), // Auto-verify for manual too
            ]);

            // Create alumni record
            $alumni = Alumni::create([
                'user_id' => $user->id,
                'student_id' => $studentId,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'other_names' => $request->other_names,
                'email' => $request->email,
                'phone' => $request->phone,
                'year_of_completion' => $request->graduation_year, // Map graduation_year to year_of_completion
                'programme' => $request->programme,
                'verification_status' => 'pending',
                'verification_source' => 'manual',
                'registration_method' => 'manual',
            ]);

            // Log manual registration
            Log::channel('sis')->info('Manual Registration Completed', [
                'user_id' => $user->id,
                'student_id' => $studentId,
                'email' => $request->email,
                'registration_method' => 'manual',
                'verification_status' => 'pending',
                'ip_address' => $request->ip()
            ]);

            // Send notification with auto-generated password
            Notification::send($user, new AlumniRegistered($alumni, 'manual', $autoGeneratedPassword));

            // Log the user in
            Auth::login($user);

            return response()->json([
                'success' => true,
                'message' => 'Registration completed successfully! A secure password has been generated and sent to your email.',
                'redirect' => route('alumni.dashboard')
            ]);

        } catch (\Exception $e) {
            Log::channel('sis')->error('Manual Registration Error', [
                'student_id' => $request->student_id,
                'error_message' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Registration failed. Please try again.'
            ], 500);
        }
    }

    // Show login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Process login (AJAX compatible)
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'identifier' => 'required|string|max:255',
            'password' => 'required|string',
        ], [
            'identifier.required' => 'Please enter your email, phone number, or student ID.',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->onlyInput('identifier');
        }

        // Find user by email, phone, or student_id
        $user = User::findForAuth($request->identifier);

        if (!$user) {
            $errorResponse = [
                'identifier' => 'The provided credentials do not match our records.',
            ];

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $errorResponse
                ], 401);
            }

            return back()->withErrors($errorResponse)->onlyInput('identifier');
        }

        // Check if user is active
        if (!$user->is_active) {
            $errorResponse = [
                'identifier' => 'Your account has been deactivated. Please contact support.',
            ];

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $errorResponse
                ], 401);
            }

            return back()->withErrors($errorResponse)->onlyInput('identifier');
        }

        // Attempt authentication with the found user's email
        $credentials = [
            'email' => $user->email,
            'password' => $request->password
        ];
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Update last login
            Auth::user()->update(['last_login_at' => now()]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Login successful!',
                    'redirect' => Auth::user()->isAdmin() ? route('admin.dashboard') : route('alumni.dashboard')
                ]);
            }

            // Redirect based on role
            if (Auth::user()->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended(route('alumni.dashboard'));
        }

        $errorResponse = [
            'identifier' => 'The provided credentials do not match our records.',
        ];

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'errors' => $errorResponse
            ], 401);
        }

        return back()->withErrors($errorResponse)->onlyInput('identifier');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully.',
                'redirect' => '/'
            ]);
        }

        return redirect('/');
    }

    // Get registration form partial (for AJAX loading)
    public function getRegistrationForm(Request $request)
    {
        $method = $request->get('method', 'sis');
        
        if (!in_array($method, ['sis', 'manual'])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid registration method'
            ], 400);
        }

        $view = view('auth.partials.registration-form', compact('method'))->render();
        
        return response()->json([
            'success' => true,
            'html' => $view
        ]);
    }
}