<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Laravel\Socialite\Facades\Socialite;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Validation\Rules\Password;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web')->except([
            'login', 'do_login', 
            'register', 'do_register', 
            'verify', 
            'socialRedirect', 'socialCallback'
        ]);
    }

    public function login()
    {
        if (Auth::check()) {
            return redirect()->route('courses_list');
        }
        return view('auth.login');
    }

    public function do_login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            if (!$user->email_verified_at) {
                Auth::logout();
                return redirect()->back()->withErrors('Email not verified.');
            }
            return redirect()->route('courses_list')->with('success', 'Logged in successfully!');
        }

        return redirect()->back()->withErrors('Invalid email or password.');
    }

    public function register()
    {
        if (Auth::check()) {
            return redirect()->route('courses_list');
        }
        return view('auth.register');
    }

    public function do_register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)->letters()->mixedCase()->numbers()->symbols()
            ]
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assign the default student role
        $user->assignRole('student');

        // Generate email verification token
        $token = Crypt::encryptString(json_encode([
            'id' => $user->id,
            'email' => $user->email
        ]));

        $verificationLink = route("verify", ['token' => $token]);

        // In a real app we would send mail. For the course, we'll display the link or print it, 
        // and also simulate sending it or saving it in session so the user can easily click it in the UI.
        return redirect()->route('login')->with('success', 'Registration successful! For demonstration, click this verification link to verify: ' . $verificationLink)->with('demo_verify_link', $verificationLink);
    }

    public function verify(Request $request)
    {
        if (!$request->token) {
            return redirect()->route('login')->withErrors('Invalid token.');
        }

        try {
            $decrypted = json_decode(Crypt::decryptString($request->token), true);
            $user = User::findOrFail($decrypted['id']);

            if ($user->email === $decrypted['email']) {
                $user->email_verified_at = Carbon::now();
                $user->save();

                Auth::login($user);
                return redirect()->route('courses_list')->with('success', 'Email verified successfully! You are now logged in.');
            }
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors('Email verification failed: ' . $e->getMessage());
        }

        return redirect()->route('login')->withErrors('Email verification failed.');
    }

    public function do_logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Logged out successfully!');
    }

    // Role and user management (Course Admins only)
    public function list()
    {
        if (!auth()->user()->hasRole('course_admin')) {
            abort(403, 'Only admins can view user roles.');
        }

        $users = User::with('roles')->get();
        $roles = Role::all();
        return view('auth.users_roles', compact('users', 'roles'));
    }

    public function save(Request $request)
    {
        if (!auth()->user()->hasRole('course_admin')) {
            abort(403, 'Only admins can modify roles.');
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'roles' => 'required|array',
        ]);

        $user = User::findOrFail($request->user_id);
        
        // Synchronize roles
        $user->syncRoles($request->roles);

        // Clear permissions cache
        Artisan::call('cache:clear');

        return redirect()->back()->with('success', 'Roles updated successfully for ' . $user->name);
    }

    // Password change feature
    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)->letters()->mixedCase()->numbers()->symbols()
            ]
        ]);

        $user = Auth::user();

        if (!Hash::check($request->old_password, $user->password)) {
            return redirect()->back()->withErrors('Current password does not match.');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->back()->with('success', 'Password updated successfully!');
    }

    // Social Login (Dynamic Provider: google, linkedin, facebook, microsoft)
    public function socialRedirect($provider)
    {
        if (!in_array($provider, ['google', 'linkedin', 'facebook', 'microsoft'])) {
            abort(404);
        }
        return Socialite::driver($provider)->redirect();
    }

    public function socialCallback($provider)
    {
        if (!in_array($provider, ['google', 'linkedin', 'facebook', 'microsoft'])) {
            abort(404);
        }
        try {
            $socialUser = Socialite::driver($provider)->user();
            
            $user = User::updateOrCreate(
                ['email' => $socialUser->email],
                [
                    'name' => $socialUser->name,
                    'email_verified_at' => Carbon::now(),
                    'password' => Hash::make(\Illuminate\Support\Str::random(16))
                ]
            );

            // If Google login, also populate Google fields
            if ($provider === 'google') {
                $user->update([
                    'google_id' => $socialUser->id,
                    'google_token' => $socialUser->token,
                    'google_refresh_token' => $socialUser->refreshToken ?? null,
                ]);
            }

            if (!$user->hasAnyRole(Role::all())) {
                $user->assignRole('student');
            }

            Auth::login($user);
            return redirect()->route('courses_list')->with('success', 'Authenticated via ' . ucfirst($provider) . '!');
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(ucfirst($provider) . ' Login failed: ' . $e->getMessage());
        }
    }
}
