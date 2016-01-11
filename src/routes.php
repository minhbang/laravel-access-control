<?php
Route::group(
    ['prefix' => 'backend', 'namespace' => 'Minhbang\AccessControl\Controllers'],
    function () {
        // Role Group
        Route::resource('role_group', 'RoleGroupController', ['except' => ['index', 'show']]);
        // Role
        Route::group(
            ['prefix' => 'role', 'as' => 'backend.role.'],
            function () {
                Route::get('of/{group}', ['as' => 'group', 'uses' => 'RoleController@index']);
                // Link User
                Route::group(
                    ['prefix' => '{role}/user', 'as' => 'user.'],
                    function () {
                        Route::post('{user}', ['as' => 'attach', 'uses' => 'RoleController@attachUser']);
                        Route::delete('{user}', ['as' => 'detach', 'uses' => 'RoleController@detachUser']);
                        Route::delete('/', ['as' => 'detach_all', 'uses' => 'RoleController@detachAllUser']);
                    }
                );
                // Link Permission
                Route::group(
                    ['prefix' => '{role}/permission', 'as' => 'permission.'],
                    function () {
                        Route::post('{ids}', ['as' => 'attach', 'uses' => 'RoleController@attachPermission']);
                        Route::delete('{ids}', ['as' => 'detach', 'uses' => 'RoleController@detachPermission']);
                        Route::delete('/', ['as' => 'detach_all', 'uses' => 'RoleController@detachAllPermission']);
                    }
                );
            }
        );
        Route::resource('role', 'RoleController');
        // Permission
        Route::group(
            ['prefix' => 'permission', 'as' => 'backend.permission.'],
            function () {
                Route::post('sync', ['as' => 'sync', 'uses' => 'PermissionController@sync']);
            }
        );

    }
);