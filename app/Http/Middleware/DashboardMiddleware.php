<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DashboardMiddleware
{
    /**
     * Redirect non-admin users away from the main dashboard to the employee dashboard.
     *
     * Owner and Super Admin → proceed to /dashboard
     * All other roles → redirect to /employee/dashboard
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        $adminRoles = config('roles.admin_roles', ['Owner', 'Super Admin']);

        if ($user->role && in_array($user->role->name, $adminRoles)) {
            return $next($request);
        }

        // Non-admin users are redirected to the employee dashboard
        return redirect()->route('employee.dashboard');
    }
}
