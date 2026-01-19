<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Illuminate\Support\Facades\Session;
use Kreait\Firebase\Exception\Auth\InvalidPassword;
use Kreait\Firebase\Exception\Auth\UserNotFound;

class AuthController extends Controller
{
    protected $auth;
    protected $database;

    public function __construct()
    {
        $credentialsPath = env('FIREBASE_CREDENTIALS');

        if (!$credentialsPath || !file_exists($credentialsPath)) {
            throw new \Exception("Firebase configuration not found at: {$credentialsPath}");
        }

        $firebaseFactory = (new Factory())
            ->withServiceAccount($credentialsPath)
            ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));

        $this->auth = $firebaseFactory->createAuth();
        $this->database = $firebaseFactory->createDatabase();
    }

    // === LOGIN FORM
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // === LOGIN POST
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        try {
            $signInResult = $this->auth->signInWithEmailAndPassword(
                $request->email,
                $request->password
            );

            $firebaseUser = $signInResult->data();
            $uid = $firebaseUser['localId'];

            // Get role from Firebase
            $role = $this->database->getReference("users/{$uid}/role")->getValue() ?? 'user';

            // Save session
            Session::put('firebase_user', [
                'uid' => $uid,
                'email' => $firebaseUser['email'],
                'name' => $firebaseUser['displayName'] ?? '',
                'role' => $role,
            ]);

            // Redirect by role
            if ($role === 'admin') {
                // Existing admin can login directly
                return redirect()->route('admin.dashboard')->with('success', 'Welcome Admin!');
            }

            // Normal user
            return redirect()->route('dashboard')->with('success', 'Welcome User!');

        } catch (InvalidPassword $e) {
            return back()->withErrors(['password' => 'Invalid password.']);
        } catch (UserNotFound $e) {
            return back()->withErrors(['email' => 'User not found.']);
        } catch (\Exception $e) {
            return back()->withErrors(['general' => 'Login failed: ' . $e->getMessage()]);
        }
    }

    // === REGISTER FORM (only for users)
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // === REGISTER POST (only user role)
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        try {
            $userProperties = [
                'email' => $request->email,
                'emailVerified' => false,
                'password' => $request->password,
                'displayName' => $request->name,
                'disabled' => false,
            ];

            // Create Firebase user
            $createdUser = $this->auth->createUser($userProperties);

            // Save user info in Firebase (role always 'user')
            $this->database->getReference('users/' . $createdUser->uid)
                ->set([
                    'name' => $request->name,
                    'email' => $request->email,
                    'role' => 'user',
                    'created_at' => now()->toDateTimeString(),
                ]);

            return redirect()->route('login')->with('success', 'Account created successfully! You can now log in.');

        } catch (\Kreait\Firebase\Exception\Auth\EmailExists $e) {
            return back()->withErrors(['email' => 'Email already exists.']);
        } catch (\Exception $e) {
            return back()->withErrors(['general' => 'Registration failed: ' . $e->getMessage()]);
        }
    }

    // === LOGOUT
    public function logout()
    {
        Session::forget('firebase_user');
        return redirect()->route('login')->with('success', 'You have been logged out.');
    }

    // Show form to enter email for password reset
// Show reset password form
public function showResetPasswordForm()
{
    return view('auth.reset_password');
}

// Handle reset password submission
public function resetPassword(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'new_password' => 'required|min:6|confirmed', // password confirmation
    ]);

    try {
        // Get user by email
        $user = $this->auth->getUserByEmail($request->email);

        // Update password
        $this->auth->updateUser($user->uid, [
            'password' => $request->new_password
        ]);

        return redirect()->route('login')->with('success', 'Your password has been updated successfully!');

    } catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e) {
        return back()->withErrors(['email' => 'No user found with this email.']);
    } catch (\Exception $e) {
        return back()->withErrors(['general' => 'Failed to reset password: ' . $e->getMessage()]);
    }
}


}
