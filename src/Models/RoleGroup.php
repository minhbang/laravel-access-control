<?php
namespace Minhbang\AccessControl\Models;

use Laracasts\Presenter\PresentableTrait;
use Minhbang\LaravelKit\Extensions\Model;

/**
 * Class RoleGroup
 *
 * @package Minhbang\AccessControl\Models
 * @property integer $id
 * @property string $name
 * @property-read \Illuminate\Database\Eloquent\Collection|\Minhbang\AccessControl\Models\Role[] $roles
 * @property-read mixed $resource_name
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\AccessControl\Models\RoleGroup whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\AccessControl\Models\RoleGroup whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelKit\Extensions\Model except($id = null)
 */
class RoleGroup extends Model
{
    use PresentableTrait;
    protected $table = 'role_groups';
    protected $presenter = 'Minhbang\AccessControl\Presenters\RoleGroupPresenter';
    protected $fillable = ['name'];
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function roles()
    {
        return $this->hasMany('Minhbang\AccessControl\Models\Role', 'group_id');
    }

    /**
     * Hook các events của model
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();
        // trước khi xóa Group, sẽ các Role thuộc group này
        static::deleting(
            function ($model) {
                /** @var static $model */
                foreach ($model->roles as $role) {
                    /** @var \Minhbang\AccessControl\Models\Role $role */
                    $role->delete();
                }
            }
        );
    }
}