<?php
namespace Minhbang\AccessControl\Models;

use Laracasts\Presenter\PresentableTrait;
use Minhbang\LaravelKit\Extensions\Model;

/**
 * Class Role
 *
 * @package Minhbang\AccessControl\Models
 * @property integer $id
 * @property string $system_name
 * @property string $full_name
 * @property string $short_name
 * @property string $acronym_name
 * @property integer $level
 * @property integer $group_id
 * @property-read \Minhbang\AccessControl\Models\RoleGroup $group
 * @property-read \Illuminate\Database\Eloquent\Collection|\Minhbang\LaravelUser\User[] $users
 * @property-read \Illuminate\Database\Eloquent\Collection|\Minhbang\AccessControl\Models\Permission[] $permissions
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\AccessControl\Models\Role whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\AccessControl\Models\Role whereSystemName($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\AccessControl\Models\Role whereFullName($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\AccessControl\Models\Role whereShortName($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\AccessControl\Models\Role whereAcronymName($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\AccessControl\Models\Role whereLevel($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\AccessControl\Models\Role whereGroupId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\AccessControl\Models\Role systemName($system_name)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelKit\Extensions\Model except($id = null)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelKit\Extensions\Model findText($column, $text)
 */
class Role extends Model
{
    use PresentableTrait;
    protected $table = 'roles';
    protected $presenter = 'Minhbang\AccessControl\Presenters\RolePresenter';
    protected $fillable = ['system_name', 'full_name', 'short_name', 'acronym_name', 'level'];
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo('Minhbang\AccessControl\Models\RoleGroup');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(config('auth.providers.users.model'));
    }

    /**
     * Danh sách IDs [cùng role_group] nhưng [level nhỏ hơn nó]
     *
     * @param bool $self bao gồm chính nó
     *
     * @return array
     */
    public function getInferiorIds($self = false)
    {
        $ids = $this->newQuery()
            ->where('group_id', '=', $this->group_id)
            ->where('level', '<', $this->level)
            ->lists('id')->all();
        if ($self) {
            $ids[] = $this->id;
        }
        return $ids;
    }

    /**
     * Permissions của role, $all =
     * - false: chỉ lấy các permissions 'attach' với role như bình thường, qua table permission_role
     * - true: lấy thêm permissions của các role [cùng role_group] nhưng [level nhở hơn nó]
     *
     * @param bool $all
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions($all = false)
    {
        $query = $this->belongsToMany('Minhbang\AccessControl\Models\Permission');
        if ($all && ($ids = $this->getInferiorIds())) {
            $query->orWhereIn('permission_role.role_id', $ids);
        }
        return $query;
    }

    /**
     * @param mixed $value
     */
    public function setLevelAttribute($value)
    {
        $this->attributes['level'] = $value ? $value : 1;
    }

    /**
     * @param \Illuminate\Database\Query\Builder|static $query
     * @param string $system_name
     *
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function scopeSystemName($query, $system_name)
    {
        return $query->where('system_name', $system_name);
    }

    /**
     * @param string $system_name
     *
     * @return static|null
     */
    public static function findBySystemName($system_name)
    {
        return static::systemName($system_name)->first();
    }

    /**
     * Hook các events của model
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();
        // trước khi xóa Role, xóa các liên kết với User và Permission
        static::deleting(
            function ($model) {
                /** @var static $model */
                $model->users()->detach();
                $model->permissions()->detach();
            }
        );
    }
}
