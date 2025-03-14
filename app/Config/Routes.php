<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->setAutoRoute(true);

$routes->get('/', 'Home::index');
$routes->get('table', 'Home::table');
$routes->get('login', 'Home::login');
$routes->get('load_image/(.*)', 'Home::load_image');
$routes->get('promotion_material_file_download/(.*)', 'Home::promotion_material_file_download');
$routes->get('load_image_meta/(.*)', 'Home::load_image_meta');
//$routes->post('login-form-submit', 'Home::system_user_login_submit');
$routes->get('/logout', 'Home::logout');
$routes->post('/fruit_search_filter', 'Home::fruit_search_filter');
$routes->post('/pharmacy_profile_search_filter', 'Pharmacy::search_filter');
$routes->post('/lookup_search_filter', 'Lookup::search_filter');
$routes->post('/clear_session', 'Home::clear_session');
$routes->post('fruit-data', 'HealthCare::fetch_fruit');
$routes->post('vegetable-data', 'HealthCare::fetch_vegetable');
$routes->post('miscellaneous-data', 'HealthCare::fetch_miscellaneous');
$routes->post('/clear_session', 'Home::clear_session');
$routes->post('/use-login-submit', 'Home::use_login_submit');
$routes->get('/file-cdn/(.*)', 'Home::load_file');

