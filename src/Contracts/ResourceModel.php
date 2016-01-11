<?php
namespace Minhbang\AccessControl\Contracts;
/**
 * Interface HasPermission
 *
 * @property-read string $resource_name
 * @property-read string $resource_title
 *
 * @package Minhbang\AccessControl\Contracts
 */
interface ResourceModel
{
    /**
     * Các actions tác động resource cần access control
     * Định dạng ['create', 'read' => 'Xem', 'update', 'delete',...]
     * Nếu không ghi rõ label (như read ở trên) thì dùng label mặc định
     *
     * @return array
     */
    public function actions();

    /**
     * Getter: $this->resource_name
     *
     * @return string
     */
    public function getResourceNameAttribute();

    /**
     * Getter: $this->resource_title
     *
     * @return string
     */
    public function getResourceTitleAttribute();
}