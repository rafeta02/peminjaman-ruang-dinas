<?php

Route::view('/', 'welcome');
Auth::routes(['register' => false]);

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth', 'admin']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // Audit Logs
    Route::resource('audit-logs', 'AuditLogsController', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);

    // User Alerts
    Route::delete('user-alerts/destroy', 'UserAlertsController@massDestroy')->name('user-alerts.massDestroy');
    Route::get('user-alerts/read', 'UserAlertsController@read');
    Route::resource('user-alerts', 'UserAlertsController', ['except' => ['edit', 'update']]);

    // Ruang
    Route::delete('ruangs/destroy', 'RuangController@massDestroy')->name('ruangs.massDestroy');
    Route::post('ruangs/media', 'RuangController@storeMedia')->name('ruangs.storeMedia');
    Route::post('ruangs/ckmedia', 'RuangController@storeCKEditorImages')->name('ruangs.storeCKEditorImages');
    Route::post('ruangs/parse-csv-import', 'RuangController@parseCsvImport')->name('ruangs.parseCsvImport');
    Route::post('ruangs/process-csv-import', 'RuangController@processCsvImport')->name('ruangs.processCsvImport');
    Route::resource('ruangs', 'RuangController');

    // Pinjam
    Route::delete('pinjams/destroy', 'PinjamController@massDestroy')->name('pinjams.massDestroy');
    Route::post('pinjams/media', 'PinjamController@storeMedia')->name('pinjams.storeMedia');
    Route::post('pinjams/ckmedia', 'PinjamController@storeCKEditorImages')->name('pinjams.storeCKEditorImages');
    Route::resource('pinjams', 'PinjamController');

    // Log Pinjam
    Route::delete('log-pinjams/destroy', 'LogPinjamController@massDestroy')->name('log-pinjams.massDestroy');
    Route::resource('log-pinjams', 'LogPinjamController');

    Route::get('system-calendar', 'SystemCalendarController@index')->name('systemCalendar');
});
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
        Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
    }
});
Route::group(['as' => 'frontend.', 'namespace' => 'Frontend', 'middleware' => ['auth']], function () {
    Route::get('/home', 'HomeController@index')->name('home');

    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // User Alerts
    Route::delete('user-alerts/destroy', 'UserAlertsController@massDestroy')->name('user-alerts.massDestroy');
    Route::resource('user-alerts', 'UserAlertsController', ['except' => ['edit', 'update']]);

    // Ruang
    Route::delete('ruangs/destroy', 'RuangController@massDestroy')->name('ruangs.massDestroy');
    Route::post('ruangs/media', 'RuangController@storeMedia')->name('ruangs.storeMedia');
    Route::post('ruangs/ckmedia', 'RuangController@storeCKEditorImages')->name('ruangs.storeCKEditorImages');
    Route::resource('ruangs', 'RuangController');

    // Pinjam
    Route::delete('pinjams/destroy', 'PinjamController@massDestroy')->name('pinjams.massDestroy');
    Route::post('pinjams/media', 'PinjamController@storeMedia')->name('pinjams.storeMedia');
    Route::post('pinjams/ckmedia', 'PinjamController@storeCKEditorImages')->name('pinjams.storeCKEditorImages');
    Route::resource('pinjams', 'PinjamController');

    // Log Pinjam
    Route::delete('log-pinjams/destroy', 'LogPinjamController@massDestroy')->name('log-pinjams.massDestroy');
    Route::resource('log-pinjams', 'LogPinjamController');

    Route::get('frontend/profile', 'ProfileController@index')->name('profile.index');
    Route::post('frontend/profile', 'ProfileController@update')->name('profile.update');
    Route::post('frontend/profile/destroy', 'ProfileController@destroy')->name('profile.destroy');
    Route::post('frontend/profile/password', 'ProfileController@password')->name('profile.password');
});
