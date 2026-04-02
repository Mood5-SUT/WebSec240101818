<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Borrow;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web')->except(['login', 'register', 'authenticate', 'saveRegistration']);
    }

    public function login()
    {
        if (Auth::check()) {
            return redirect()->route('books_list');
        }

        return view('auth.login');
    }

    public function register()
    {
        if (Auth::check()) {
            return redirect()->route('books_list');
        }

        return view('auth.register');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $throttleKey = $this->throttleKey($request);

        if (RateLimiter::tooManyAttempts($throttleKey, 2)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()->withErrors([
                'email' => 'Too many login attempts. Please try again after '.$seconds.' seconds.',
            ])->withInput();
        }

        $email = $request->email;
        $password = $request->password;

        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();

            return redirect()->route('books_list')->with('success', 'Login successful.');
        }

        RateLimiter::hit($throttleKey, 15);

        return back()->withErrors(['email' => 'Invalid email or password.'])->withInput();
    }

    protected function throttleKey(Request $request): string
    {
        return Str::lower($request->input('email')).'|'.$request->ip();
    }

    public function saveRegistration(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $user->assignRole('Member');

        Auth::attempt(['email' => $request->email, 'password' => $request->password]);
        $request->session()->regenerate();

        return redirect()->route('users_profile')->with('success', 'Registration successful. Your Member role has been assigned.');
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('users_login')->with('success', 'You have been logged out.');
    }

    public function list()
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user || !$user->hasPermissionTo('view_members')) {
            abort(403);
        }

        $members = User::role('Member')->orderBy('name')->get();

        return view('users.list', compact('members'));
    }

    public function editMember($id)
    {
        /** @var User|null $authUser */
        $authUser = Auth::user();

        if (!$authUser || !$authUser->hasPermissionTo('manage_users')) {
            abort(403);
        }

        $user = User::findOrFail($id);

        if (!$user->hasRole('Member')) {
            abort(403);
        }

        return view('users.member-password', compact('user'));
    }

    public function saveMemberPassword(Request $request, $id)
    {
        /** @var User|null $authUser */
        $authUser = Auth::user();

        if (!$authUser || !$authUser->hasPermissionTo('manage_users')) {
            abort(403);
        }

        $user = User::findOrFail($id);

        if (!$user->hasRole('Member')) {
            abort(403);
        }

        $request->validate([
            'password' => 'required|string|confirmed',
        ]);

        $user->password = bcrypt($request->password);
        $user->save();

        return redirect()->route('users_list')->with('success', 'Member password updated successfully.');
    }

    public function edit($id = null)
    {
        /** @var User|null $authUser */
        $authUser = Auth::user();

        if (!$authUser || !$authUser->hasPermissionTo('manage_users')) {
            abort(403);
        }

        $user = $id ? User::findOrFail($id) : new User();

        return view('users.edit', compact('user'));
    }

    public function save(Request $request, $id = null)
    {
        /** @var User|null $authUser */
        $authUser = Auth::user();

        if (!$authUser || !$authUser->hasPermissionTo('manage_users')) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.($id ?? 'NULL').',id',
            'password' => $id ? 'nullable|string|confirmed' : 'required|string|confirmed',
        ]);

        $user = $id ? User::findOrFail($id) : new User();
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();
        $user->syncRoles(['Librarian']);
        Artisan::call('cache:clear');

        return redirect()->route('users_edit', $user->id)->with('success', 'Librarian account saved successfully.');
    }

    public function delete($id)
    {
        /** @var User|null $authUser */
        $authUser = Auth::user();

        if (!$authUser || !$authUser->hasPermissionTo('manage_users')) {
            abort(403);
        }

        $user = User::findOrFail($id);

        if ($user->hasRole('Admin')) {
            return redirect()->route('users_list')->with('error', 'Admin accounts cannot be deleted here.');
        }

        $user->delete();

        return redirect()->route('users_list')->with('success', 'User deleted successfully.');
    }

    public function profile()
    {
        $activeBorrows = collect();
        $borrowingLimit = null;
        $remainingLimit = null;
        $borrowingStatus = null;

        /** @var User|null $user */
        $user = Auth::user();

        if ($user && $user->hasRole('Member')) {
            $activeBorrows = Borrow::with('book')
                ->where('user_id', $user->id)
                ->whereNull('returned_at')
                ->latest('borrowed_at')
                ->get();

            $borrowingLimit = 3;
            $remainingLimit = $borrowingLimit - $activeBorrows->count();
            $borrowingStatus = $remainingLimit > 0 ? 'Eligible to borrow books' : 'Borrowing limit reached';
        }

        return view('users.profile', compact('activeBorrows', 'borrowingLimit', 'remainingLimit', 'borrowingStatus'));
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required|string',
            'password' => 'required|string|confirmed',
        ]);

        /** @var User|null $user */
        $user = Auth::user();

        if (!$user || !Hash::check($request->old_password, $user->password)) {
            return back()->withErrors(['old_password' => 'The old password is incorrect.']);
        }

        $user->password = bcrypt($request->password);
        $user->save();

        return redirect()->route('users_profile')->with('success', 'Password changed successfully.');
    }
}
