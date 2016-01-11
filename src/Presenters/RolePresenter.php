<?php
namespace Minhbang\AccessControl\Presenters;

use Laracasts\Presenter\Presenter;

/**
 * Class RolePresenter
 *
 * @package Minhbang\AccessControl\Presenters
 */
class RolePresenter extends Presenter
{
    /**
     * @param bool $label
     *
     * @return string
     */
    public function link_edit($label = true)
    {
        $label = $label ? ' ' . trans('common.update') : '';
        return '<a href="' . route('backend.role.edit', ['role' => $this->entity->id]) . '"
           data-toggle="tooltip"
           class="modal-link btn btn-info btn-xs"
           data-title="' . trans('common.update_object', ['name' => trans('access-control::role.role')]) . '"
           data-label="' . trans('common.save_changes') . '"
           data-width="small"
           data-icon="align-justify"><span class="glyphicon glyphicon-edit"></span>' . $label . '
        </a>';
    }

    /**
     * @return string
     */
    public function actions()
    {
        $name = trans('access-control::role.role');
        $show = '<a href="' . route('backend.role.show', ['role' => $this->entity->id]) . '"
           data-toggle="tooltip"
           class="btn btn-success btn-xs"
           data-title="' . trans('common.object_details_view', ['name' => $name]) . '">
           <span class="glyphicon glyphicon-list"></span>
        </a>';
        $delete = '<a href="' . route('backend.role.destroy', ['role' => $this->entity->id]) . '"
            data-toggle="tooltip"
            data-title="' . trans('common.delete_object', ['name' => $name]) . '"
            data-item_name="' . $name . '"
            data-item_title="' . $this->entity->full_name . '"
            class="delete_role btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span>
        </a>';
        return $show . $this->link_edit(false) . $delete;
    }
}