$routes->group('support-ticket', static function ($routes) {
    $routes->get('clinta_extended', 'SupportTickets::index');
    $routes->get('dashboard', 'SupportTickets::dashboard');
    $routes->get('items', 'SupportTickets::items');
    $routes->post('fetch-data', 'SupportTickets::fetch_data');
    $routes->post('fetch-builder-task', 'SupportTickets::fetch_builder_data');
    $routes->post('fetch-items', 'SupportTickets::fetch_items');
    $routes->post('get-record-items', 'SupportTickets::get_item_record');
    $routes->post('update-deadline-form-submit', 'SupportTickets::UpdateDeadLineFormSubmit');
    $routes->post('update-builder-deadline-form-submit', 'SupportTickets::UpdateBuilderDeadLineFormSubmit');
    $routes->post('TicketReplyFormSubmit', 'SupportTickets::TicketReplyFormSubmit');
    $routes->post('BuilderTicketReplyFormSubmit', 'SupportTickets::BuilderTicketReplyFormSubmit');
    $routes->post('submit', 'SupportTickets::ticket_form_submit');
    $routes->post('submit-item', 'SupportTickets::item_form_submit');
    $routes->post('search_filter', 'SupportTickets::search_filter');
    $routes->post('builder_support_search_filter', 'SupportTickets::builder_support_search_filter');
    $routes->post('load_tickets_comments', 'SupportTickets::load_tickets_comments');
    $routes->post('load_builder_tickets_comments', 'SupportTickets::load_builder_tickets_comments');
    $routes->post('delete', 'SupportTickets::delete_ticket');
    $routes->post('delete-item', 'SupportTickets::delete_item');
    $routes->get('tickets_reply/(:num)', 'SupportTickets::index');
    $routes->get('builder_tickets_reply/(:num)', 'SupportTickets::builder_support');
    $routes->get('builder_support_ticket', 'SupportTickets::builder_support');
    $routes->get('download/(:any)', 'SupportTickets::downloadFile');

});
$routes->group('builder', static function ($routes) {
    $routes->get('/', 'Builder::index');
    $routes->get('add', 'Builder::index');
    $routes->get('specialities', 'Builder::index');
    $routes->get('add-doctor', 'Builder::index');
    $routes->get('add-hospital', 'Builder::index');
    $routes->get('hospital', 'Builder::index');
    $routes->get('images', 'Builder::index');
    $routes->get('banners', 'Builder::index');
    $routes->post('get-banners', 'Builder::fetch_banners');
    $routes->post('get-images', 'Builder::fetch_images');
    $routes->post('get-doctor', 'Builder::fetch_doctors');
    $routes->post('get-hospital', 'Builder::fetch_hospitals');
    $routes->get('dashboard', 'Builder::dashboard');
    $routes->post('submit-doctor', 'Builder::doctors_profile_form_submit');
    $routes->post('submit-hospital', 'Builder::hospitals_profile_form_submit');
    $routes->post('add_telemedicine_credits', 'Builder::add_telemedicine_credits');
    $routes->post('add_sms_credits', 'Builder::add_sms_credits');
    $routes->post('submit-image', 'Builder::image_form_submit');
    $routes->post('delete', 'Builder::delete');
    $routes->post('delete-doctor', 'Builder::delete_doctor');
    $routes->post('delete-hospital', 'Builder::delete_hospital');
    $routes->post('delete-banner', 'Builder::delete_banner');
    $routes->post('delete-images', 'Builder::delete_images');
    $routes->get('update-doctor/(:num)', 'Builder::index');
    $routes->get('specialities-gallery/(:num)', 'Builder::gallery');
    $routes->get('update-hospital/(:num)', 'Builder::index');
});
$routes->group('users', static function ($routes) {
    $routes->get('/', 'Users::index');
    $routes->get('access-level', 'Users::index');
    $routes->get('admin-activites', 'Users::index');
    $routes->get('admin-approval', 'Users::index');
    $routes->post('users-data', 'Users::fetch_users');
    $routes->post('get-record', 'Users::get_item_record');
    $routes->get('dashboard', 'Users::dashboard');
    $routes->post('submit', 'Users::user_form_submit');
    $routes->post('delete', 'Users::delete_user');
});
$routes->group('document', static function ($routes) {
    $routes->get('diet-plan', 'Document::index');
    $routes->get('workout-plan', 'Document::index');
    $routes->get('tips-guides', 'Document::index');
    $routes->get('faq', 'Document::index');
    $routes->get('exercise', 'Document::index');
    $routes->post('document-data', 'Document::fetch_document_data');
    $routes->post('get-record', 'Document::get_record');
    $routes->get('dashboard', 'Document::dashboard');
    $routes->post('submit', 'Document::form_submit');
    $routes->post('delete', 'Document::delete');
});$routes->group('frenchises', static function ($routes) {
    $routes->get('', 'HealthCare::frenchises');
    $routes->get('add', 'HealthCare::frenchises');
    $routes->get('update/(:num)', 'HealthCare::frenchises');
    $routes->post('submit', 'HealthCare::form_submit_frenchises');
    $routes->post('delete', 'HealthCare::delete_frenchises');
});
$routes->group('diet', static function ($routes) {
    $routes->get('/', 'HealthCare::index');
    $routes->get('diet-categories', 'HealthCare::diet_categories');
    $routes->get('dashboard', 'HealthCare::dashboard');
    $routes->get('fruit', 'HealthCare::index');
    $routes->get('pulses-grains', 'HealthCare::index');
    $routes->get('dry-fruits', 'HealthCare::index');
    $routes->get('miscellaneous', 'HealthCare::index');
    $routes->get('dry-fruits', 'HealthCare::index');
    $routes->get('support-videos', 'HealthCare::support_videos');
    $routes->post('dry-fruits-data', 'HealthCare::fetch_dry_fruit');
    $routes->post('diet-categories-data', 'HealthCare::fetch_diet_categories');
    $routes->post('support-videos-data', 'HealthCare::fetch_support_videos');
    $routes->post('pulses-grains-data', 'HealthCare::fetch_grains');
    $routes->post('get-record-support-video', 'HealthCare::get_record_support_video');
    $routes->post('get-record', 'HealthCare::get_item_record');
    $routes->post('get-record-category', 'HealthCare::get_category_record');
    $routes->get('vegetable', 'HealthCare::index');
    $routes->post('submit', 'HealthCare::item_form_submit');
    $routes->post('submit-category', 'HealthCare::category_form_submit');
    $routes->post('submit-support-video', 'HealthCare::support_videos_form_submit');
    $routes->post('detail-submit', 'HealthCare::diet_submit');
    $routes->post('support-video-delete', 'HealthCare::delete_support_video');
    $routes->post('delete', 'HealthCare::delete_item');
    $routes->post('delete-category', 'HealthCare::delete_category');
    $routes->get('fruit-detail/(:num)', 'HealthCare::diet');
    $routes->get('dry-fruits-detail/(:num)', 'HealthCare::diet');
    $routes->get('miscellaneous-detail/(:num)', 'HealthCare::diet');
    $routes->get('pulses-grains-detail/(:num)', 'HealthCare::diet');
    $routes->get('vegetable-detail/(:num)', 'HealthCare::diet');

});
$routes->group('pharmacy', static function ($routes) {
    $routes->get('dashboard', 'Pharmacy::dashboard');
    $routes->get('pharmacy_profile', 'Pharmacy::index');
    $routes->post('pharmacy-data', 'Pharmacy::fetch_pharmacy');
    $routes->post('submit', 'Pharmacy::form_submit');
    $routes->post('delete', 'Pharmacy::delete');
    $routes->post('get-record', 'Pharmacy::get_record');
    $routes->post('treatment_search_filter', 'Pharmacy::search_filter');


});
$routes->group('lookups', static function ($routes) {
    $routes->get('/', 'Lookup::index');
    $routes->get('lookup-options/(:num)', 'Lookup::lookup_option');
    $routes->post('lookup-data', 'Lookup::fetch_lookups');
    $routes->post('lookup-option-data', 'Lookup::fetch_lookups_options');
    $routes->post('get-record', 'Lookup::get_record');
    $routes->post('submit', 'Lookup::lookup_form_submit');
    $routes->post('delete', 'Lookup::delete_lookup');
    $routes->post('get-lookup-option-record', 'Lookup::get_lookup_option_record');
    $routes->post('submit-lookup-option', 'Lookup::lookup_option_form_submit');
    $routes->post('delete-option', 'Lookup::delete_lookup_option');
});

