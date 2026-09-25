<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
class MemberMiddleware {
    public function handle(Request $request, Closure $next): Response {
        abort_unless($request->user()?->role === 'member' && $request->user()->client_id, 403);
        return $next($request);
    }
}
