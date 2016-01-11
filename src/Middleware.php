<?php
namespace Minhbang\AccessControl;

use Closure;
use Illuminate\Routing\Route;

/**
 * Class Middleware
 *
 * @package Minhbang\AccessControl
 */
class Middleware
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string|null $resource
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $resource = null)
    {
        $model = $resource ? $request->route($resource) : null;
        return $next($request);
    }
}