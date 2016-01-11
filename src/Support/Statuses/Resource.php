<?php
namespace Minhbang\AccessControl\Support\Statuses;
/**
 * Interface Resource
 * Trạng thái của Resource
 *
 * @package Minhbang\AccessControl\Contracts\Statuses
 */
interface Resource
{
    /**
     * Trạng thái đang xử lý: user đang biên tập, đang trình thủ trưởng duyệt...
     */
    const STATUS_PROCESSING = 1;
    /**
     * Trạng thái trả về: thủ trưởng trả về, cơ quan trả về...
     */
    const STATUS_REFUSED = 2;
    /**
     * Trạng thái đã đăng: cơ quan đã đăng, bgh đã đăng...
     */
    const STATUS_PUBLISHED = 3;
}