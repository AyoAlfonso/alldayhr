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

//Front routes start
// Admin routes
Route::group(
    ['namespace' => 'Front', 'as' => 'jobs.'],
    function () {
        Route::get('/', 'FrontJobsController@jobOpenings')->name('jobOpenings');
        Route::get('/job/{slug}', 'FrontJobsController@jobDetail')->name('jobDetail');
        Route::get('/job/category/search', 'FrontJobsController@getJobCategories')->name('getJobCategories');
        Route::post('/job/category/search', 'FrontJobsController@searchJobCategories')->name('searchJobCategories');
       
        Route::group(['middleware' => 'auth:candidate'], function () {
            Route::get('/job/{slug}/apply', ['uses' => 'FrontJobsController@jobApply'])->name('jobApply');
            Route::post('/job/saveApplication', 'FrontJobsController@saveApplication')->name('saveApplication');
        });
    }
);
//Refactor, should login as candidate to access this.s
Route::group(
    ['namespace' => 'Candidate', 'as' => 'profile.', 'middleware' => 'auth:candidate'],
    function () {
        Route::get('/candidate/profile', 'CandidateProfileController@show')->name('candidateProfile');
        Route::get('/candidate/dashboard', 'CandidateDashboardController@show')->name('candidateDashboard');
    }
);

//Front routes end
// Candidate routes
Route::group(
    ['namespace' => 'Auth', 'as' => 'candidate.', 'middleware' => ['guest_candidate', 'guest']],
    function () {
        Route::post('/candidate/signup', ['as' => 'candidatesignup', 'uses' => 'RegisterController@createCandidate']);
        Route::get('/candidate/signup', ['as' => 'candidatesignup', 'uses' => 'RegisterController@showCandidateRegisterForm']);
        Route::post('/candidate/login', ['as' => 'candidatelogin', 'uses' => 'LoginController@candidateLogin']);
        Route::get('/candidate/login', ['as' => 'candidatelogin', 'uses' => 'LoginController@showCandidateLoginForm']);
        Route::get('/candidate/verification', ['as' => 'candidateverification', 'uses' => 'RegisterController@verification']);

        Route::get('/candidate/verify-account', ['as' => 'verify-acc', 'uses' => 'RegisterController@onClickVerificationLink']);

        Route::get('/candidate/resend-verify-account', ['as' => 'resend-verify-acc', 'uses' => 'RegisterController@resendVerification']);
        Route::get('/candidate/verify-account/{token}', ['as' => 'verify-account', 'uses' => 'RegisterController@onClickVerificationLink']);

        Route::get('/candidate/forgot-password', ['as' => 'forgot-password', 'uses' => 'ResetPasswordController@beginResetPass']);
        Route::post('/candidate/forgot-password', ['as' => 'forgot-password', 'uses' => 'ResetPasswordController@getResetToken']);
        Route::get('/candidate/resend-recover-pass/', ['as' => 'resend-recover-pass', 'uses' => 'ResetPasswordController@resendResetPass']);
        Route::get('/candidate/recover-password/{token}', ['as' => 'recover-password', 'uses' => 'ResetPasswordController@getChangePass']);

        //Dummy
        Route::get('/candidate/recover-password', ['as' => 'recover-pass-notoken', 'uses' => 'ResetPasswordController@getChangePass']);

        Route::post('/candidate/recover-password/{token}', ['as' => 'recover-pass', 'uses' => 'ResetPasswordController@onClickPassResetLink']);
        Route::get('/candidate/reset-pass-success', ['uses' => 'ResetPasswordController@showSuccessPage']);
    }
);

//Auth::routes();
// Admin routes
Route::get('/login', ['uses' => 'Admin\AdminLoginController@getLogin'])->name('login');
Route::post('/login', ['uses' => 'Admin\AdminLoginController@postLogin'])->name('login');

// Password Reset Routes...
Route::get('password/reset', ['as' => 'password.reset', 'uses' => 'Auth\ForgotPasswordController@showLinkRequestForm']);
Route::post('password/email', ['as' => 'password.email', 'uses' => 'Auth\ForgotPasswordController@sendResetLinkEmail']);
Route::get('password/reset/{token}', ['as' => 'password.reset.token', 'uses' => 'Auth\ResetPasswordController@showResetForm']);
Route::post('password/reset', ['as' => 'password.reset.post', 'uses' => 'Auth\ResetPasswordController@reset']);

