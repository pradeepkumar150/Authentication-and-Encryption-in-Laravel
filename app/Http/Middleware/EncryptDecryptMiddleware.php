<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Symfony\Component\HttpFoundation\Response;

class EncryptDecryptMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Decrypt the request payload if it exists
        if ($request->isJson() && $request->getContent()) {
            try {
                $decryptedData = Crypt::decryptString($request->getContent());
                $request->merge(json_decode($decryptedData, true));
            } catch (\Exception $e) {
                return response()->json(['error' => 'Invalid encrypted data'], 400);
            }
        }

        return $next($request);
    }
    public function terminate($request, $response)
    {
        // Encrypt the response payload
        if ($response instanceof \Illuminate\Http\Response) {
            $responseData = $response->getContent();
            $encryptedData = Crypt::encryptString($responseData);
            $response->setContent($encryptedData);
            $response->headers->set('Content-Type', 'application/json');
        }
    }
}
