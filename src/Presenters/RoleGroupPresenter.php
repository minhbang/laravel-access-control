<?php
namespace Minhbang\AccessControl\Presenters;

use Laracasts\Presenter\Presenter;

/**
 * Class RoleGroupPresenter
 *
 * @package Minhbang\AccessControl\Presenters
 */
class RoleGroupPresenter extends Presenter
{
    /**
     * @return string
     */
    public function name_block()
    {
        $url = route('backend.role.group', ['group' => $this->entity->id]);
        $actions = "<div class='actions'>{$this->actions()}<div>";
        return "<a href=\"{$url}\">{$this->entity->name}</a>{$actions}";
    }

    /**
     *
     * @return string
     */
    public function actions()
    {
        $name = trans('access-control::role_group.role_group');
        if ($this->entity->name == 'System') {
            $edit = '<a href="#" class="btn btn-info btn-xs disabled"><span class="glyphicon glyphicon-edit"></span></a>';
            $delete = '<a href="#" class="btn btn-danger btn-xs disabled"><span class="glyphicon glyphicon-trash"></span></a>';
        } else {
            $edit = '<a href="' . route('backend.role_group.edit', ['role_group' => $this->entity->id]) . '"
               data-toggle="tooltip"
               class="modal-link btn btn-info btn-xs"
               data-title="' . trans('common.update_object', ['name' => $name]) . '"
               data-label="' . trans('common.save_changes') . '"
               data-width="small"
               data-icon="fa-sitemap"><span class="glyphicon glyphicon-edit"></span>
            </a>';
            $delete = '<a href="' . route('backend.role_group.destroy', ['role_group' => $this->entity->id]) . '"
                data-toggle="tooltip"
                data-title="' . trans('common.delete_object', ['name' => $name]) . '"
                data-item_name="' . $name . '"
                data-item_title="' . $this->entity->name . '"
                class="delete_group btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span>
            </a>';
        }

        return $edit . $delete;
    }
}