<?php
namespace Minhbang\AccessControl\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Minhbang\AccessControl\Exceptions\RoleDeniedException;

/**
 * Class VerifyRole
 *
 * @package Minhbang\AccessControl\Middleware
 */
class VerifyRole
{
    /**
     * @var \Illuminate\Contracts\Auth\Guard
     */
    protected $auth;

    /**
     * VerifyRole constructor.
     *
     * @param \Illuminate\Contracts\Auth\Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param int|string $role
     *
     * @return mixed
     * @throws \Minhbang\AccessControl\Exceptions\RoleDeniedException
     */
    public function handle($request, Closure $next, $role)
    {
        if ($this->auth->check() && $this->auth->user()->is($role)) {
            return $next($request);
        }
        throw new RoleDeniedException($role);
    }
}