Route::group(['middleware' => 'auth'], function () {
    Route::get('logout', ['as' => 'logout', 'uses' => 'Admin\AdminLoginController@logout']);
    Route::post('logout', ['as' => 'logout', 'uses' => 'Admin\AdminLoginController@logout']);

    Route::post('mark-notification-read', ['uses' => 'NotificationController@markAllRead'])->name('mark-notification-read');

    // Admin routes
    Route::group(
        ['namespace' => 'Admin', 'prefix' => 'admin', 'as' => 'admin.'],
        function () {
            Route::get('/dashboard', 'AdminDashboardController@index')->name('dashboard');

            Route::get('job-categories/data', 'AdminJobCategoryController@data')->name('job-categories.data');
            Route::get('job-categories/getSkills/{categoryId}', 'AdminJobCategoryController@getSkills')->name('job-categories.getSkills');
            Route::resource('job-categories', 'AdminJobCategoryController');

            //Questions
            Route::get('questions/data', 'AdminQuestionController@data')->name('questions.data');
            Route::resource('questions', 'AdminQuestionController');

            // company settings
            Route::group(
                ['prefix' => 'settings'],
                function () {

                    Route::resource('settings', 'CompanySettingsController', ['only' => ['edit', 'update', 'index']]);

                    // Role permission routes
                    Route::resource('settings', 'CompanySettingsController', ['only' => ['edit', 'update', 'index']]);
                    Route::post('role-permission/assignAllPermission', ['as' => 'role-permission.assignAllPermission', 'uses' => 'ManageRolePermissionController@assignAllPermission']);
                    Route::post('role-permission/removeAllPermission', ['as' => 'role-permission.removeAllPermission', 'uses' => 'ManageRolePermissionController@removeAllPermission']);
                    Route::post('role-permission/assignRole', ['as' => 'role-permission.assignRole', 'uses' => 'ManageRolePermissionController@assignRole']);
                    Route::post('role-permission/detachRole', ['as' => 'role-permission.detachRole', 'uses' => 'ManageRolePermissionController@detachRole']);
                    Route::post('role-permission/storeRole', ['as' => 'role-permission.storeRole', 'uses' => 'ManageRolePermissionController@storeRole']);
                    Route::post('role-permission/deleteRole', ['as' => 'role-permission.deleteRole', 'uses' => 'ManageRolePermissionController@deleteRole']);
                    Route::get('role-permission/showMembers/{id}', ['as' => 'role-permission.showMembers', 'uses' => 'ManageRolePermissionController@showMembers']);
                    Route::resource('role-permission', 'ManageRolePermissionController');

                    //language settings
                    Route::get('language-settings/change-language', ['uses' => 'LanguageSettingsController@changeLanguage'])->name('language-settings.change-language');
                    Route::resource('language-settings', 'LanguageSettingsController');

                    Route::resource('theme-settings', 'AdminThemeSettingsController');

                    Route::resource('smtp-settings', 'AdminSmtpSettingController');

                    Route::get('update-application/update', ['as' => 'update-application.updateApp', 'uses' => 'UpdateApplicationController@update']);
                    Route::get('update-application/download', ['as' => 'update-application.download', 'uses' => 'UpdateApplicationController@download']);
                    Route::get('update-application/downloadPercent', ['as' => 'update-application.downloadPercent', 'uses' => 'UpdateApplicationController@downloadPercent']);
                    Route::get('update-application/checkIfFileExtracted', ['as' => 'update-application.checkIfFileExtracted', 'uses' => 'UpdateApplicationController@checkIfFileExtracted']);
                    Route::get('update-application/install', ['as' => 'update-application.install', 'uses' => 'UpdateApplicationController@install']);
                    Route::resource('update-application', 'UpdateApplicationController');
                }
            );


            Route::get('skills/data', 'AdminSkillsController@data')->name('skills.data');
            Route::resource('skills', 'AdminSkillsController');

            Route::get('locations/data', 'AdminLocationsController@data')->name('locations.data');
            Route::resource('locations', 'AdminLocationsController');

            Route::get('jobs/data', 'AdminJobsController@data')->name('jobs.data');
            Route::resource('jobs', 'AdminJobsController');
            Route::post('jobs/store-settings/{id}', 'AdminJobsController@storeSettings')->name('jobs.store-settings');

            Route::get('job-assessment/data', 'AdminAssessmentController@getJobsData')->name('candidate-assessment.data');
            Route::post('job-assessment/create-test-takers', 'AdminAssessmentController@createTestTakers')->name('candidate-assessment.createTestTakers');
            Route::post('job-assessment/send-tt-login', 'AdminAssessmentController@sendTTLogin')->name('candidate-assessment.sendTTLogin');

            Route::get('job-assessment', 'AdminAssessmentController@getJobs')->name('candidate-assessment.get-jobs');
            Route::get('job-assessment/tt/data', 'AdminAssessmentController@getTestTakersData')->name('candidate-assessment.test-takers-data');
            Route::get('job-assessment/tt/{id?}', 'AdminAssessmentController@getTestTakers')->name('candidate-assessment.get-test-takers');
            Route::post('job-assessment/store-test-takers/{id}', 'AdminAssessmentController@getTestPlatformStatus')->name('candidate-assessment.store-test-takers');
            Route::get('job-assessment/get-tests-on-Tao', 'AdminAssessmentController@getTestsOnTao')->name('candidate-assessment.get-tests-templates');
            Route::post('job-assessment/get-tao-tt', 'AdminAssessmentController@getTestTakersOnTao')->name('candidate-assessment.get-test-takers-on-tao');
            Route::post('job-assessment/create-delivery-on-tao', 'AdminAssessmentController@createDeliveryOnTao')->name('candidate-assessment.create-delivery-on-tao');
            Route::post('job-assessment/delete-delivery-on-tao', 'AdminAssessmentController@deleteDeliveryOnTao')->name('candidate-assessment.del-delivery-on-tao');

            Route::post('job-applications/update-status/{id?}', 'AdminJobApplicationController@updateJobAppStatusById')->name('job-applications.update-status');
            Route::post('job-applications/rating-save/{id?}', 'AdminJobApplicationController@ratingSave')->name('job-applications.rating-save');
            Route::get('job-applications/create-schedule/{id?}', 'AdminJobApplicationController@createSchedule')->name('job-applications.create-schedule');
            Route::post('job-applications/store-schedule', 'AdminJobApplicationController@storeSchedule')->name('job-applications.store-schedule');
            Route::get('job-applications/question/{jobID}', 'AdminJobApplicationController@jobQuestion')->name('job-applications.question');
            Route::get('job-applications/export/{status}/{location}/{startDate}/{endDate}/{jobs}/{type}', 'AdminJobApplicationController@export')->name('job-applications.export');
            Route::get('job-applications/data', 'AdminJobApplicationController@data')->name('job-applications.data');
            Route::get('job-applications/table-view', 'AdminJobApplicationController@table')->name('job-applications.table');
            Route::post('job-applications/updateIndex', 'AdminJobApplicationController@updateIndex')->name('job-applications.updateIndex');
            Route::resource('job-applications', 'AdminJobApplicationController');

            Route::get('job-applications/company/{id}', 'AdminJobApplicationController@singleCompany')->name('job-applications.singleCompany');
            Route::get('job-applications/job/{id}', 'AdminJobApplicationController@singleJob')->name('job-applications.singleJob');
          
            Route::resource('profile', 'AdminProfileController');

            Route::get('interview-schedule/data', 'InterviewScheduleController@data')->name('interview-schedule.data');
            Route::get('interview-schedule/table-view', 'InterviewScheduleController@table')->name('interview-schedule.table-view');
            Route::post('interview-schedule/change-status', 'InterviewScheduleController@changeStatus')->name('interview-schedule.change-status');
            Route::post('interview-schedule/change-status-multiple', 'InterviewScheduleController@changeStatusMultiple')->name('interview-schedule.change-status-multiple');
            Route::get('interview-schedule/notify/{id}/{type}', 'InterviewScheduleController@notify')->name('interview-schedule.notify');
            Route::get('interview-schedule/response/{id}/{type}', 'InterviewScheduleController@employeeResponse')->name('interview-schedule.response');
            Route::resource('interview-schedule', 'InterviewScheduleController');

            Route::get('team/data', 'AdminTeamController@data')->name('team.data');
            Route::post('team/change-role', 'AdminTeamController@changeRole')->name('team.changeRole');
          
            Route::get('candidate/signup', 'AdminTeamController@showCandidateRegisterForm')->name('team.createCandidate');
            Route::get('candidate/login', 'AdminTeamController@showCandidateLoginForm')->name('team.candidatelogin');
            // Route::post('candidate/login', 'AdminTeamController@candidateLogin')->name('team.candidatelogin');
            // Route::post('candidate/create', 'AdminTeamController@createCandidate')->name('team.createCandidate');

            Route::resource('team', 'AdminTeamController');

            Route::get('company/data', 'AdminCompanyController@data')->name('company.data');
            Route::resource('company', 'AdminCompanyController');
        }
    );
});

