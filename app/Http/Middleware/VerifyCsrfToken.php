<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Session\TokenMismatchException;
class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
     public function handle($request, Closure $next) {
        try {
            return parent::handle($request, $next);
        } catch (TokenMismatchException $ex) {
            return Response()->json(['success'=>false,'type'=>'error','message'=>'Please reload the page and try again!!!'],419);
        }
    }
    protected $except = [
        //
    ];
}
