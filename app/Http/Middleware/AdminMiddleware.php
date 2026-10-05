<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        $user = $request->user();

        if (! $user) {
            return redirect()
                ->route('login')
                ->with('error', 'برای دسترسی به پنل مدیریت باید وارد شوید.');
        }

        if (! $user->is_active) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->with('error', 'این حساب غیرفعال شده است. دوباره وارد شوید.');
        }

        if (! $user->isAdmin()) {
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}
