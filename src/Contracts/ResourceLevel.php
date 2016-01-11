<?php
namespace Minhbang\AccessControl\Contracts;
/**
 * Interface ResourceLevel
 * Trạng thái của Resource
 *
 * @package Minhbang\AccessControl\Contracts
 */
interface ResourceLevel
{
    /**
     * Cấp Cá nhân: đạng soạn thảo, bị trả về
     */
    const LEVEL_CANHAN = 1;

    /**
     * Cấp Đơn vị: trình thủ trưởng đơn vị duyệt, cơ quan trả về
     */
    const LEVEL_DONVI = 2;

    /**
     * Cấp Cơ quan: trình cơ quan duyệt, bgh trả về, đã đăng
     */
    const LEVEL_COQUAN = 3;

    /**
     * Cấp BGH: trình bgh duyệt, đã đăng
     */
    const LEVEL_BGH = 4;

    /**
     * @param \Illuminate\Database\Query\Builder $query
     * @param int $level
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeLevel($query, $level);

    /**
     * Lấy level titles
     * - $level = null: tất cả level, dạng: ['level code' => 'level title,...']
     * - $level != null: nếu tồn tại ==> 'level title', ngược lại ==> FALSE
     *
     * @param null|int $level
     *
     * @return array|string|false
     */
    public function levelTitles($level = null);

    /**
     * Lấy tất cả level values, không 'title'
     *
     * @return array
     */
    public function levelValues();


}