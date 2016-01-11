<?php
namespace Minhbang\AccessControl\Models;

use Laracasts\Presenter\PresentableTrait;
use Minhbang\LaravelKit\Extensions\Model;
use Minhbang\AccessControl\Presenters\PermissionPresenter;
/**
 * Class Permission
 *
 * @package Minhbang\AccessControl\Models
 * @property integer $id
 * @property string $resource
 * @property string $action
 * @property-read \Illuminate\Database\Eloquent\Collection|\Minhbang\AccessControl\Models\Role[] $roles
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\AccessControl\Models\Permission whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\AccessControl\Models\Permission whereResource($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\AccessControl\Models\Permission whereAction($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelKit\Extensions\Model except($id = null)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelKit\Extensions\Model findText($column, $text)
 */
class Permission extends Model
{
    use PresentableTrait;
    protected $presenter = PermissionPresenter::class;
    protected $table = 'permissions';
    protected $fillable = ['resource', 'action'];
    public $timestamps = false;

    /**
     * Danh sách roles có permission này
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany('Minhbang\AccessControl\Models\Role');
    }

    /**
     * Hook các events của model
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();
        // trước khi xóa Permission, sẽ các liến kết với roles
        static::deleting(
            function ($model) {
                /** @var static $model */
                $model->roles()->detach();
            }
        );
    }
}