$routes->group('franchises', static function ($routes) {
    $routes->get('/', 'HealthCare::index');


});
$routes->group('representative', static function ($routes) {
    $routes->get('/', 'Representatives::index');
    $routes->get('add', 'Representatives::index');
    $routes->get('update/(:num)', 'Representatives::index');
    $routes->post('representatives-data', 'Representatives::fetch_representative');
    $routes->post('submit', 'Representatives::representatives_form_submit');
    $routes->post('rec-submit', 'Representatives::RCCReceiptForm');
    $routes->post('get', 'Representatives::rcc_receipt_html_list');
    $routes->post('delete', 'Representatives::delete');

});
$routes->group('medicine', static function ($routes) {
    $routes->get('/', 'Medicine::index');
    $routes->post('search_filter', 'Medicine::search_filter');

});
$routes->group('therapy', static function ($routes) {
    $routes->get('/', 'Pharmacy::index');
    $routes->get('add', 'Pharmacy::index');
    $routes->post('submit', 'Pharmacy::user_form_submit');
    $routes->post('delete', 'Pharmacy::delete');
    $routes->get('update/(:num)', 'Pharmacy::index');
});
$routes->group('customers', static function ($routes) {
    $routes->get('/', 'Customers::index');
    $routes->get('add-customer', 'Customers::index');
    $routes->post('submit', 'Customers::customer_form_submit');
    $routes->post('delete', 'Customers::delete');
    $routes->get('update-customer/(:num)', 'Customers::index');
    $routes->get('customer-profile/(:num)', 'Customers::index');

});


$routes->group('diseases', static function ($routes) {
    $routes->get('/', 'Disease::index');
    $routes->post('submit', 'Disease::Disease_form_submit');
    $routes->post('delete', 'Disease::delete');
    $routes->post('get-record', 'Disease::get_record');
    $routes->post('diseases-data', 'Disease::fetch_diseases');


});
$routes->group('laboratories', static function ($routes) {
    $routes->get('/', 'Laboratories::index');
    $routes->post('submit', 'Laboratories::laboratories_form_submit');
    $routes->post('delete', 'Laboratories::delete');
    $routes->post('get-record', 'Laboratories::get_record');
    $routes->post('laboratories-data', 'Laboratories::fetch_laboratories');


});
$routes->group('investigation', static function ($routes) {
    $routes->get('/', 'Customers::index');
    $routes->get('add', 'Customers::index');
    $routes->post('submit', 'Customers::customer_form_submit');
    $routes->post('delete', 'Customers::delete');
    $routes->get('update/(:num)', 'Customers::index');

});
$routes->group('specialities', static function ($routes) {
    $routes->get('/', 'Customers::index');
    $routes->get('add', 'Customers::index');
    $routes->post('submit', 'Customers::customer_form_submit');
    $routes->post('delete', 'Customers::delete');
    $routes->get('update/(:num)', 'Customers::index');

});
$routes->group('extended', static function ($routes) {
    $routes->get('/', 'Extended::index');
    $routes->get('add-profile', 'Extended::index');
    $routes->get('extended_default_lookup', 'Extended::index');
    $routes->get('extended_default_config', 'Extended::index');
    $routes->post('submit', 'Extended::extended_profile_form_submit');
    $routes->post('get-profile', 'Extended::fetch_profiles');
//    $routes->post('delete', 'Extended::delete');
    $routes->get('update-profile/(:num)', 'Extended::index');
    $routes->get('extended_profile_detail/(:num)', 'Extended::index');

});


$routes->group('clinta_members', static function ($routes) {
    $routes->get('/', 'ClintaMember::index');
    $routes->get('add', 'ClintaMember::index');
    $routes->post('submit', 'ClintaMember::shift_form_submit');
    $routes->post('check_login_credentials', 'ClintaMember::check_login_credentials');
    $routes->post('get_user_data_by_id', 'ClintaMember::get_user_data_by_id');
    $routes->post('delete', 'ClintaMember::delete');
    $routes->post('members-data', 'ClintaMember::fetch_members');

    $routes->get('update/(:num)', 'ClintaMember::index');
});

$routes->group('laboratories', static function ($routes) {
    $routes->get('/', 'Customers::index');
    $routes->get('add', 'Customers::index');
    $routes->post('submit', 'Customers::customer_form_submit');
    $routes->post('delete', 'Customers::delete');
    $routes->get('update/(:num)', 'Customers::index');
});
$routes->group('task', static function ($routes) {
    $routes->get('/', 'Tasks::index');
    $routes->get('add', 'Tasks::index');
    $routes->get('assigned_task', 'Tasks::index');
    $routes->get('dashboard', 'Tasks::dashboard');
    $routes->post('submit', 'Tasks::user_form_submit');
    $routes->post('delete', 'Tasks::delete');
    $routes->get('update/(:num)', 'Tasks::index');
});