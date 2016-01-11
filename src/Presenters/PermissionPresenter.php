<?php
namespace Minhbang\AccessControl\Presenters;

use Laracasts\Presenter\Presenter;

/**
 * Class PermissionPresenter
 *
 * @package Minhbang\AccessControl\Presenters
 */
class PermissionPresenter extends Presenter
{
    /**
     * @return string
     */
    public function action()
    {
        return app('access-control')->getResourceActions($this->entity->resource, $this->entity->action);
    }

    /**
     * @return string
     */
    public function resource()
    {
        $title = app('access-control')->getResourceTitles($this->entity->resource);
        return "<strong>{$this->entity->resource}</strong> | <span class=\"text-info\">{$title}</span>";
    }
}