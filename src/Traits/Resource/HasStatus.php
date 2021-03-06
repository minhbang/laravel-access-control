<?php
namespace Minhbang\AccessControl\Traits\Resource;

/**
 * Class HasStatus
 * Trait cho Resource Model: Article, Document,...
 * - Model có property 'status' => trạng thái hiện tại
 *
 * @property string $table
 * @property int $status
 * @property bool timestamps
 * @method bool save(array $options = [])
 * @package Minhbang\AccessControl\Traits\Resource
 */
trait HasStatus
{
    /**
     * @var array
     */
    protected static $status_titles;

    /**
     * Định nghĩa tất cả statuses
     *
     * @return array
     */
    abstract public function statuses();

    /**
     * Các css class cho các statuses
     *
     * @return mixed
     */
    abstract public function statusCss();

    /**
     * Lấy resources theo status
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @param int $status
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeStatus($query, $status)
    {
        return $query->where("{$this->table}.status", '=', $status);
    }

    /**
     * Lấy status titles
     * - $status = null: tất cả status, dạng: ['status code' => 'status title,...']
     * - $status !=null: nếu tồn tại ==> 'status title', ngược lại ==> FALSE
     *
     * @param null|int $status
     *
     * @return array|string|false
     */
    public function statusTitles($status = null)
    {
        if (!self::$status_titles) {
            $instance = new static();
            self::$status_titles = $instance->statuses();
        }

        return $status ?
            (isset(self::$status_titles[$status]) ? self::$status_titles[$status] : false) :
            self::$status_titles;
    }

    /**
     * Lấy tất cả status values, không 'title'
     *
     * @return array
     */
    public function statusValues()
    {
        return array_keys($this->statusTitles());
    }

    /**
     * @return string
     */
    public function statusTitle()
    {
        $title = $this->statusTitles($this->status);
        if (is_array($title)) {
            $title = current($title);
        }

        return $title;
    }

    /**
     * @param int $status
     *
     * @return bool
     */
    public function statusUpdate($status)
    {
        $status = (int)$status;
        if (in_array($status, $this->statusValues())) {
            $this->timestamps = false;
            $this->status = $status;

            return $this->save();
        } else {
            return false;
        }
    }
}