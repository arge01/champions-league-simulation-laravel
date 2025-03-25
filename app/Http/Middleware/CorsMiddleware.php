<?php

namespace App\Http\Middleware;

use Closure;

class CorsMiddleware
{
	public function handle($request, Closure $next)
	{
		$allowedOrigins = [
			'http://localhost:3000',
		];

		$origin = $request->headers->get('Origin');

		if (in_array($origin, $allowedOrigins)) {
			if ($request->isMethod('OPTIONS')) {
				$response = response('OK', 200);
			} else {
				$response = $next($request);
			}

			$response->header('Access-Control-Allow-Origin', $origin);
			$response->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
			$response->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
			$response->header('Access-Control-Allow-Credentials', 'true');
			return $response;
		}

		return response('Forbidden', 403);
	}
}
