<?php
Route::get('/', function () {
    return redirect('/admin/home');
});

// Authentication Routes...
$this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
$this->post('login', 'Auth\LoginController@login')->name('auth.login');
$this->post('logout', 'Auth\LoginController@logout')->name('auth.logout');

// Change Password Routes...
$this->get('change_password', 'Auth\ChangePasswordController@showChangePasswordForm')->name('auth.change_password');
$this->patch('change_password', 'Auth\ChangePasswordController@changePassword')->name('auth.change_password');

// Password Reset Routes...
$this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('auth.password.reset');
$this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('auth.password.reset');
$this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
$this->post('password/reset', 'Auth\ResetPasswordController@reset')->name('auth.password.reset');

Route::group(['middleware' => ['auth'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    // Route::get('/home', 'HomeController@index');
    Route::match(array('GET', 'POST'), 'admin\engagements', 'Admin\EngagementsController@index');
    Route::match(array('GET', 'POST'), 'home', 'HomeController@index');
    Route::match(array('GET', 'POST'), '/reports/top', 'Admin\ReportsController@top');
    Route::match(array('GET', 'POST'), '/reports/bitcoin', 'Admin\ReportsController@bitcoin');
    Route::match(array('GET', 'POST'), '/reports/outtobank', 'Admin\ReportsController@outtobank');
    // Route::get('/reports/top', 'Admin\ReportsController@top');
    // Route::get('/reports/bitcoin', 'Admin\ReportsController@bitcoin');
    Route::get('/reports/reactions', 'Admin\ReportsController@reactions');
    Route::get('/reports/comments', 'Admin\ReportsController@comments');
    Route::get('/reports/shares', 'Admin\ReportsController@shares');


    Route::resource('banks', 'Admin\BanksController');
    Route::post('banks_mass_destroy', ['uses' => 'Admin\BanksController@massDestroy', 'as' => 'banks.mass_destroy']);
    Route::resource('permissions', 'Admin\PermissionsController');
    Route::post('permissions_mass_destroy', ['uses' => 'Admin\PermissionsController@massDestroy', 'as' => 'permissions.mass_destroy']);
    Route::resource('roles', 'Admin\RolesController');
    Route::post('roles_mass_destroy', ['uses' => 'Admin\RolesController@massDestroy', 'as' => 'roles.mass_destroy']);
    Route::resource('users', 'Admin\UsersController');
    Route::post('users_mass_destroy', ['uses' => 'Admin\UsersController@massDestroy', 'as' => 'users.mass_destroy']);
    Route::resource('engagements', 'Admin\EngagementsController');
    // Route::match(array('GET', 'POST'), '/engagements/index', 'Admin\EngagementsController@index');
    Route::post('engagements_mass_destroy', ['uses' => 'Admin\EngagementsController@massDestroy', 'as' => 'engagements.mass_destroy']);
    Route::post('engagements_restore/{id}', ['uses' => 'Admin\EngagementsController@restore', 'as' => 'engagements.restore']);
    Route::delete('engagements_perma_del/{id}', ['uses' => 'Admin\EngagementsController@perma_del', 'as' => 'engagements.perma_del']);

    Route::post('fetchTransaction', 'Admin\EngagementsController@fetchTransaction')->name('fetchTransaction');

    Route::post('csv_parse', 'Admin\CsvImportController@parse')->name('csv_parse');
    Route::post('csv_process', 'Admin\CsvImportController@process')->name('csv_process');
});