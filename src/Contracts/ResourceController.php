<?php
namespace Minhbang\AccessControl\Contracts;
/**
 * Interface ResourceController
 * Các controller actions theo tác với resource, trừ CRUD
 *
 * @package Minhbang\AccessControl\Contracts
 */
interface ResourceController
{
    /**
     * Không chấp nhận, trả resource về cấp dưới
     * - level -= 1
     * - status = STATUS_REFUSED
     *
     * @param mixed $model
     *
     * @return mixed
     */
    public function refuse($model);

    /**
     * Duyệt resource, đồng thời gởi lên cấp trên
     * - level += 1
     * - status không thay đổi (STATUS_PROCESSING)
     *
     * @param mixed $model
     *
     * @return mixed
     */
    public function approve($model);

    /**
     * Đăng resource
     * - level không thay đổi
     * - status = STATUS_PUBLISHED
     * @param mixed $model
     *
     * @return mixed
     */
    public function publish($model);
}