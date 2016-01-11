<?php
return [
    /**
     * Tự động add các route
     */
    'add_route'   => true,
    'middlewares' => [
        'role'       => 'admin',
        'role_group' => 'admin',
        'permission'  => 'admin',
    ],
    /**
     * Danh sách resource classes cần access control
     * Resource model phải use HasPermission trait
     */
    'resources'   => [
        \Minhbang\Article\Article::class,
        \Minhbang\LaravelImage\ImageModel::class,
    ],
];