/*Route::get('/candidate/dashboard', ['middleware' => 'auth', 'uses' => 'DashboardController@showDashboardPage']);*/
Route::get('/candidate/list', ['middleware' => 'auth', 'as' => 'admin.showCandidatePool', 'uses' => 'DashboardController@showCandidatesPage']);
Route::get('/candidate/list/data', ['middleware' => 'auth', 'as' => 'admin.showCandidatePoolData', 'uses' => 'DashboardController@showCandidatesData']);
Route::get('/candidate/download-candidate-data', ['as' => 'admin.downloadCandidateData', 'uses' => 'DashboardController@downloadCandidateCSV']);

Route::get('/candidate/organisation-jobs', ['as' => 'admin.getOrganisationJobs', 'uses' => 'DashboardController@getOrganisationJobs']);
Route::get('/candidate/profile/{id}', ['middleware' => 'auth', 'as' => 'admin.getCandidateProfile', 'uses' => 'DashboardController@getCandidateProfile']);

Route::post('/candidate/assign-pool-job-candidate', ['as' => 'admin.assignjobtocandidate', 'uses' => 'DashboardController@assignjobtocandidate']);
Route::post('/candidate/send-candidate-mail', ['as' => 'admin.sendemailtocandidate', 'uses' => 'DashboardController@sendemailtocandidate']);
Route::post('/candidate/shortlist-candidates', ['as' => 'admin.shortlistcandidate', 'uses' => 'DashboardController@shortlistcandidate']);


