<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckBranchStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && $user->branch_id) {
            $user->load('branch');

            if ($user->branch && $user->branch->status === 'inactive') {
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->with('error', 'Cabang Anda telah dinonaktifkan, silakan hubungi owner.');
            }
        }

        return $next($request);
    }
}
