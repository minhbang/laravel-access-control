<?php
namespace Minhbang\AccessControl\Controllers;

use Minhbang\AccessControl\Models\Permission;
use Minhbang\LaravelKit\Extensions\BackendController;

/**
 * Class PermissionController
 *
 * @package Minhbang\AccessControl\Controllers
 */
class PermissionController extends BackendController
{
    /**
     * Sync permissions với actions được 'định nghĩa' trong các resources model
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sync()
    {
        // actions định nghĩa trong các model
        $resource_actions = app('access-control')->getResourceActions();
        $model_actions = [];
        foreach ($resource_actions as $resource => $actions) {
            $model_actions = array_merge(
                $model_actions,
                array_map(function ($action) use ($resource) {
                    return "{$resource}.{$action}";
                }, array_keys($actions))
            );
        }
        // actions đã lưu trên DB (permissions table)
        $db_actions = array_map(
            function ($permission) {
                return "{$permission['resource']}.{$permission['action']}";
            },
            Permission::all()->toArray()
        );

        // có trong DB, nhưng không có trong model ===> remove
        $remove = array_diff($db_actions, $model_actions);
        foreach ($remove as $action) {
            list($resource, $action_name) = explode('.', $action);
            if ($permission = Permission::whereResource($resource)->whereAction($action_name)->first()) {
                // Permission model sẽ tự động xóa liên kết với roles
                $permission->delete();
            }
        }

        // có định nghĩa trong model, nhưng chưa lưu trong DB ===> insert
        $insert = array_diff($model_actions, $db_actions);
        if ($insert) {
            $permissions = array_map(function ($action) {
                list($resource, $action_name) = explode('.', $action);
                return ['resource' => $resource, 'action' => $action_name];
            }, $insert);
            Permission::insert($permissions);
        }

        return response()->json(
            [
                'type'    => 'success',
                'content' => trans('access-control::permission.sync_success'),
            ]
        );
    }
}
