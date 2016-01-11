<?php
namespace Minhbang\AccessControl\Traits\Resource;

use Minhbang\AccessControl\Support\Statuses\Resource;

/**
 * Class DefaultStatuses
 *
 * @package Minhbang\AccessControl\Traits\Resource
 */
trait DefaultStatuses
{
    /**
     * All statuses
     *
     * @return array
     */
    public function statuses()
    {
        return [
            Resource::STATUS_PROCESSING => trans('access-control::status.processing'),
            Resource::STATUS_REFUSED    => trans('access-control::status.refused'),
            Resource::STATUS_PUBLISHED  => trans('access-control::status.published'),
        ];
    }

    /**
     * @return array
     */
    public function statusCss()
    {
        return [
            Resource::STATUS_PROCESSING => 'default',
            Resource::STATUS_REFUSED    => 'danger',
            Resource::STATUS_PUBLISHED  => 'primary',
        ];
    }
}