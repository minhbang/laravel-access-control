<?php
namespace Minhbang\AccessControl\Exceptions;
/**
 * Class RoleDeniedException
 *
 * @package Minhbang\AccessControl\Exceptions
 */
class RoleDeniedException extends AccessDeniedException
{
    public function __construct($permission)
    {
        $this->message = sprintf("You don't have a required ['%s'] role.", $permission);
    }
}