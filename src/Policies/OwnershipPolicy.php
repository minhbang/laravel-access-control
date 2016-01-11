<?php
namespace Minhbang\AccessControl\Policies;
/**
 * Class OwnershipPolicy
 * Các luật liên quan đến quyền sở hữu content
 *
 * @package Minhbang\AccessControl\Policies
 */
class OwnershipPolicy extends Policy
{
    public function update()
    {
        return user()->isAuthorOf($this->entity);
    }

    /**
     * Chỉ được xóa khi:
     * - SuperAdmin, Admin
     * - Chủ của model
     * - Thủ trưởng đơn vị người tạo
     * - Thủ trưởng cơ quan
     * - Ban Giám hiệu
     *
     * @return bool
     */
    public function delete()
    {
        return user()->isAuthorOf($this->entity);
    }
}