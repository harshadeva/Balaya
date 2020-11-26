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

//test
//Route::get('/linkstorage', function () {
//    Artisan::call('storage:link')
//});

Route::get('test_sms123', function (){
    $client = new \GuzzleHttp\Client();
    $res = $client->get("https://smsserver.textorigins.com/Send_sms?src=CYCLOMAX236&email=cwimagefactory@gmail.com&pwd=cwimagefactory&msg=testSms-Harsha&dst=0717275539");
    return json_decode($res->getBody(), true);
})->name('testSms');

Auth::routes();

Route::get('/signin', function () {
    return view('signin');
})->name('signin')->middleware('guest');

Route::get('/home', 'HomeController@index')->name('home');
Route::post('customLogin', 'UserController@login')->name('customLogin');
Route::get('privacy', function (){
    return view('privacy.privacy');
})->name('privacy');


Route::group(['middleware' => 'auth', 'prefix' => ''], function () {
    Route::group(['middleware' => 'isActive', 'prefix' => ''], function () {
        Route::group(['middleware' => 'setLanguage', 'prefix' => ''], function () {

            //common
            Route::get('/', 'DashboardController@index')->name('dashboard');
            Route::get('view_post', 'PostController@view')->name('viewPost')->middleware('postView');

            Route::group(['middleware' => 'hierarchy', 'prefix' => ''], function () {

                //Council management
                Route::get('council', 'CouncilController@index')->name('council');
                Route::get('council_view', 'CouncilController@view')->name('council-view');

                //Divisional secretariat management
                Route::get('divisional_secretariat', 'DivisionalSecretariatController@index')->name('divisionalSecretariat');
                Route::get('divisional_secretariat_view', 'DivisionalSecretariatController@view')->name('divisionalSecretariat-view');

                //Village
                Route::get('village', 'VillageController@index')->name('village');

                //Gramasewa Division
                Route::get('gramasewa_division', 'GramasewaDivisionController@index')->name('gramasewaDivision');

                //Polling Booth
                Route::get('member_division', 'PollingBoothController@index')->name('pollingBooth');

                //Election Division
                Route::get('election_division', 'ElectionDivisionController@index')->name('electionDivision');

            });

            Route::group(['middleware' => 'mediaStaff', 'prefix' => ''], function () {

                //Post
                Route::get('create_post', 'PostController@index')->name('createPost');

                //Analysis management
                Route::get('pending_response', 'ResponseAnalysisController@index')->name('pendingResponse');

                //SMS
                Route::get('save_group_sms', 'SmsController@saveGroupSms')->name('saveGroupSms');
                Route::get('create_sms', 'SmsController@create')->name('createSms');
                Route::get('send_group', 'SmsController@sendGroup')->name('sendGroup');

            });

            Route::group(['middleware' => 'mediaHead', 'prefix' => ''], function () {

                //Post
                Route::get('pending_posts', 'PostController@pending')->name('pendingPosts');
                Route::get('active_posts', 'PostController@active')->name('activePosts');
                Route::get('view_posts_by_category', 'PostController@viewByCategory')->name('viewPostsByCategory');
                Route::get('rejected_posts', 'PostController@rejected')->name('rejectedPosts');

                //SMS
                Route::get('welcome_message', 'SmsController@index')->name('welcomeMessage');
                Route::get('rejected_sms', 'SmsController@rejectedSms')->name('rejectedSms');
                Route::get('sent_sms', 'SmsController@sentSms')->name('sentSms');
                Route::get('pending_sms', 'SmsController@pending')->name('pendingSms');

                //Staff management
                Route::get('assign_staff', 'StaffController@index')->name('assignStaff');

                //Comments
                Route::get('pending_comments', 'PostResponseController@pending')->name('pendingComments');

            });


            Route::group(['middleware' => 'dataEntry', 'prefix' => ''], function () {

                //SMS
                Route::get('create_sms_group', 'SmsController@createGroup')->name('createSmsGroup')->middleware('onlyDataEntry');
                Route::get('add_contacts', 'SmsController@addContacts')->name('addContacts')->middleware('onlyDataEntry');

                //Canvassing
                Route::get('create_canvassing', 'CanvassingController@create')->name('createCanvassing')->middleware('onlyDataEntry');
                Route::get('canvassing_type', 'CanvassingController@canvassingType')->name('canvassingType')->middleware('onlyDataEntry');
                Route::post('add_temporary_village', 'CanvassingController@addVillageTemporary')->name('addVillageToCanvassing')->middleware('onlyDataEntry');
                Route::post('loadVillageTemp', 'CanvassingController@loadVillageTemp')->name('loadVillageTemp')->middleware('onlyDataEntry');
                Route::post('deleteVillageTempCanvassing', 'CanvassingController@deleteVillageTemp')->name('deleteVillageTempCanvassing')->middleware('onlyDataEntry');
                Route::post('saveCanvassing', 'CanvassingController@saveCanvassing')->name('saveCanvassing')->middleware('onlyDataEntry');
                Route::post('saveCanvassingType', 'CanvassingController@saveCanvassingType')->name('saveCanvassingType')->middleware('onlyDataEntry');
                Route::post('getCanvassingTypeByOffice', 'CanvassingController@getCanvassingTypeByOffice')->name('getCanvassingTypeByOffice')->middleware('onlyDataEntry');
                Route::post('deleteCanvassingType', 'CanvassingController@deleteCanvassingType')->name('deleteCanvassingType')->middleware('onlyDataEntry');
                Route::post('updateCanvassingType', 'CanvassingController@updateCanvassingType')->name('updateCanvassingType')->middleware('onlyDataEntry');
                Route::post('deleteCanvassingTempValues', 'CanvassingController@deleteTempValues')->name('deleteCanvassingTempValues')->middleware('onlyDataEntry');

                Route::get('unapproved_canvassing', 'CanvassingController@unapproved')->name('upApprovedCanvassing');
                Route::get('pending_canvassing', 'CanvassingController@pending')->name('pendingCanvassing');
                Route::get('rejected_canvassing', 'CanvassingController@rejected')->name('rejectedCanvassing');
                Route::post('finished_canvassing', 'CanvassingController@finishedTable')->name('finishedCanvassing');
            });

            Route::group(['middleware' => 'officeAdmin', 'prefix' => ''], function () {

                //Budget
                Route::get('assign_budget', 'TaskController@index')->name('assignTask');
                Route::get('default_estimation', 'TaskController@createDefault')->name('createDefaultTask');
                Route::get('view_budget', 'TaskController@view')->name('viewTasks');

                //user management
                Route::get('add_user', 'UserController@index')->name('addUser');
                Route::get('pending_requests', 'UserController@viewPendingAgents')->name('pendingAgents');
                Route::get('view_users', 'UserController@view')->name('viewUser');

                //Canvassing
                Route::post('approveCanvassing', 'CanvassingController@approve')->name('approveCanvassing');
                Route::post('rejectCanvassing', 'CanvassingController@reject')->name('rejectCanvassing');
                Route::get('finished_canvassing', 'CanvassingController@finished')->name('finishedCanvassing');

            });

            //Screen management
            Route::get('canvassing_screen_pending', 'ScreenController@pending')->name('pendingCanvassingScreen');
            Route::get('canvassing_screen_today', 'ScreenController@today')->name('todayCanvassingScreen');
            Route::get('canvassing_screen_ongoing', 'ScreenController@ongoing')->name('ongoingCanvassingScreen');
            Route::post('pendingScreenTable', 'ScreenController@pendingTable')->name('pendingScreenTable');
            Route::post('todayScreenTable', 'ScreenController@todayTable')->name('todayScreenTable');
            Route::post('ongoingScreenTable', 'ScreenController@ongoingTable')->name('ongoingScreenTable');
            Route::get('canvassing_route_map', 'ScreenController@routeOnMap')->name('canvassingRouteOnMap');
            //Screen management end

            Route::group(['middleware' => 'superAdmin', 'prefix' => ''], function () {

                //SMS
                Route::get('sms_configuration', 'SmsController@config')->name('smsConfiguration');

                //office management
                Route::get('add_office', 'OfficeController@index')->name('addOffice');
                Route::get('view_offices', 'OfficeController@view')->name('viewOffice');

                //category management
                Route::get('add_category', 'CategoryController@index')->name('addCategory');
                Route::get('view_category', 'CategoryController@view')->name('viewCategory');

                //payment management
                Route::get('add_payment', 'PaymentController@index')->name('addPayment');
                Route::get('view_payment', 'PaymentController@view')->name('viewPayments');
                Route::get('view_outstanding_payment', 'PaymentController@viewOutstanding')->name('viewOutstandingPayments');

                //setting
                Route::get('app_version', 'GeneralController@appVersion')->name('appVersion');
            });

//            Route::group(['middleware' => 'analyzer', 'prefix' => ''], function () {

            //Canvassing
            Route::post('getVotingData', 'CanvassingController@getVotingData')->name('getVotingData');

                //Report management
                Route::get('report_category_wise', 'ReportController@categoryWise')->name('report-categoryWise');
                Route::get('report_location_wise', 'ReportController@locationWise')->name('report-locationWise');

                //Generic Reports
                Route::get('agents_report', 'GenericReportController@agents')->name('report-agents');
                Route::get('members_report', 'GenericReportController@members')->name('report-members');
                Route::get('age_report', 'GenericReportController@age')->name('report-age');
                Route::get('education_qualification_report', 'GenericReportController@education')->name('report-education');
                Route::get('career_report', 'GenericReportController@career')->name('report-career');
                Route::get('religion_report', 'GenericReportController@religion')->name('report-religion');
                Route::get('income_report', 'GenericReportController@income')->name('report-income');
                Route::get('ethnicity_report', 'GenericReportController@ethnicity')->name('report-ethnicity');
                Route::get('voters_report', 'GenericReportController@voters')->name('report-voters');
                Route::get('post_response_summery', 'GenericReportController@communityResponseSummery')->name('report-post-response');
//                Route::get('post_response_summery', 'GenericReportController@postResponses')->name('report-post-response');
//                Route::get('report-community-response', 'GenericReportController@communityResponseSummery')->name('report-community-response');
//            });

            //Direct Messages
            Route::get('direct_messages', 'DirectMessageController@index')->name('directMessages');

            //Attendance management
            Route::get('create_event', 'EventController@index')->name('create-event');
            Route::get('view_events', 'EventController@view')->name('view-events');

            //user management
            Route::post('save_user', 'UserController@store')->name('saveUser');
            Route::post('edit_users', 'UserController@edit')->name('editUser');
            Route::post('update_user', 'UserController@update')->name('updateUser');
            Route::post('my_profile', 'UserController@profile')->name('myProfile');
            Route::post('get_user_by_id', 'UserController@getById')->name('getUserById');
            Route::post('approve_agent', 'UserController@approveAgent')->name('approveAgent');
            Route::post('disable_user', 'UserController@disable')->name('disableUser');
            Route::post('enable_user', 'UserController@enable')->name('enableUser');
            Route::post('delete_user', 'UserController@deleteUser')->name('deleteUser');
            Route::post('auto_approve_member', 'UserController@autoMember')->name('autoApproveMember');
            Route::post('auto_approve_agent', 'UserController@autoAgent')->name('autoApproveAgent');
            Route::post('get_members_by_agent', 'UserController@getMemberByAgent')->name('getMembersByAgent');

            //office management
            Route::post('save_office', 'OfficeController@store')->name('saveOffice');
            Route::post('get_office_by_id', 'OfficeController@getById')->name('getOfficeById');
            Route::post('update_office', 'OfficeController@update')->name('updateOffice');
            Route::post('disable_office', 'OfficeController@disable')->name('disableOffice');
            Route::post('enable_office', 'OfficeController@enable')->name('enableOffice');

            //category management
            Route::post('get_sub_cat_by_main', 'CategoryController@getSubCatByMain')->name('getSubCatByMain');
            Route::post('load_category_recent', 'CategoryController@loadRecent')->name('loadCategoryRecent');
            Route::post('view_categories', 'CategoryController@getCatBySub')->name('viewCategories');
            Route::post('save_category', 'CategoryController@store')->name('saveCategory');
            Route::post('update_category', 'CategoryController@update')->name('updateCategory');
            Route::post('deactivate_category', 'CategoryController@deactivate')->name('deactivateCategory');
            Route::post('activate_category', 'CategoryController@activate')->name('activateCategory');

            //payment management
            Route::post('get_rental_by_office', 'PaymentController@getRentalByOffice')->name('getRentalByOffice');
            Route::post('save_payment', 'PaymentController@store')->name('savePayment');

            //Task
            Route::post('get_budget_by_id', 'TaskController@getById')->name('getTaskById');
            Route::post('save_budget', 'TaskController@storeDefault')->name('saveTask');
            Route::post('deactivate_budget', 'TaskController@deactivate')->name('deactivateTask');
            Route::post('activate_budget', 'TaskController@activate')->name('activateTask');
            Route::get('view_estimation', 'TaskController@view')->name('viewBudget');
            Route::post('view_budget_by_type', 'TaskController@viewByType')->name('viewBudgetByType');
            Route::post('is_default_budget_created', 'TaskController@isDefaultBudgetCreated')->name('isDefaultBudgetCreated');

            //Staff management
            Route::post('assign_staff', 'StaffController@store')->name('assignStaff');
            Route::post('view_assigned_division', 'StaffController@viewAssignedDivision')->name('viewAssignedDivision');

            //Direct Messages
            Route::post('load_message_users', 'DirectMessageController@loadUsers')->name('loadMessageUsers');
            Route::post('get_messages_by_user', 'DirectMessageController@getByUser')->name('getMessagesByUser');
            Route::post('save_message_attachments', 'DirectMessageController@storeAttachments')->name('saveMessageAttachments');
            Route::post('save_message', 'DirectMessageController@store')->name('saveMessage');
            Route::post('get_message_by_user', 'DirectMessageController@getMessageByUser')->name('getMessageByUser');

            //Report management
            Route::post('report_category_wise', 'ReportController@categoryWiseChart')->name('report-categoryWise');
            Route::post('report_location_wise', 'ReportController@locationWiseChart')->name('report-locationWise');
            Route::post('report_category_data', 'ReportController@getCategoryData')->name('report-category_data');

            //Generic Reports
            Route::post('age_report', 'GenericReportController@ageChart')->name('report-age');
            Route::post('education_qualification_report', 'GenericReportController@educationChart')->name('report-education');
            Route::post('income_report', 'GenericReportController@incomeChart')->name('report-income');
            Route::post('career_report', 'GenericReportController@careerChart')->name('report-career');
            Route::post('religion_report', 'GenericReportController@religionChart')->name('report-religion');
            Route::post('ethnicity_report', 'GenericReportController@ethnicityChart')->name('report-ethnicity');
            Route::post('voters_report', 'GenericReportController@votersChart')->name('report-voters');
            Route::post('get_community_category_values', 'GenericReportController@getCommunityCategoryValues')->name('getCommunityCategoryValues');

            //Canvassing Reports
            Route::get('voters_count_report', 'CanvassingReportController@votersCount')->name('report-votersCount');
            Route::get('canvassing_compare_report', 'CanvassingReportController@compare')->name('report-compareCanvassing');
            Route::post('voters_count_report', 'CanvassingReportController@votersCountChart')->name('report-votersCount');
            Route::post('canvassing_compare_report', 'CanvassingReportController@compareChart')->name('report-compareCanvassing');

            //Attendance management
            Route::post('save_event', 'EventController@store')->name('save-event');

            //Dashboard
            Route::post('dashboard-getStorage', 'DashboardController@getStorage')->name('dashboard-getStorage');

            //SMS
            Route::post('updateSmsLimit', 'SmsController@limit')->name('updateSmsLimit');
            Route::post('saveWelcomeSms', 'SmsController@saveWelcome')->name('saveWelcomeSms');
            Route::post('create_sms', 'SmsController@sendBulk')->name('createSms');
            Route::post('get_number_of_users', 'SmsController@getNumberOfReceivers')->name('getNumberOfReceivers');
            Route::post('get_number_of_users_group', 'SmsController@getNumberOfReceiversGroup')->name('getNumberOfReceiversGroup');
            Route::post('get_group_by_office', 'SmsController@getGroupByOffice')->name('getSmsGroupByOffice');
            Route::post('save_sms_group', 'SmsController@storeGroup')->name('saveSmsGroup');
            Route::post('update_sms_group', 'SmsController@updateGroup')->name('updateSmsGroup');
            Route::post('delete_sms_group', 'SmsController@deleteGroup')->name('deleteGroup');
            Route::post('get_contact_by_group', 'SmsController@getContactByGroup')->name('getContactByGroup');
            Route::post('save_contact', 'SmsController@storeContact')->name('saveContact');
            Route::post('update_contact', 'SmsController@updateContact')->name('updateContact');
            Route::post('delete_contact', 'SmsController@deleteContact')->name('deleteContact');

            Route::post('storeSms/{type}', 'SmsController@store')->name('storeSms');
            Route::post('send_sms', 'SmsController@sendApproved')->name('sendSms');
            Route::post('reject_sms', 'SmsController@rejectSms')->name('rejectSms');

            //setting
            Route::post('app_version', 'GeneralController@storeAppVersion')->name('storeAppVersion');

            //Election Division
            Route::post('get_election_division_by_auth', 'ElectionDivisionController@getByAuth')->name('getElectionDivisionsByAuth');
            Route::post('get_election_division_by_district', 'ElectionDivisionController@getByDistrict')->name('getElectionDivisionsByDistrict');
            Route::post('save_election_division', 'ElectionDivisionController@store')->name('saveElectionDivision');
            Route::post('update_election_division', 'ElectionDivisionController@update')->name('updateElectionDivision');
            Route::post('confirm_election_divisions', 'ElectionDivisionController@confirm')->name('confirmElectionDivisions');
            Route::post('delete_election_division', 'ElectionDivisionController@deleteRecord')->name('deleteElectionDivision');

            //Polling Booth
            Route::post('get_polling_booth_by_auth', 'PollingBoothController@getByAuth')->name('getPollingBoothByAuth');
            Route::post('get_polling_booth_by_election_division', 'PollingBoothController@getByElectionDivision')->name('getPollingBoothByElectionDivision');
            Route::post('get_polling_booth_by_election_divisions', 'PollingBoothController@getByElectionDivisions')->name('getPollingBoothByElectionDivisions');
            Route::post('save_polling_booth', 'PollingBoothController@store')->name('savePollingBooth');
            Route::post('update_polling_booth', 'PollingBoothController@update')->name('updatePollingBooth');
            Route::post('confirm_polling_booths', 'PollingBoothController@confirm')->name('confirmPollingBooths');
            Route::post('delete_polling_booth', 'PollingBoothController@deleteRecord')->name('deletePollingBooth');

            //Gramasewa Division
            Route::post('get_pgramasewa_division_by_auth', 'GramasewaDivisionController@getByAuth')->name('getGramasewaDivisionByAuth');
            Route::post('get_pgramasewa_division_by_polling_booth', 'GramasewaDivisionController@getByPollingBooth')->name('getGramasewaDivisionByPollingBooth');
            Route::post('get_gramasewa_division_by_polling_booths', 'GramasewaDivisionController@getByPollingBooths')->name('getGramasewaDivisionByPollingBooths');
            Route::post('save_gramasewa_division', 'GramasewaDivisionController@store')->name('saveGramasewaDivision');
            Route::post('update_gramasewa_division', 'GramasewaDivisionController@update')->name('updateGramasewaDivision');
            Route::post('confirm_gramasewa_divisions', 'GramasewaDivisionController@confirm')->name('confirmGramasewaDivisions');
            Route::post('delete_gramasewa_division', 'GramasewaDivisionController@deleteRecord')->name('deleteGramasewaDivision');
            Route::post('get_gramasewa_by_secretariat', 'GramasewaDivisionController@getBySecretariat')->name('getGramasewaBySecretariat');
            Route::post('get_gramasewa_by_council', 'GramasewaDivisionController@getByCouncil')->name('getGramasewaByCouncil');
            Route::post('get_unassigned_gramasewa_divisions', 'GramasewaDivisionController@getUnAssigned')->name('getUnAssignedGramasewaDivisions');
            Route::post('get_noncouncilled_gramasewa_divisions', 'GramasewaDivisionController@getUnCouncilled')->name('getNonCouncilledGramasewaDivisions');

            //Village
            Route::post('get_village_by_auth', 'VillageController@getByAuth')->name('getVillageByAuth');
            Route::post('get_village_by_gramasewa_division', 'VillageController@getByGramasewaDivision')->name('getVillageByGramasewaDivision');
            Route::post('get_village_by_gramasewa_divisions', 'VillageController@getByGramasewaDivisions')->name('getVillageByGramasewaDivisions');
            Route::post('save_village', 'VillageController@store')->name('saveVillage');
            Route::post('update_village', 'VillageController@update')->name('updateVillage');
            Route::post('confirm_villages', 'VillageController@confirm')->name('confirmVillages');
            Route::post('delete_village', 'VillageController@deleteRecord')->name('deleteVillage');

            //Post
            Route::post('publish_posts', 'PostController@publish')->name('publishPost');
            Route::post('reject_posts', 'PostController@reject')->name('rejectPost');
            Route::post('save_post', 'PostController@store')->name('savePost');
            Route::post('view_posts_admin', 'PostController@showAdmin')->name('viewPostAdmin');

            //Post Response
            Route::post('view_comments', 'PostResponseController@viewComments')->name('viewComments');
            Route::post('view_user_comments', 'PostResponseController@viewUserComments')->name('viewUserComments');
            Route::post('save_comment', 'PostResponseController@store')->name('saveComment');
            Route::post('save_comment_attachments', 'PostResponseController@storeAttachments')->name('saveCommentAttachments');
            Route::post('get_comment_by_user_and_post', 'PostResponseController@getCommentByUserAndPost')->name('getCommentByUserAndPost');
            Route::post('publish_management_comment', 'PostResponseController@publishManagementComment')->name('publishManagementComment');
            Route::post('reject_management_comment', 'PostResponseController@rejectManagementComment')->name('rejectManagementComment');
            Route::post('pending_responses', 'PostResponseController@pendingResponses')->name('pendingResponses');
            Route::post('view_pending_response', 'PostResponseController@viewPendingResponse')->name('viewPendingResponse');
            Route::post('publish_comment', 'PostResponseController@publishComment')->name('publishComment');
            Route::post('reject_comment', 'PostResponseController@rejectComment')->name('rejectComment');

            //Analysis management
            Route::post('analyse_response', 'ResponseAnalysisController@analyse')->name('analyseResponse');
            Route::post('save_response_analysis', 'ResponseAnalysisController@store')->name('saveResponseAnalysis');

            //Divisional secretariat management
            Route::post('save_divisional_secretariat', 'DivisionalSecretariatController@store')->name('saveDivisionalSecretariat');
            Route::post('get_divisional_secretariat_by_auth', 'DivisionalSecretariatController@getByAuth')->name('getDivisionalSecretariatByAuth');
            Route::post('update_divisional_secretariat', 'DivisionalSecretariatController@update')->name('updateDivisionalSecretariat');
            Route::post('delete_divisional_secretariat', 'DivisionalSecretariatController@deleteRecord')->name('deleteDivisionalSecretariat');
            Route::post('assign_gramasewa_divisions', 'DivisionalSecretariatController@assignDivisions')->name('assignedGramasewaDivisions');
            Route::post('confirm_divisional_secretariat', 'DivisionalSecretariatController@confirm')->name('confirmDivisionalSecretariat');

            //Council management
            Route::post('save_council', 'CouncilController@store')->name('saveCouncil');
            Route::post('get_council_by_auth', 'CouncilController@getByAuth')->name('getCouncilByAuth');
            Route::post('update_council', 'CouncilController@update')->name('updateCouncil');
            Route::post('delete_council', 'CouncilController@deleteRecord')->name('deleteCouncil');
            Route::post('assigned_gramasewa_divisions_council', 'CouncilController@assignDivisions')->name('assignedGramasewaCouncil');
            Route::post('confirm_council', 'CouncilController@confirm')->name('confirmCouncil');

            //google Maps
            Route::get('agent_distribution', 'GoogleMapController@agentDistribution')->name('agentDistribution');
            Route::post('get_village_locations', 'GoogleMapController@getVillageLocations')->name('getVillageLocations');
//            Route::get('canvassing_path', 'GoogleMapController@canvassingPath')->name('canvassingPath');
            Route::post('get_canvassing_map_data', 'GoogleMapController@getScreenMapData')->name('getScreenMapData');
            Route::get('routeTest', 'GoogleMapController@routeTest')->name('routeTest');
            Route::get('live_map', 'GoogleMapController@liveMap')->name('liveMap');
            Route::get('heat_map', 'GoogleMapController@heatMap')->name('heatMap');
            //google Maps end

        });
    });

});



