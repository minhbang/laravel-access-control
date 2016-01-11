<?php
namespace Minhbang\AccessControl\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Minhbang\AccessControl\Exceptions\PermissionDeniedException;

/**
 * Class VerifyPermission
 *
 * @package Minhbang\AccessControl\Middleware
 */
class VerifyPermission
{
    /**
     * @var \Illuminate\Contracts\Auth\Guard
     */
    protected $auth;

    /**
     * VerifyPermission constructor.
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
     * @param int|string $permission
     *
     * @return mixed
     * @throws \Minhbang\AccessControl\Exceptions\PermissionDeniedException
     */
    public function handle($request, Closure $next, $permission)
    {
        if ($this->auth->check() && $this->auth->user()->can($permission)) {
            return $next($request);
        }
        throw new PermissionDeniedException($permission);
    }
}