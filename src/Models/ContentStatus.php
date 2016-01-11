<?php
namespace Minhbang\AccessControl\Models;
/**
 * Class ContentStatus
 *
 * @package Minhbang\AccessControl\Models
 */
class ContentStatus
{
    /*
    |--------------------------------------------------------------------------
    | TRẠNG THÁI CỦA THÔNG TIN
    |--------------------------------------------------------------------------
    |
    | - Đơn vị: Thủ trưởng đơn vị của Người tạo
    | - Cơ quan: Thủ trưởng cơ quan phụ trách danh mục của thông tin
    |
    */
    /**
     * Nháp
     * - Người tạo: xem, sửa, xóa, status: [pending1] (Gửi Đ.Vị duyệt)
     */
    const DRAFT = 0;
    /**
     * Chờ Đơn vị duyệt
     * - Người tạo: xem, sửa, xóa, status: [draft] (Nháp)
     * - Đơn vị (Chờ duyệt): xem, status: [returned1, pending2] (Trả về, Gửi C.Quan duyệt)
     */
    const PENDING1 = 1;
    /**
     * Đơn vị trả về
     * - Người tạo: xem, sửa, xóa, status: [draft, pending1] (Nháp, Gửi Đ.Vị duyệt lại)
     * - Đơn vị (Đã trả về): xem, status: [pending1, pending2] (Chưa duyệt, Gửi C.Quan duyệt)
     */
    const RETURNED1 = 2;

    /**
     * Chơ Cơ quan quản lý duyệt
     * - Người tạo: xem
     * - Đơn vị (Đã duyệt): xem, status: [pending1, returned1] (Chưa duyệt, Trả về)
     * - Cơ quan (Chờ duyệt): xem, status: [returned2, published1, pending3] (Trả về, C.Quan Đăng, Trình BGH)
     */
    const PENDING2 = 3;
    /**
     * Cơ quan trả về
     * - Người tạo: xem
     * - Đơn vị (Cơ quan trả về): xem, status: [pending2, returned1] (Gửi C.Quan duyệt lại, Trả về)
     * - Cơ quan (Đã trả về): xem, status: [published1, pending2, pending3] (C.Quan Đăng, Chưa duyệt, Trình BGH)
     */
    const RETURNED2 = 4;

    /**
     * Chờ BGH duyệt
     * - Người tạo: xem
     * - Đơn vị (Đã duyệt): xem
     * - Cơ quan (Đã duyệt): status: [pending2, returned2] (Cơ quan xem xét lại, Trả về)
     * - BGH (Chờ duyệt): xem, status: [returned3, published2] (Trả về, BGH Đăng)
     */
    const PENDING3 = 5;
    /**
     * BGH trả về
     * - Người tạo: xem
     * - Đơn vị (Đã duyệt): xem
     * - Cơ quan (BGH trả về): status: [pending3, returned2] (Trình BGH lại, Trả về)
     * - BGH (Đã trả về): xem, status: [published2, pending3] (BGH Đăng, Chưa duyệt)
     */
    const RETURNED3 = 6;

    /**
     * Cơ quan đã đăng
     * - Người tạo: xem
     * - Đơn vị (Đã đăng): xem
     * - Cơ quan (Đã đăng): status: [pending2, pending3, returned2] (Chưa duyệt, Trình BGH, Trả về)
     * - BGH (Đã đăng): xem, status: [returned3] (Trả về)
     */
    const PUBLISHED1 = 7;
    /**
     * BGH đã đăng
     * - Người tạo: xem
     * - Đơn vị (Đã đăng): xem
     * - Cơ quan (Đã đăng): xem
     * - BGH (Đã đăng): xem, status: [returned3, pending3] (Trả về, Chờ duyệt)
     */
    const PUBLISHED2 = 8;

    /*
    |--------------------------------------------------------------------------
    | NHÓM TRẠNG THÁI
    |--------------------------------------------------------------------------
    */
    /**
     * 1. Cá nhân:
     * - status: DRAFT, PENDING1, RETURNED1
     * - Và user là Người tạo Content
     */
    const GROUP1 = 1;
    /**
     * 2. Đơn vị:
     * - status: PENDING1, RETURNED1, PENDING2, RETURNED2
     * - Và user là Thủ trưởng Đơn vị của Người tạo Content
     */
    const GROUP2 = 2;
    /**
     * 3. Cơ quan
     * - status: PENDING2, RETURNED2, PENDING3, RETURNED3, PUBLISHED1
     * - Và user là Thủ trưởng Cơ quan quản lý Chuyên mục của Content
     */
    const GROUP3 = 3;
    /**
     * 4. Ban Giám hiệu
     * - status: PENDING3, RETURNED3, PUBLISHED1, PUBLISHED2
     * - Và user thuộc BGH
     */
    const GROUP4 = 4;
    /**
     * @var array
     */
    protected $groups;

    /**
     * ContentStatus constructor.
     */
    public function __construct()
    {
        $this->groups = [
            static::GROUP1 => [static::DRAFT, static::PENDING1, static::RETURNED1],
            static::GROUP2 => [static::PENDING1, static::RETURNED1, static::PENDING2, static::RETURNED2],
            static::GROUP3 => [static::PENDING2, static::RETURNED2, static::PENDING3, static::RETURNED3, static::PUBLISHED1],
            static::GROUP4 => [static::PENDING3, static::RETURNED3, static::PUBLISHED1, static::PUBLISHED2],
        ];
    }

    /**
     * Kiểm tra $model có phải thuộc GROUP1, đối với User($user_id)
     *
     * @param mixed $model
     * @param int|null $user_id
     *
     * @return bool
     */
    public function isInGroup1($model, $user_id = null)
    {
        return in_array($model->status, $this->groups[static::GROUP1]) && user($user_id)->isAuthorOf($model);
    }

    /**
     * Kiểm tra $model có phải thuộc GROUP1, đối với User($user_id)
     *
     * @param mixed $model
     * @param int|null $user_id
     *
     * @return bool
     */
    public function isInGroup2($model, $user_id = null)
    {
        $author = user($model->user_id);

        return in_array($model->status, $this->groups[static::GROUP2]) && user($user_id)->isAuthorOf($model);
    }

    /**
     * Content $model chưa được đăng
     *
     * @param \Minhbang\LaravelKit\Extensions\Model|mixed $model
     *
     * @return bool
     */
    public function isUnpublished($model)
    {
        return $model->status < self::PUBLISHED1;
    }
}
