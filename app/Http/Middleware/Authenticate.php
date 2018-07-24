<?php

namespace REBELinBLUE\Deployer\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Routing\Redirector;
use Symfony\Component\HttpFoundation\Response;

/**
 * Authentication middleware.
 */
class Authenticate
{
    /**
     * @var Redirector
     */
    private $redirector;

    /**
     * @var ResponseFactory
     */
    private $response;

    /**
     * @var AuthFactory
     */
    private $auth;

    /**
     * @param Redirector      $redirector
     * @param ResponseFactory $response
     * @param AuthFactory     $auth
     */
    public function __construct(Redirector $redirector, ResponseFactory $response, AuthFactory $auth)
    {
        $this->redirector = $redirector;
        $this->response   = $response;
        $this->auth       = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param Closure                  $next
     * @param string|null              $guard
     *
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if ($this->auth->guard($guard)->guest()) {
            if ($request->ajax()) {
                return $this->response->make('Unauthorized.', Response::HTTP_UNAUTHORIZED);
            }

            return $this->redirector->guest('login');
        }

        return $next($request);
    }
}
