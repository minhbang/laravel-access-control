<?php
namespace Minhbang\AccessControl;

use Lang;

/**
 * Class AccessControl
 *
 * @package Minhbang\AccessControl
 */
class AccessControl
{
    /**
     * @var string[]
     */
    protected $resource_classes;
    /**
     * @var string[]
     */
    protected $resource_titles;
    /**
     * @var \MinhBang\AccessControl\Contracts\Model[]
     */
    protected $resource_models;
    /**
     * @var array
     */
    protected $resource_actions;

    /**
     * AccessControl constructor.
     *
     * @param array $resources
     */
    public function __construct($resources)
    {
        $this->resource_classes = $resources;
    }

    /**
     * Lấy tất cả tên 'hiển thị' từ name (tên hệ thống) của resources
     *
     * @param null|string $name
     * @param mixed $default
     *
     * @return string[]
     */
    public function getResourceTitles($name = null, $default = null)
    {
        if (!$this->resource_titles) {
            $this->resource_titles = [];
            foreach ($this->getResourceModels() as $model) {
                $this->resource_titles[$model->resource_name] = $model->resource_title;
            };
        }
        return array_get($this->resource_titles, $name, $default);
    }

    /**
     * Lấy actions của tất cả resources, hoặc của resource $name, hoặc chỉ $action label
     * Chú ý tạo đủ: [action => 'label'] nếu model khai báo thiếu label
     * [
     *     'article' => ['create'=>'Tạo', 'read'=> 'Read',...],
     *     'document' => ...
     * ]
     *
     * @param null|string $name
     * @param null|string $action
     * @param mixed $default
     *
     * @return array
     */
    public function getResourceActions($name = null, $action = null, $default = null)
    {
        if (!$this->resource_actions) {
            $this->resource_actions = [];
            foreach ($this->getResourceModels() as $model) {
                $actions = $model->actions();
                $result = [];
                array_walk($actions, function ($value, $key) use (&$result) {
                    if (is_numeric($key)) {
                        $result[$value] = Lang::has("common.{$value}") ?
                            Lang::trans("common.{$value}") : ucwords(str_replace('.', ' ', $value));
                    } else {
                        $result[$key] = $value;
                    }
                });
                $this->resource_actions[$model->resource_name] = $result;
            };
        }
        $name = $name ? ($action ? "{$name}.{$action}" : $name) : null;
        return array_get($this->resource_actions, $name, $default);
    }

    /**
     * Lấy tất cả models của resources, hoặc $model của $name
     *
     * @param string|null $name
     * @param mixed $default
     *
     * @return \MinhBang\AccessControl\Contracts\Model[]|\MinhBang\AccessControl\Contracts\Model|mixed
     */
    public function getResourceModels($name = null, $default = null)
    {
        if (!$this->resource_models) {
            $this->resource_models = array_map(function ($class) {
                return new $class();
            }, $this->resource_classes);
        }
        return array_get($this->resource_models, $name, $default);
    }
}