<?php
namespace Minhbang\AccessControl\Policies;
/**
 * Class RolePermissionPolicy
 * Các luật liên quan đến roles, permissions của user được gán về content
 *
 * @package Minhbang\AccessControl\Policies
 */
class PermissionPolicy extends Policy
{
    /**
     * Kiểm tra user() được phép thực hiện action $name đối với model $this->entity
     *
     * @param string $name
     * @param array $parameters
     *
     * @return bool
     */
    public function __call($name, $parameters)
    {
        return $this->forceTrue() ? true : user()->allowed($name, $this->entity);
    }

}