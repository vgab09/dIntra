<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Authentication Routes...
$this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
$this->post('login', 'Auth\LoginController@login');
$this->get('logout', 'Auth\LoginController@logout')->name('logout');

// Password Reset Routes...
$this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
$this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
$this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
$this->post('password/reset', 'Auth\ResetPasswordController@reset');

Route::group(['middleware' => 'auth'], function () {
    //Dashboard
    $this->get('/','Dashboard\DashboardController@dashboard')->name('dashboard');

    //Profile
    $this->get('/profile','Profile\ProfileController@edit')->name('editProfile');
    $this->post('/profile','Profile\ProfileController@update')->name('updateProfile');

    //Designation
    Route::group(['namespace'=>'Designation','prefix' => 'designations'], function () {
        $this->get('/', 'DesignationController@index')->middleware('permission:list_designation')->name('indexDesignation');
        $this->get('/new', 'DesignationController@new')->middleware('permission:create_designation')->name('newDesignation');
        $this->post('/new', 'DesignationController@insert')->middleware('permission:create_designation');
        $this->get('/edit/{id_designation}', 'DesignationController@edit')->middleware('permission:update_designation')->name('editDesignation');
        $this->post('/edit/{id_designation}', 'DesignationController@update')->middleware('permission:update_designation');
        $this->get('/delete/{id_designation}', 'DesignationController@delete')->middleware('permission:delete_designation')->name('deleteDesignation');
        $this->post('/delete/{id_designation}','DesignationController@resolveContractAndDelete')->middleware('permission:delete_designation');
    });

    //Department
    Route::group(['namespace'=>'Department','prefix' => 'department'], function () {
        $this->get('/', 'DepartmentController@index')->middleware('permission:list_department')->name('indexDepartment');
        $this->get('/new', 'DepartmentController@new')->middleware('permission:create_department')->name('newDepartment');
        $this->post('/new', 'DepartmentController@insert')->middleware('permission:create_department');
        $this->get('/edit/{id_department}', 'DepartmentController@edit')->middleware('permission:update_department')->name('editDepartment');
        $this->post('/edit/{id_department}', 'DepartmentController@update')->middleware('permission:update_department');
        $this->get('/delete/{id_department}', 'DepartmentController@delete')->middleware('permission:delete_department')->name('deleteDepartment');
        $this->post('/delete/{id_department}','DepartmentController@resolveContractAndDelete')->middleware('permission:delete_department');
    });
    
    //leaveType
    Route::group(['namespace'=>'LeaveType','prefix' => 'leavetype'], function () {
        $this->get('/', 'LeaveTypeController@index')->middleware('permission:list_leave_type')->name('indexLeaveType');
        $this->get('/new', 'LeaveTypeController@new')->middleware('permission:create_leave_type')->name('newLeaveType');
        $this->post('/new', 'LeaveTypeController@insert')->middleware('permission:create_leave_type');
        $this->get('/edit/{id_leave_type}', 'LeaveTypeController@edit')->middleware('permission:update_leave_type')->name('editLeaveType');
        $this->post('/edit/{id_leave_type}', 'LeaveTypeController@update')->middleware('permission:update_leave_type');
        $this->get('/delete/{id_leave_type}', 'LeaveTypeController@delete')->middleware('permission:delete_leave_type')->name('deleteLeaveType');
        $this->post('/delete/{id_leave_type}','LeaveTypeController@resolveContractAndDelete')->middleware('permission:delete_leave_type');
    });

    //workDay
    Route::group(['namespace'=>'Workday','prefix' => 'workday'], function () {
        $this->get('/', 'WorkdayController@index')->middleware('permission:list_workday')->name('indexWorkday');
        $this->get('/new', 'WorkdayController@new')->middleware('permission:create_workday')->name('newWorkday');
        $this->post('/new', 'WorkdayController@insert')->middleware('permission:create_workday');
        $this->get('/edit/{id_workday}', 'WorkdayController@edit')->middleware('permission:update_workday')->name('editWorkday');
        $this->post('/edit/{id_workday}', 'WorkdayController@update')->middleware('permission:update_workday');
        $this->get('/delete/{id_workday}', 'WorkdayController@delete')->middleware('permission:delete_workday')->name('deleteWorkday');
        $this->post('/delete/{id_workday}','WorkdayController@resolveContractAndDelete')->middleware('permission:delete_workday');
    });
});

