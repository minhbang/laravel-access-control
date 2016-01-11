<?php
namespace Minhbang\AccessControl\Policies;
/**
 * Class Policy
 *
 * @package Minhbang\AccessControl\Policies
 */
abstract class Policy
{

    /**
     * @var \Minhbang\LaravelKit\Extensions\Model|mixed
     */
    protected $entity;

    /**
     * @param $entity
     */
    public function __construct($entity)
    {
        $this->entity = $entity;
    }

    /**
     * Nhóm Administrator pass qua mọi policy
     *
     * @return bool
     */
    protected function forceTrue()
    {
        return user()->inAdminGroup();
    }

    /**
     * Nếu chưa định nghĩa policy cho action $name ==> TRUE
     *
     * @param string $name
     * @param array $parameters
     *
     * @return bool
     */
    public function __call($name, $parameters)
    {
        return method_exists($this, $name) ? call_user_func_array([$this, $name], $parameters) : true;
    }
}