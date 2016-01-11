<?php
namespace Minhbang\AccessControl\Traits\User;

use Minhbang\AccessControl\Models\Permission;

/**
 * Class HasRole
 * User model trait: thêm các tính năng RBAC cho user
 *
 * @package Minhbang\AccessControl\Traits
 * @property-read bool $exists
 * @method \Illuminate\Database\Eloquent\Relations\BelongsToMany belongsToMany($related, $table = null, $foreignKey = null, $otherKey = null, $relation = null)
 */
trait HasRole
{
    /**
     * Property for caching roles.
     *
     * @var \Illuminate\Database\Eloquent\Collection|\Minhbang\AccessControl\Models\Role[]|null
     */
    protected $cached_roles;

    /**
     * Property for caching permissions.
     *
     * @var \Illuminate\Database\Eloquent\Collection|\Minhbang\AccessControl\Models\Permission[]|null
     */
    protected $cached_permissions;

    /**
     * Boot cho model sử dụng HasRole
     */
    public static function bootHasRole()
    {
        // trước khi xóa Model, xóa các liên kết với Role
        static::deleting(
            function ($model) {
                /** @var static $model */
                $model->roles()->detach();
            }
        );
    }

    /**
     * Danh sách role đã gán cho User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany('Minhbang\AccessControl\Models\Role')->orderBy('roles.level');
    }

    /**
     * Get all roles as collection.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRoles()
    {
        if (is_null($this->cached_roles)) {
            $this->cached_roles = $this->roles()->get();
        }
        return $this->cached_roles;
    }

    /**
     * Check if the user has a role or roles.
     *
     * @param int|string|array $role
     * @param bool $all
     *
     * @return bool
     */
    public function is($role, $all = false)
    {
        if (!$this->exists) {
            return false;
        }
        return $this->{$this->getMethodName('is', $all)}($role);
    }

    /**
     * Check if the user has at least one role.
     *
     * @param int|string|array $role
     *
     * @return bool
     */
    public function isOne($role)
    {
        if (!$this->exists) {
            return false;
        }
        foreach ($this->getArrayFrom($role) as $role) {
            if ($this->hasRole($role)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if the user has all roles.
     *
     * @param int|string|array $role
     *
     * @return bool
     */
    public function isAll($role)
    {
        if (!$this->exists) {
            return false;
        }
        foreach ($this->getArrayFrom($role) as $role) {
            if (!$this->hasRole($role)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Check if the user has role.
     * Có thể sử dụng str*,
     * vd: 'truong*' thay cho 'Trưởng Khoa', 'Trưởng Bộn môn',...
     *
     * @param int|string $role
     *
     * @return bool
     */
    public function hasRole($role)
    {
        if (!$this->exists) {
            return false;
        }
        return $this->getRoles()->contains(function ($key, $value) use ($role) {
            /** @var \Minhbang\AccessControl\Models\Role $value */
            return $role == $value->id || str_is($role, $value->system_name);
        });
    }


    /**
     * Lấy tất cả permisions của các role của user
     * - Đối với từng role, permissions bao gồm của nó và của tất cả role ưcùng role_groupơ nhưng [level < hơn nó]
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function permissions()
    {
        // Role IDs tất cả roles attach trực tiếp với User + 'Roles cấp dưới của các roles đó'
        $role_ids = [];
        foreach ($this->getRoles() as $role) {
            /** @var \Minhbang\AccessControl\Models\Role $role */
            $role_ids = array_merge($role_ids, $role->getInferiorIds(true));
        }
        $role_ids = $role_ids ?: [-1];

        return Permission::select('permissions.*')
            ->join('permission_role', 'permission_role.permission_id', '=', 'permissions.id')
            ->join('roles', 'roles.id', '=', 'permission_role.role_id')
            ->whereIn('roles.id', $role_ids)
            ->groupBy('permissions.id');
    }

    /**
     * Get all permissions as collection.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPermissions()
    {
        if (is_null($this->cached_permissions)) {
            $this->cached_permissions = $this->permissions()->get();
        }
        return $this->cached_permissions;
    }

    /**
     * Check if the user has a permission or permissions.
     * $permission string theo định dạng 'resource.action'
     *
     * @param int|string|array $permission
     * @param bool $all
     *
     * @return bool
     */
    public function can($permission, $all = false)
    {
        if (!$this->exists) {
            return false;
        }
        return $this->{$this->getMethodName('can', $all)}($permission);
    }

    /**
     * Check if the user has at least one permission.
     * $permission string theo định dạng 'resource.action'
     *
     * @param int|string|array $permission
     *
     * @return bool
     */
    public function canOne($permission)
    {
        if (!$this->exists) {
            return false;
        }
        foreach ($this->getArrayFrom($permission) as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if the user has all permissions.
     * $permission string theo định dạng 'resource.action'
     *
     * @param int|string|array $permission
     *
     * @return bool
     */
    public function canAll($permission)
    {
        if (!$this->exists) {
            return false;
        }
        foreach ($this->getArrayFrom($permission) as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Check if the user has a permission.
     * $permission string theo định dạng 'resource.action'
     *
     * @param int|string $permission
     *
     * @return bool
     */
    public function hasPermission($permission)
    {
        if (!$this->exists) {
            return false;
        }
        return $this->getPermissions()->contains(function ($key, $value) use ($permission) {
            /** @var \Minhbang\AccessControl\Models\Permission $value */
            return $permission == $value->id || str_is($permission, "{$value->resource}.{$value->action}");
        });
    }

    /**
     * Kiểm tra có được thực hiện $action đối với $entity model không
     *
     * @param string $action
     * @param mixed $entity
     *
     * @return bool
     */
    public function allowed($action, $entity)
    {
        if (!$this->exists) {
            return false;
        }
        if ($resource = $entity->resource_name) {
            return $this->hasPermission("{$resource}.{$action}");
        } else {
            return true;
        }
    }

    /**
     * Get method name.
     *
     * @param string $name
     * @param bool $all
     *
     * @return string
     */
    protected function getMethodName($name, $all)
    {
        return ((bool)$all) ? $name . 'All' : $name . 'One';
    }

    /**
     * Get an array from argument.
     * Tác $argument dạng 'string' (phân các bằng dấu ',' hoặc dấu '|') thành 'array'
     *
     * @param int|string|array $argument
     *
     * @return array
     */
    protected function getArrayFrom($argument)
    {
        return (!is_array($argument)) ? preg_split('/ ?[,|] ?/', $argument) : $argument;
    }

    /**
     * Handle dynamic method calls.
     *
     * @param string $method
     * @param array $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (starts_with($method, 'is')) {
            return $this->is(snake_case(substr($method, 2), '.'));
        } elseif (starts_with($method, 'can')) {
            return $this->can(snake_case(substr($method, 3), '.'));
        } elseif (starts_with($method, 'allowed')) {
            return $this->allowed(snake_case(substr($method, 7), '.'), $parameters[0]);
        }
        return parent::__call($method, $parameters);
    }
}