<?php
namespace Minhbang\AccessControl\Controllers;

use Minhbang\AccessControl\Models\RoleGroup;
use Minhbang\LaravelKit\Extensions\BackendController;
use Minhbang\AccessControl\Requests\RoleGroupRequest;

/**
 * Class RoleGroupController
 *
 * @package Minhbang\AccessControl\Controllers
 */
class RoleGroupController extends BackendController
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $role_group = new RoleGroup();
        $url = route('backend.role_group.store');
        $method = 'post';
        return view('access-control::role_group.form', compact('url', 'method', 'role_group'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Minhbang\AccessControl\Requests\RoleGroupRequest $request
     *
     * @return \Illuminate\View\View
     */
    public function store(RoleGroupRequest $request)
    {
        $role_group = new RoleGroup();
        $role_group->fill($request->all());
        $role_group->save();
        return view(
            '_modal_script',
            [
                'message'    => [
                    'type'    => 'success',
                    'content' => trans('common.create_object_success', ['name' => trans('access-control::role_group.role_group')]),
                ],
                'reloadPage' => true,
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \Minhbang\AccessControl\Models\RoleGroup $role_group
     *
     * @return \Illuminate\View\View
     */
    public function edit(RoleGroup $role_group)
    {
        $url = route('backend.role_group.update', ['role_group' => $role_group->id]);
        $method = 'put';
        return view('access-control::role_group.form', compact('url', 'method', 'role_group'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Minhbang\AccessControl\Requests\RoleGroupRequest $request
     * @param \Minhbang\AccessControl\Models\RoleGroup $role_group
     *
     * @return \Illuminate\View\View
     */
    public function update(RoleGroupRequest $request, RoleGroup $role_group)
    {
        $role_group->fill($request->all());
        $role_group->save();
        return view(
            '_modal_script',
            [
                'message'    => [
                    'type'    => 'success',
                    'content' => trans('common.update_object_success', ['name' => trans('access-control::role_group.role_group')]),
                ],
                'reloadPage' => true,
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Minhbang\AccessControl\Models\RoleGroup $role_group
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function destroy(RoleGroup $role_group)
    {
        $role_group->delete();
        return response()->json(
            [
                'type'    => 'success',
                'content' => trans('common.delete_object_success', ['name' => trans('access-control::role_group.role_group')]),
            ]
        );
    }
}
