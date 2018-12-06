<?php


#=================   main page   ====================
Route::get('/', [
    'uses' => 'BlogController@index',
    'as' => 'blog'
]);


#=================   blog details   ====================
Route::get('/blog/{post}', [
    'uses' => 'BlogController@show',
    'as'   => 'blog.show'
]);


#=================   Comments   ====================
Route::post('/blog/{post}/comments', [
    'uses' => 'CommentsController@store',
    'as'   => 'blog.comments'
]);

#=================   Category show   ====================
Route::get('/category/{category}', [
    'uses' => 'BlogController@category',
    'as'   => 'category'
]);

Route::get('/author/{author}', [
    'uses' => 'BlogController@author',
    'as'   => 'author'
]);


#=================   Tags   ====================
Route::get('/tag/{tag}', [
    'uses' => 'BlogController@tag',
    'as'   => 'tag'
]);









Auth::routes();

Route::get('/home', 'Backend\HomeController@index');

#=================   Edit Profile Form   ====================
Route::get('/edit-account', 'Backend\HomeController@edit');
Route::put('/edit-account', 'Backend\HomeController@update');


#=================   Post   ====================
Route::resource('/backend/blog', 'Backend\BlogController', ['as' => 'backend']);

Route::put('/backend/blog/restore/{blog}', [
    'uses' => 'Backend\BlogController@restore',
    'as'   => 'blog.restore'
]);

Route::delete('/backend/blog/force-destroy/{blog}', [
    'uses' => 'Backend\BlogController@forceDestroy',
    'as'   => 'blog.force-destroy'
]);

#=================   Categories   ====================
Route::resource('/backend/categories', 'Backend\CategoriesController', ['as' => 'backend']);

#=================   Users   ====================
Route::resource('/backend/users', 'Backend\UsersController', ['as' => 'backend']);

#=================   Confirm Delete User   =======
Route::get('/backend/users/confirm/{users}', [
    'uses' => 'Backend\UsersController@confirm',
    'as' => 'users.confirm'
]);

