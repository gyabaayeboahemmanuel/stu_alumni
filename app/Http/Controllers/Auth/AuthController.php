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

            // Call SIS API using the helper function - hardcode entity to 'student'
            $apiResponse = Fuction::pullData($request->student_id, 'student');
            
            if ($apiResponse['success']) {
                $sisData = $apiResponse['data'];
                
                // Log successful SIS integration
                SISIntegration::create([
                    'student_id' => $request->student_id,
                    'request_data' => $request->all(),
                    'response_data' => $sisData,
                    'status' => 'success',
                    'verified_at' => now(),
                    'ip_address' => $request->ip(),
                ]);

                // Log successful verification
                Log::channel('sis')->info('SIS Verification Completed Successfully', [
                    'student_id' => $request->student_id,
                    'response_time' => $apiResponse['response_time'] ?? 'N/A',
                    'data_retrieved' => [
                        'has_name' => !empty($sisData['full_name']) || (!empty($sisData['first_name']) && !empty($sisData['last_name'])),
                        'has_email' => !empty($sisData['email']),
                        'has_programme' => !empty($sisData['programme']),
                        'has_graduation_year' => !empty($sisData['graduation_year'])
                    ]
                ]);

                return response()->json([
                    'success' => true,
                    'data' => $sisData,
                    'message' => 'SIS verification successful! Your information has been auto-populated.'
                ]);
            } else {
                // Log failed SIS integration
                SISIntegration::create([
                    'student_id' => $request->student_id,
                    'request_data' => $request->all(),
                    'response_data' => $apiResponse,
                    'status' => 'failed',
                    'verified_at' => null,
                    'ip_address' => $request->ip(),
                ]);

                // Log verification failure
                Log::channel('sis')->warning('SIS Verification Failed', [
                    'student_id' => $request->student_id,
                    'api_message' => $apiResponse['message'],
                    'response_time' => $apiResponse['response_time'] ?? 'N/A',
                    'ip_address' => $request->ip()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => $apiResponse['message'] ?? 'Student ID not found in SIS system. Please use alternative registration or check your details.'
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
                'graduation_year' => $request->graduation_year,
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
            'graduation_year' => 'required|integer|min:1968|max:' . (date('Y') + 1),
            'programme' => 'required|string|max:200',
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
            // Generate student ID if not provided
            $studentId = $request->student_id ?? 'MANUAL_' . Str::random(8);

            // Generate a secure random password
            $autoGeneratedPassword = Str::random(12);

            // Create user
            $user = User::create([
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
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
                'graduation_year' => $request->graduation_year,
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
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->onlyInput('email');
        }

        $credentials = $request->only('email', 'password');
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
            'email' => 'The provided credentials do not match our records.',
        ];

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'errors' => $errorResponse
            ], 401);
        }

        return back()->withErrors($errorResponse)->onlyInput('email');
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