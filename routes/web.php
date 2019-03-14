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
    
    //LeaveType
    Route::group(['namespace'=>'LeaveType','prefix' => 'leavetype'], function () {
        $this->get('/', 'LeaveTypeController@index')->middleware('permission:list_leave_type')->name('indexLeaveType');
        $this->get('/new', 'LeaveTypeController@new')->middleware('permission:create_leave_type')->name('newLeaveType');
        $this->post('/new', 'LeaveTypeController@insert')->middleware('permission:create_leave_type');
        $this->get('/edit/{id_leave_type}', 'LeaveTypeController@edit')->middleware('permission:update_leave_type')->name('editLeaveType');
        $this->post('/edit/{id_leave_type}', 'LeaveTypeController@update')->middleware('permission:update_leave_type');
        $this->get('/delete/{id_leave_type}', 'LeaveTypeController@delete')->middleware('permission:delete_leave_type')->name('deleteLeaveType');
        $this->post('/delete/{id_leave_type}','LeaveTypeController@resolveContractAndDelete')->middleware('permission:delete_leave_type');
    });

    //Workday
    Route::group(['namespace'=>'Workday','prefix' => 'workday'], function () {
        $this->get('/', 'WorkdayController@index')->middleware('permission:list_workday')->name('indexWorkday');
        $this->get('/new', 'WorkdayController@new')->middleware('permission:create_workday')->name('newWorkday');
        $this->post('/new', 'WorkdayController@insert')->middleware('permission:create_workday');
        $this->get('/edit/{id_workday}', 'WorkdayController@edit')->middleware('permission:update_workday')->name('editWorkday');
        $this->post('/edit/{id_workday}', 'WorkdayController@update')->middleware('permission:update_workday');
        $this->get('/delete/{id_workday}', 'WorkdayController@delete')->middleware('permission:delete_workday')->name('deleteWorkday');
        $this->post('/delete/{id_workday}','WorkdayController@resolveContractAndDelete')->middleware('permission:delete_workday');
    });

    //Holiday
    Route::group(['namespace'=>'Holiday','prefix' => 'holiday'], function () {
        $this->get('/', 'HolidayController@index')->middleware('permission:list_holiday')->name('indexHoliday');
        $this->get('/new', 'HolidayController@new')->middleware('permission:create_holiday')->name('newHoliday');
        $this->post('/new', 'HolidayController@insert')->middleware('permission:create_holiday');
        $this->get('/edit/{id_holiday}', 'HolidayController@edit')->middleware('permission:update_holiday')->name('editHoliday');
        $this->post('/edit/{id_holiday}', 'HolidayController@update')->middleware('permission:update_holiday');
        $this->get('/delete/{id_holiday}', 'HolidayController@delete')->middleware('permission:delete_holiday')->name('deleteHoliday');
        $this->post('/delete/{id_holiday}','HolidayController@resolveContractAndDelete')->middleware('permission:delete_holiday');
    });

    //Employee
    Route::group(['namespace'=>'Employee','prefix' => 'employee'], function () {
        $this->get('/', 'EmployeeController@index')->middleware('permission:list_employee')->name('indexEmployee');
        $this->get('/new', 'EmployeeController@new')->middleware('permission:create_employee')->name('newEmployee');
        $this->post('/new', 'EmployeeController@insert')->middleware('permission:create_employee');
        $this->get('/edit/{id_employee}', 'EmployeeController@edit')->middleware('permission:update_employee')->name('editEmployee');
        $this->post('/edit/{id_employee}', 'EmployeeController@update')->middleware('permission:update_employee');
        $this->get('/delete/{id_employee}', 'EmployeeController@delete')->middleware('permission:delete_employee')->name('deleteEmployee');
        $this->post('/delete/{id_employee}','EmployeeController@resolveContractAndDelete')->middleware('permission:delete_employee');
    });

    //LeaveRequest
    Route::group(['namespace'=>'LeaveRequest','prefix' => 'leave'], function () {
        $this->get('/', 'LeaveRequestController@index')->middleware('permission:list_leave_request')->name('indexLeaveRequest');
        $this->get('/new', 'LeaveRequestController@new')->middleware('permission:create_leave_request')->name('newLeaveRequest');
        $this->post('/new', 'LeaveRequestController@insert')->middleware('permission:create_leave_request');

        $this->get('/edit/{id_leave_request}', 'LeaveRequestController@edit')->middleware('permission:update_leave_request')->name('editLeaveRequest');
        $this->post('/edit/{id_leave_request}', 'LeaveRequestController@update')->middleware('permission:update_leave_request');

        $this->get('/show/{id_leave_request}','LeaveRequestController@show')->middleware('permission:list_leave_request')->name('showLeaveRequest');

        $this->get('/accept/{id_leave_request}', 'LeaveRequestController@accept')->middleware('permission:accept_leave_request')->name('acceptLeaveRequest');

        $this->get('/denny/{id_leave_request}', 'LeaveRequestController@showDennyForm')->middleware('permission:denny_leave_request')->name('showDennyLeaveRequestForm');
        $this->post('/denny/{id_leave_request}', 'LeaveRequestController@denny')->middleware('permission:denny_leave_request');
    });
});

