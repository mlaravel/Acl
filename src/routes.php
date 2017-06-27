<?php

Route::group([
    'namespace' => 'iLaravel\Acl\Controllers',
    'prefix'    => 'acl',
    'as'      => 'acl.',
    'middleware' => 'web',
], function () {
    Route::group(['prefix' => 'role', 'as' => 'role.'], function () {
        Route::get('/create', 'RoleController@create')->name('create');
        Route::post('/', ['uses' => 'RoleController@store', 'as' => 'store']);
        Route::put('{id}', 'RoleController@update');

        Route::get('/sync', 'RoleController@getSyncPermissions');
        Route::post('/sync', 'RoleController@syncPermissions')->name('sync');

        Route::get('get', 'RoleController@getRoles');
    });
    Route::group(['prefix' => 'permission'], function () {
        Route::get('get', 'PermissionController@getPermissions');
    });
});