Route::get('/candidate/testMail', ['uses' => 'DashboardController@testMail']);
Route::get('/candidate/logout', ['middleware' => 'auth:candidate', 'uses' => 'DashboardController@logout', 'as' => 'candidate.logout']);
Route::get('update-database', function () {
    \Illuminate\Support\Facades\Artisan::call('migrate', array('--force' => true));

    return 'Database updated successfully. <a href="' . route('login') . '">Click here to Login</a>';
});

Route::group(['prefix' => 'api/v1/candidate'], function ($router) {
    Route::group(['prefix' => 'general'], function ($router) {
        $router->get('states', function (){
            return \App\Helpers\General::getstates();
        });
        $router->get('nationalities', function (){
            return \App\Helpers\General::getNationalities();
        });
        $router->get('lga/{state}', 'GeneralController@getLGAs');
        $router->get('grades', 'GeneralController@getGrades');
        $router->get('degrees', 'GeneralController@getDegrees');
        $router->get('industries', function (){
            return \App\Helpers\General::getIndustries();
        });
        $router->get('universities', function (){
            return \App\Helpers\General::getUniversities();
        });
        $router->get('languages', function (){
            return \App\Helpers\General::getLanguages();
        });
    });
    Route::post('login', ['uses' => 'Auth\LoginController@candidateLoginAPI']);


    Route::group(['middleware' => 'auth:candidate'], function ($router) {
        $router->get('profile', 'Candidate\API\CandidateProfileController@getProfileInfo');
        $router->post('profile/update/image', 'Candidate\API\CandidateProfileController@postUpdateImage');
        $router->post('profile/update/info', 'Candidate\API\CandidateProfileController@postProfileInfo');
        $router->post('profile/update/other', 'Candidate\API\CandidateProfileController@postProfileOther');
        $router->post('profile/update/cv', 'Candidate\API\CandidateProfileController@postResume');
        $router->post('profile/create/education', 'Candidate\API\CandidateProfileController@postCreateEducation');
        $router->post('profile/update/education', 'Candidate\API\CandidateProfileController@postUpdateEducation');
        $router->delete('profile/delete/education/{education}', 'Candidate\API\CandidateProfileController@deleteEducation');
        $router->post('profile/create/work', 'Candidate\API\CandidateProfileController@postCreateWork');
        $router->post('profile/update/work', 'Candidate\API\CandidateProfileController@postUpdateWork');
        $router->delete('profile/delete/work/{work}', 'Candidate\API\CandidateProfileController@deleteWork');
        $router->post('profile/create/document', 'Candidate\API\CandidateProfileController@postCreateDocument');
        $router->delete('profile/delete/document/{document}', 'Candidate\API\CandidateProfileController@deleteDocument');
        $router->post('profile/create/olevel', 'Candidate\API\CandidateProfileController@postCreateOlevel');
        $router->post('profile/update/olevel', 'Candidate\API\CandidateProfileController@postUpdateOlevel');
        $router->delete('profile/delete/olevel/{olevel}', 'Candidate\API\CandidateProfileController@deleteOlevel');

    });
});

