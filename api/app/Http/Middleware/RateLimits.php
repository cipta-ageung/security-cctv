<?php
     
namespace App\Http\Middleware;
    
use Closure;
use Illuminate\Routing\Middleware\ThrottleRequests;
class RateLimits extends ThrottleRequests
{
    protected function resolveRequestSignature($request)
    {
        return sha1(implode('|', [
                    $request->method(),
                    $request->root(),
                    $request->path(),
                    $request->ip(),
                    $request->query('access_agent')
                ]
            ));
    
        return $request->fingerprint();
    }
      
}