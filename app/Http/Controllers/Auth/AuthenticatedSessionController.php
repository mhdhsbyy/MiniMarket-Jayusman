<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        /** @var User $user */
        $user = Auth::user();

        $user->load('roles');

        if ($user->hasAnyRole(['owner'])) {
            return redirect()->route('owner.dashboard');
        }

        if ($user->hasAnyRole(['manager'])) {
            return redirect()->route('manager.dashboard');
        }

        if ($user->hasAnyRole(['supervisor'])) {
            return redirect()->route('supervisor.dashboard');
        }

        if ($user->hasAnyRole(['cashier'])) {
            return redirect()->route('cashier.dashboard');
        }

        if ($user->hasAnyRole(['warehouse'])) {
            return redirect()->route('warehouse.dashboard');
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        abort(403, 'Role user belum memiliki akses.');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
