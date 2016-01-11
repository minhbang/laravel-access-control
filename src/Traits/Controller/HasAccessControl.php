<?php
namespace Minhbang\AccessControl\Traits\Controller;
/**
 * Class HasAccessControl
 * Sử dụng cho Controller cần Access Control
 *
 * @package Minhbang\AccessControl\Traits
 */
trait HasAccessControl
{
    /**
     * Khai báo resource mà controler này tác động đến
     * @return string
     */
    abstract protected function resource();
    /**
     * HasAccessControl boot method
     */
    protected function bootHasAccessControl()
    {

    }
}