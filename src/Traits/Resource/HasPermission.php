<?php
namespace Minhbang\AccessControl\Traits\Resource;
/**
 * Class HasPermission
 * Sử dụng cho Resource Model
 *
 * @property-read string $resource_name
 * @property-read string $resource_title
 * @package Minhbang\AccessControl\Traits
 */
trait HasPermission
{
    /**
     * Resource Name của model
     *
     * @return string
     */
    abstract protected function resourceName();

    /**
     * Resource Title của model (display name)
     *
     * @return string
     */
    abstract protected function resourceTitle();

    /**
     * Các actions cần access control
     * Định dạng ['create', 'read' => 'Xem', 'update', 'delete',...]
     * Nếu không ghi rõ label (như read ở trên) thì dùng label mặc định
     *
     * @return array
     */
    abstract public function actions();

    /**
     * Getter: $this->resource_name
     *
     * @return string
     */
    public function getResourceNameAttribute()
    {
        return $this->resourceName();
    }

    /**
     * Getter: $this->resource_title
     *
     * @return string
     */
    public function getResourceTitleAttribute()
    {
        return $this->resourceTitle();
    }
}