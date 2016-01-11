<?php
namespace Minhbang\AccessControl\Traits\Resource;

/**
 * Class HasPolicy
 * Trait cho Resource Model: Article, Document,...
 *
 * @package Minhbang\AccessControl\Traits\Resource
 */
trait HasPolicy
{
    /**
     * policy instance
     *
     * @var mixed
     */
    protected $policy_instance;

    /**
     * Prepare a new or cached policy instance
     *
     * @return mixed
     * @throws PresenterException
     */
    public function policy()
    {
        if (!$this->policy_class or !class_exists($this->policy_class)) {
            throw new PresenterException('Please set the $policy_class property to your policy class path.');
        }

        if (!$this->policy_instance) {
            $this->policy_instance = new $this->policy_class($this);
        }

        return $this->policy_instance;
    }
}
