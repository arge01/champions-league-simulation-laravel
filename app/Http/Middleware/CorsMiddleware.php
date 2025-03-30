<?php

namespace App\Http\Middleware;

use Closure;

class CorsMiddleware
{
	public function handle($request, Closure $next)
	{
		$allowedOrigins = [
			'http://localhost:3000',
			'http://127.0.0.1:3000',
			'http://localhost:8000',
			'http://127.0.0.1:8000',
			'*'
		];

		$origin = $request->headers->get('Origin');

		$response = $request->isMethod('OPTIONS')
			? response()->json([], 200)
			: $next($request);

		$response->headers->set('Access-Control-Allow-Origin', $origin ?: '*');
		$response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS');
		$response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
		$response->headers->set('Access-Control-Allow-Credentials', 'true');

		return $response;
	}
}
