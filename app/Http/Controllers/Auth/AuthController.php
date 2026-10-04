<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = [
            'email' => $request->validated('email'),
            'password' => $request->validated('password'),
            'is_active' => true,
        ];

        if (! Auth::attempt(
            $credentials,
            $request->validated('remember')
        )) {
            return back()
                ->withInput($request->only([
                    'email',
                    'remember',
                ]))
                ->withErrors([
                    'email' => 'ایمیل یا رمز عبور صحیح نیست.',
                ]);
        }

        $request->session()->regenerate();

        $user = $request->user();

        if ($user->isAdmin()) {
            return redirect()
                ->route('admin.dashboard')
                ->with('success', 'خوش آمدید.');
        }

        return redirect()
            ->route('home')
            ->with('success', 'خوش آمدید.');
    }
    public function register(
        RegisterRequest $request
    ): RedirectResponse {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => 'customer',
            'is_active' => true,
        ]);

        Auth::login($user);

        $request->session()->regenerate();

        return redirect()
            ->intended(route('home'))
            ->with('success', 'حساب کاربری با موفقیت ایجاد شد.');
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()
            ->route('home')
            ->with('success', 'با موفقیت خارج شدید.');
    }
}
