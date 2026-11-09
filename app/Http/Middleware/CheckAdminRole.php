<?php
namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminRole
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check() || auth()->user()->role !== User::ROLE_ADMIN) {
            abort(403, 'Unauthorized Access: Administrator Rights Required.');
        }

        return $next($request);
    }
}
