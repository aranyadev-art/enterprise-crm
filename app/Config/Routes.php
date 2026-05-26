<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('leads/convert/(:num)', 'Leads::convert/$1');
$routes->get('register', 'Auth::register');
$routes->post('save-register', 'Auth::saveRegister');
$routes->get('login', 'Auth::login');
$routes->post('checkLogin', 'Auth::checkLogin');
$routes->get('logout', 'Auth::logout');
$routes->get('/user', 'User::index');
$routes->get('dashboard', 'Dashboard::index');
$routes->get('dashboard/getChartData', 'Dashboard::getChartData');
$routes->get('/search', 'Dashboard::globalSearch');
$routes->get('followup/done/(:num)', 'Dashboard::markFollowupDone/$1');

$routes->post('/user/save', 'User::save');
$routes->get('users', 'User::list');
$routes->get('user/edit/(:num)', 'User::edit/$1');
$routes->post('user/update/(:num)', 'User::update/$1');
$routes->get('user/delete/(:num)', 'User::delete/$1');
$routes->post('user/deleteMultiple', 'User::deleteMultiple');
$routes->get('users/create', 'User::create');


$routes->get('clients', 'Client::list');
$routes->get('clients/create', 'Client::create');
$routes->post('clients/save', 'Client::save');
$routes->get('client/edit/(:num)', 'Client::edit/$1');
$routes->post('clients/update/(:num)', 'Client::update/$1');
$routes->get('client/delete/(:num)', 'Client::delete/$1');
$routes->post('client/deleteMultiple', 'Client::deleteMultiple');
$routes->get('client/login', 'ClientAuth::login');
$routes->post('client/checkLogin', 'ClientAuth::checkLogin');
$routes->get('client/dashboard', 'Client::dashboard');
$routes->get('client/profile', 'Client::profile');
$routes->get('client/orders', 'Client::orders');
$routes->get('client/order/(:num)', 'Client::orderDetails/$1');
$routes->get('client/quotations', 'Client::quotations');
$routes->get('client/logout', 'ClientAuth::logout');
$routes->get('client/payment', 'Client::payment');
$routes->post('client/savePayment', 'Client::savePayment');
$routes->post('clients/check-email', 'Clients::checkEmail');

$routes->get('sales', 'Sales::list');
$routes->get('sales/add', 'Sales::add');
$routes->post('sales/add', 'Sales::add');
$routes->post('sales/save', 'Sales::save');
$routes->post('sales/uploadDesign', 'Sales::uploadDesign');
$routes->post('sales/deleteMultiple', 'Sales::deleteMultiple');


$routes->get('change-password', 'User::changePassword');
$routes->post('update-password', 'User::updatePassword');

$routes->get('cod/create','Cod::create');
$routes->post('cod/save','Cod::save');
$routes->get('cod', 'Cod::list');
$routes->get('cod/list','Cod::list');
$routes->get('cod/download/(:num)', 'Cod::download/$1');
$routes->post('cod/deleteMultiple', 'Cod::deleteMultiple');

$routes->get('quotation', 'Quotation::index');
$routes->get('quotation/list', 'Quotation::list');
$routes->get('quotation/edit/(:num)', 'Quotation::edit/$1');
$routes->post('quotation/update/(:num)', 'Quotation::update/$1');
$routes->get('quotation/delete/(:num)', 'Quotation::delete/$1');
$routes->get('quotation/create', 'Quotation::create');
$routes->post('quotation/create', 'Quotation::create');
$routes->post('quotation/deleteMultiple', 'Quotation::deleteMultiple');
$routes->get('quotation/download/(:num)', 'Quotation::downloadPdf/$1');
$routes->post('quotation/storeFromCalculator', 'Quotation::storeFromCalculator');

$routes->get('orders', 'Order::index');
$routes->get('orders/create', 'Order::create');

$routes->post('orders/save', 'Order::save'); // ✅ correct
$routes->post('orders/deleteMultiple', 'Order::deleteMultiple'); // ✅ correct

