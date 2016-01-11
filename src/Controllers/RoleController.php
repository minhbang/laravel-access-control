<?php
namespace Minhbang\AccessControl\Controllers;

use Minhbang\AccessControl\Models\Permission;
use Minhbang\LaravelUser\User;
use Minhbang\AccessControl\Models\Role;
use Minhbang\AccessControl\Models\RoleGroup;
use Minhbang\LaravelKit\Extensions\BackendController;
use Minhbang\AccessControl\Requests\RoleRequest;

class RoleController extends BackendController
{
    /**
     * @var \Minhbang\AccessControl\Models\RoleGroup role group hiện tại
     */
    protected $group;

    public function __construct()
    {
        parent::__construct();
        $this->switchGroup();
    }

    /**
     * @param null|int $group
     */
    protected function switchGroup($group = null)
    {
        $key = 'backend.access-control.role_group';
        $group = $group ?: session($key);
        if ($group) {
            $this->group = RoleGroup::find($group);
        }
        $this->group = $this->group ?: RoleGroup::firstOrCreate(['name' => 'System']);
        session([$key => $this->group->id]);
    }

    /**
     * @param int|null $group
     *
     * @return \Illuminate\View\View
     */
    public function index($group = null)
    {
        $this->switchGroup($group);
        $groups = RoleGroup::orderBy('id')->get();
        $roles = $this->group->roles()->orderBy('level')->with('users')->get();
        $current = $this->group;
        $this->buildHeading(
            [trans('access-control::role.manage'), "[{$this->group->name}]"],
            'fa-male',
            ['#' => trans('access-control::role.role')]
        );

        return view('access-control::role.index', compact('roles', 'groups', 'current'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $role = new Role();
        $url = route('backend.role.store');
        $method = 'post';

        return view('access-control::role.form', compact('url', 'method', 'role'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Minhbang\AccessControl\Requests\RoleRequest $request
     *
     * @return \Illuminate\View\View
     */
    public function store(RoleRequest $request)
    {
        $role = new Role();
        $role->fill($request->all());
        $role->group_id = $this->group->id;
        $role->save();

        return view(
            '_modal_script',
            [
                'message'    => [
                    'type'    => 'success',
                    'content' => trans('common.create_object_success', ['name' => trans('access-control::role.role')]),
                ],
                'reloadPage' => true,
            ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param \Minhbang\AccessControl\Models\Role $role
     *
     * @return \Illuminate\View\View
     */
    public function show(Role $role)
    {
        $this->buildHeading(
            [trans('access-control::role.view') . " [{$this->group->name}]:", $role->full_name],
            'list',
            [
                route('backend.role.index') => trans('access-control::role.roles'),
                '#'                         => trans('common.view_detail'),
            ]
        );

        // users đã gán $role
        $users = $role->users()->with('group')->get();
        // 10 user khác chưa gán $role
        $selectize_users = User::forSelectize($users->pluck('id')->all(), 10)->get()->all();
        $permissions = $role->permissions()->get();
        $other_permissions = Permission::except($permissions->pluck('id'))->get()->sortBy('resource')->groupBy('resource');
        $permissions = $permissions->sortBy('resource')->groupBy('resource');

        return view(
            'access-control::role.show',
            compact('role', 'users', 'selectize_users', 'permissions', 'other_permissions')
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \Minhbang\AccessControl\Models\Role $role
     *
     * @return \Illuminate\View\View
     */
    public function edit(Role $role)
    {
        $url = route('backend.role.update', ['role' => $role->id]);
        $method = 'put';

        return view('access-control::role.form', compact('url', 'method', 'role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Minhbang\AccessControl\Requests\RoleRequest $request
     * @param \Minhbang\AccessControl\Models\Role $role
     *
     * @return \Illuminate\View\View
     */
    public function update(RoleRequest $request, Role $role)
    {
        $role->fill($request->all());
        $role->save();

        return view(
            '_modal_script',
            [
                'message'    => [
                    'type'    => 'success',
                    'content' => trans('common.update_object_success', ['name' => trans('access-control::role.role')]),
                ],
                'reloadPage' => true,
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Minhbang\AccessControl\Models\Role $role
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return response()->json(
            [
                'type'    => 'success',
                'content' => trans('common.delete_object_success', ['name' => trans('access-control::role.role')]),
            ]
        );
    }

    /**
     * @param \Minhbang\AccessControl\Models\Role $role
     * @param \Minhbang\LaravelUser\User $user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function attachUser(Role $role, User $user)
    {
        if ($role->users()->wherePivot('user_id', $user->id)->count() <= 0) {
            $role->users()->attach($user->id);
        }

        return response()->json(
            [
                'type'    => 'success',
                'content' => trans('access-control::role.attach_user_success'),
            ]
        );
    }

    /**
     * @param \Minhbang\AccessControl\Models\Role $role
     * @param \Minhbang\LaravelUser\User $user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function detachUser(Role $role, User $user)
    {
        $role->users()->detach($user->id);

        return response()->json(
            [
                'type'    => 'success',
                'content' => trans('access-control::role.detach_user_success'),
            ]
        );
    }

    /**
     * @param \Minhbang\AccessControl\Models\Role $role
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function detachAllUser(Role $role)
    {
        $role->users()->detach();

        return response()->json(
            [
                'type'    => 'success',
                'content' => trans('access-control::role.detach_all_user_success'),
            ]
        );
    }

    /**
     * @param \Minhbang\AccessControl\Models\Role $role
     * @param string $ids
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function attachPermission(Role $role, $ids)
    {
        if ($ids = explode(',', trim($ids))) {
            $all_ids = Permission::all()->pluck('id')->all();
            // Chỉ lấy ids là permissions ID
            $ids = array_filter($ids, function ($id) use ($all_ids) {
                return in_array($id, $all_ids);
            });
            // Loại bỏ các permissions đã attach vào $role này rồi
            $ids = array_diff($ids, $role->permissions->pluck('id')->all());
            if ($ids) {
                $role->permissions()->attach($ids);
            }
        }

        return response()->json(
            [
                'type'    => 'success',
                'content' => trans('access-control::role.attach_permission_success'),
            ]
        );
    }

    /**
     * @param \Minhbang\AccessControl\Models\Role $role
     * @param string $ids
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function detachPermission(Role $role, $ids)
    {
        if ($ids = explode(',', trim($ids))) {
            $role->permissions()->detach($ids);
        }

        return response()->json(
            [
                'type'    => 'success',
                'content' => trans('access-control::role.detach_permission_success'),
            ]
        );
    }

    /**
     * @param \Minhbang\AccessControl\Models\Role $role
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function detachAllPermission(Role $role)
    {
        $role->permissions()->detach();

        return response()->json(
            [
                'type'    => 'success',
                'content' => trans('access-control::role.detach_all_permission_success'),
            ]
        );
    }
}
