<?php
namespace Minhbang\AccessControl\Traits\Resource;

use Minhbang\AccessControl\Contracts\ResourceLevel;

/**
 * Class HasLevel
 * Trait cho Resource Model: Article, Document,...
 * - Model có property 'level' => cấp nào đang thụ lý
 *
 * @property string $table
 * @property int $level
 * @package Minhbang\AccessControl\Traits\Resource
 */
trait HasLevel
{
    /**
     * @var array
     */
    protected static $level_titles;

    /**
     * Lấy resources theo $level
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @param int $level
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeLevel($query, $level)
    {
        // Điều kiện: resources đang ở level $level
        $query->where("{$this->table}.level", '=', $level);
        if (!user()->inAdminGroup()) {
            switch ($level) {
                case ResourceLevel::LEVEL_CANHAN:
                    // Điều kiện: resources do chính user() tạo
                    $query->where("{$this->table}.user_id", '=', user('id'));
                    break;
                case ResourceLevel::LEVEL_DONVI:
                    // Điều kiện: resources của các user khác cùng đơn vị do user() làm thủ trưởng
                    if (
                        // user() là thủ trưởng đơn vị
                        user()->isGroupManager() &&
                        // IDs users thuộc user()->group (gồm cả group con), trừ chính user()
                        ($ids = user()->group->users->lists('id', 'username')->forget(user('username'))->all())
                    ) {
                        $query->whereIn("{$this->table}.user_id", $ids);
                    } else {
                        $query->whereRaw('1=0');
                    }
                    break;
                case ResourceLevel::LEVEL_COQUAN:
                    // Điều kiện: resources thuộc các categories user()-group được phép quản lý
                    if (
                        // user() là thủ trưởng đơn vị
                        user()->isGroupManager() &&
                        // IDs các categories user()->group được phép quản lý (bao gồm các categories con)
                        ($ids = user()->group->categories->lists('id')->all())
                    ) {
                        $query->whereIn("{$this->table}.category_id", $ids);
                    } else {
                        $query->whereRaw('1=0');
                    }
                    break;
                case ResourceLevel::LEVEL_BGH:
                    // Điều kiện: là thủ trưởng bgh
                    if (!user()->inBgh()) {
                        $query->whereRaw('1=0');
                    }
                    break;
                default:
                    $query->whereRaw('1=0');
            }
        }
        return $query;
    }


    /**
     * Lấy level titles
     * - $level = null: tất cả level, dạng: ['level code' => 'level title,...']
     * - $level != null: nếu tồn tại ==> 'level title', ngược lại ==> FALSE
     *
     * @param null|int $level
     *
     * @return array|string|false
     */
    public function levelTitles($level = null)
    {
        if (!self::$level_titles) {
            self::$level_titles = [
                ResourceLevel::LEVEL_CANHAN => trans('access-control::level.canhan'),
                ResourceLevel::LEVEL_DONVI  => trans('access-control::level.donvi'),
                ResourceLevel::LEVEL_COQUAN => trans('access-control::level.coquan'),
                ResourceLevel::LEVEL_BGH    => trans('access-control::level.bgh'),
            ];
        }
        return $level ?
            (isset(self::$level_titles[$level]) ? self::$level_titles[$level] : false) :
            self::$level_titles;
    }

    /**
     * Lấy tất cả level values, không 'title'
     *
     * @return array
     */
    public function levelValues()
    {
        return array_keys($this->levelTitles());
    }


}