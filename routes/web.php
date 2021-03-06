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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::get('/logout', 'Auth\LoginController@logout');

Route::get('/home', 'Misc\HomeController@index')->name('home');
Route::get('/signup', 'Misc\HomeController@index')->name('home')->middleware('guest');

// Logged in Routes
Route::group(['middleware' => 'auth'], function () {
    Route::get('/metronic', 'Misc\HomeController@metronic');
    Route::get('/fudge', 'Misc\HomeController@fudge');
    Route::get('/test', function () {return view('test');});
    Route::get('/test2', function () {return view('test2');});
    Route::get('/metronic', function () {return view('metronic');});
    Route::get('/metronic2', function () {return view('metronic2');});


    // Update Session
    Route::post('/uset', 'Auth\SessionController@update');

    // Protect access the Users photos + thumbs
    Route::get('/image/{aid}/{type}/{filename}', 'Misc\FileController@getPhoto'); //->where('filename', '^(.+)\/([^\/]+)$');
    Route::get('/log/{aid}/{type}/{filename}', 'Misc\FileController@getLog'); //->where('filename', '^(.+)\/([^\/]+)$');

    // Account Admins
    Route::get('/data/admins', 'Account\AdminController@getAdmins');
    Route::post('/account/admin/add-admin', 'Account\AdminController@addAdmin');
    Route::post('/account/admin/update-permissions/{id}', 'Account\AdminController@updatePermission');
    Route::resource('/account/admin', 'Account\AdminController');

    // Accounts
    Route::get('/account', 'Account\AccountController@profile');
    Route::get('/account/schools', 'Account\AccountController@schools');
    Route::get('/account/grades', 'Account\AccountController@grades');
    Route::get('/account/admins', 'Account\AccountController@admins');
    Route::resource('/account', 'Account\AccountController');

    // Grades
    Route::get('/data/grades', 'People\GradeController@getGrades');
    Route::resource('/settings/grade', 'People\GradeController');

    // Schools
    Route::get('/data/schools', 'People\SchoolController@getSchools');
    Route::get('/data/schools-by-grade/{gid}', 'People\SchoolController@schoolsByGrade');
    Route::get('/data/school/{sid}/grade/{gid}/link/{link}', 'People\SchoolController@linkSchool2Grade');
    Route::resource('/settings/school', 'People\SchoolController');


    // People
    Route::get('/data/people', 'People\PeopleController@getPeople');
    Route::get('/data/people/search-add', 'People\PeopleController@searchAddUser');
    Route::any('/people/{id}/medical', 'People\PeopleController@medical');
    Route::any('/people/{id}/activity', 'People\PeopleController@activity');
    Route::any('/people/{id}/notes', 'People\PeopleController@notes');
    Route::any('/people/{id}/status/{status}', 'People\PeopleController@status');
    Route::get('/people/{id}/del', 'People\PeopleController@destroy');
    Route::post('/people/{id}/photo', 'People\PeopleController@updatePhoto');
    Route::resource('/people', 'People\PeopleController');

    // Households
    Route::get('/data/household/members/{id}', 'People\HouseholdController@getMembers');
    Route::get('/data/household/households/{id}', 'People\HouseholdController@getHouseholds');
    Route::get('/household/{hid}/member/{pid}/add', 'People\HouseholdController@addMember');
    Route::get('/household/{hid}/member/{pid}/del', 'People\HouseholdController@delMember');
    Route::post('/household/update', 'People\HouseholdController@updateHousehold');
    Route::resource('/household', 'People\HouseholdController');

    // Activity
    Route::any('/data/activity', 'People\ActivityController@getActivity');
    Route::resource('/activity', 'People\ActivityController');


    // Events
    //Route::get('/data/event/dates/{id}', 'Event\EventController@getDates');
    Route::get('/data/event/people/{id}', 'Event\EventController@getPeople');
    Route::get('/event/{id}/settings/', 'Event\EventController@settings');
    Route::get('/event/{id}/attendance/{date}', 'Event\EventController@attendance');
    Route::any('/event/{id}/status/{status}', 'Event\EventController@status');
    Route::get('/event/{id}/del', 'Event\EventController@destroy');
    Route::post('/event/{id}/photo', 'Event\EventController@updatePhoto');
    Route::resource('/event', 'Event\EventController');

    // Event Instance
    Route::get('/event/instance/{id}/del', 'Event\EventInstanceController@destroy');
    Route::resource('/event/instance', 'Event\EventInstanceController');

    // Checkin
    Route::get('/data/checkin/people/{eid}', 'Event\CheckinController@getPeople');
    Route::get('/data/checkin/parents', 'Event\CheckinController@getParents');
    Route::get('/checkin/{id}/register/student', 'Event\CheckinController@studentForm');
    Route::post('/checkin/{id}/register/student', 'Event\CheckinController@studentRegister');
    Route::get('/checkin/{id}/register/volunteer', 'Event\CheckinController@volunteerForm');
    Route::post('/checkin/{id}/register/volunteer', 'Event\CheckinController@volunteerRegister');
    Route::resource('/checkin', 'Event\CheckinController');

    // Attendance
    Route::resource('/attendance', 'Event\AttendanceController');

    // Stats
    Route::any('/stats/event/{eid}/student/{uid}', 'Event\StatsController@studentAttendance');
    Route::any('/stats/event/weekly-totals', 'Event\StatsController@weekTotals');
    Route::any('/stats/event/compare-year/{years}', 'Event\StatsController@compareYear');

    // Reports
    Route::any('/report/nightly', 'Misc\ReportController@nightly');

    // Settings
    Route::get('/group', 'Misc\HomeController@group');
    Route::get('/quick', 'Misc\ImportController@quick');
    Route::get('/import-students', 'Misc\ImportController@importStudents');
    Route::get('/import-adults', 'Misc\ImportController@importAdults');
    Route::get('/import-events', 'Misc\ImportController@importEvents');
    Route::get('/create-people-history', 'Misc\ImportController@createPeopleHistory');
    Route::get('/form-households', 'Misc\ImportController@formHouseholds');
    Route::get('/copy-address', 'Misc\ImportController@copyAddress');
    Route::get('/copy-address/{from}/{to}', 'Misc\ImportController@copyAddressDone');
    Route::get('/outdated-students', 'Misc\ReportController@outdatedStudents');


    // Search
    Route::view('/search', 'search');
    Route::get('/user/find', 'Users\SearchController@searchUsers');
});