$routes->get('orders/sendToFactory/(:num)', 'Order::sendToFactory/$1'); // ✅ FIXED

$routes->get('accounts/create', 'Accounts::create');
$routes->get('accounts', 'Accounts::index');
$routes->get('account', 'Accounts::index');
$routes->post('accounts/save', 'Accounts::save');
$routes->get('accounts/list', 'Accounts::list');
$routes->get('accounts', 'Accounts::list'); // 🔥 IMPORTANT
$routes->get('accounts/dispatch/(:num)', 'Accounts::dispatch/$1');
$routes->post('accounts/deleteMultiple', 'Accounts::deleteMultiple');
$routes->get('accounts/payment/(:num)', 'Accounts::addPayment/$1');
$routes->post('accounts/savePayment', 'Accounts::savePayment');
$routes->get('accounts/payments/(:num)', 'Accounts::paymentHistory/$1');
$routes->get('accounts/payments/pdf/(:num)', 'Accounts::downloadPaymentsPDF/$1');

$routes->get('factory', 'Factory::index');
$routes->get('factory/create', 'Factory::create');
$routes->post('factory/save', 'Factory::save');
$routes->get('factory/create/(:num)', 'Factory::create/$1');
$routes->post('factory/deleteMultiple', 'Factory::deleteMultiple');

$routes->get('calculator', 'Calculator::index');
$routes->post('calculator/upload', 'Calculator::upload');


$routes->get('/shipping', 'Shipping::index');
$routes->get('shipping', 'Shipping::index');
$routes->get('/shipping/create', 'Shipping::create');
$routes->post('/shipping/store', 'Shipping::store');
$routes->get('/shipping/delete/(:num)', 'Shipping::delete/$1');
$routes->post('shipping/deleteMultiple', 'Shipping::deleteMultiple');

$routes->get('alerts', 'Alerts::index');

// 👉 Alert create (test ke liye)
$routes->get('alerts/create/(:any)', 'Alerts::createAlert/$1');

// 👉 Mark as Read
$routes->get('alerts/read/(:any)', 'Alerts::markAsRead/$1');
$routes->get('profile', 'User::profile');
$routes->get('profile', 'Profile::index');
$routes->post('profile/update', 'Profile::update');
$routes->get('clear-alerts', function () {
    session()->remove('alerts');
    session()->destroy();
    return "Session Cleared";
});

$routes->get('settings', 'Settings::index');
$routes->post('settings/updatePassword', 'Settings::updatePassword');
$routes->post('settings/uploadProfile', 'Settings::uploadProfile');
$routes->post('settings/updateNotification', 'Settings::updateNotification');
$routes->get('settings/testEmail', 'Settings::testEmail');

$routes->get('roster', 'RosterController::index');         
$routes->get('roster/create', 'RosterController::create'); 
$routes->post('roster/save', 'RosterController::save');   
$routes->get('roster/edit/(:num)', 'RosterController::edit/$1');
$routes->post('roster/update/(:num)', 'RosterController::update/$1');
$routes->get('roster/delete/(:num)', 'RosterController::delete/$1'); 

$routes->get('/leads', 'Leads::index');
$routes->get('/leads/create', 'Leads::create');
$routes->post('/leads/store', 'Leads::store');
$routes->get('/leads/edit/(:num)', 'Leads::edit/$1');
$routes->post('/leads/update/(:num)', 'Leads::update/$1');
$routes->get('/leads/delete/(:num)', 'Leads::delete/$1');
$routes->get('leads/convert/(:num)', 'Leads::convert/$1');
$routes->post('leads/update-status/(:num)', 'Leads::updateStatus/$1');
$routes->post('leads/assign/(:num)', 'Leads::assign/$1');
$routes->post('leads/deleteMultiple', 'Leads::deleteMultiple');




//$routes->get('/reset-password', 'Auth::resetPassword');//