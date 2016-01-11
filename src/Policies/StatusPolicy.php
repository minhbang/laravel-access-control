<?php
namespace Minhbang\AccessControl\Policies;

use Minhbang\AccessControl\Models\ContentStatus;

/**
 * Class StatusPolicy
 * Các luật liên quan đến trạng thái của content
 *
 * @package Minhbang\AccessControl\Policies
 */
class StatusPolicy extends Policy
{
    /**
     * @var \Minhbang\AccessControl\Models\ContentStatus
     */
    protected $manager;

    public function __construct($entity)
    {
        parent::__construct($entity);
        $this->manager = new ContentStatus();
    }

    /**
     * Được phép XÓA khi content thuộc phạm vi quản lý của cá nhân tác giả
     *
     * @param int|null $user_id
     *
     * @return bool
     */
    public function delete($user_id = null)
    {
        return $this->manager->isInGroup1($this->entity, $user_id);
    }

    /**
     * Được phép SỬA khi:
     * - Thuộc phạm vi quản lý của tác giả
     * - hoặc Thuộc phạm vi Đơn vị mình quản lý
     * - hoặc Thuộc phạm vi Danh mục mình quản lý
     * - hoặc Thuộc phạm vi BGH
     */
    public function update()
    {

    }

}