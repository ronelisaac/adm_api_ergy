<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', 'PassportController@login');
//Route::post('/register', 'PassportController@register');
Route::get('/full-users/recovery-password/{email}', 'FullUserController@recoveryPassword');
Route::post('/full-users/register', 'FullUserController@register');



Route::middleware(['auth:api'])->group(function () {
  Route::get('/records/shared', 'RecordController@shared');
  Route::post('/records/share', 'RecordController@share');
  Route::get('/records/list-approvals', 'RecordController@listApprovals');
  Route::post('/records/update-approvals', 'RecordController@updateApprovals');
  Route::get('/records/list-user-records', 'RecordController@listUserRecords');
  Route::resource('/records', 'RecordController');
  Route::get('/full-users/share-pdf-mail/{id}/{mails}', 'FullUserController@sharePdfMail');
  Route::get('/full-users/send-verification-mail/{email}', 'FullUserController@sendVerificationEmail');
  Route::resource('/full-users', 'FullUserController');
  
  //Route::get('/users/get-records', 'UserController@getRecords');
  // Route::resource('/indicators', 'IndicatorController');
  // Route::resource('/time-series', 'TimeSerieController');
  Route::resource('/broadcasts', 'BroadcastController');
  Route::resource('/markers', 'MarkerController');
  Route::post('/markers/refresh', 'CheckbookController@refresh');
  /*imports*/
  /*imports - end*/

  /*broadcasts*/
  /*broadcasts - end*/
  
  /*own*/
  Route::get('/projects/list/', 'CustomProjectController@list');
  Route::get('/services/list/', 'CustomServiceController@list');
  Route::get('/projects/get-amounts/{id}', 'CustomProjectController@getAmounts');
  Route::get('/projects/all/', 'CustomProjectController@all');
  Route::get('/services/get-pending/', 'CustomServiceController@getPending');
  Route::get('/main-report/get-receivable/{currency}', 'MainReportController@getReceivable');
  Route::get('/main-report/get-expired-services/', 'MainReportController@getExpiredServices');
  Route::get('/projects/download-purchase-order-pdf/{id}', 'CustomProjectController@downloadPurchaseOrderPdf');
  Route::get('/main-report/get-payable/{currency}', 'MainReportController@getPayable');
  Route::get('/main-report/get-outgoing-check', 'MainReportController@getOutgoingCheck');
  Route::get('/main-report/get-invoices-service', 'MainReportController@getInvoicesService');
  Route::get('/purchases/list/', 'CustomPurchaseController@list');
  Route::get('/purchases/download-excel/', 'CustomPurchaseController@downloadExcel');
  /*own - end*/
  
  /*main*/
  Route::post('/projects/all', 'ProjectController@all');
  Route::get('/projects/search', 'ProjectController@search');
  Route::post('/projects/do', 'ProjectController@do');
  Route::post('/projects/cancel', 'ProjectController@cancel');
  Route::post('/projects/block', 'ProjectController@block');
  Route::get('/projects/pdf/{id}', 'ProjectController@downloadPdf');
  Route::get('/projects/excel', 'ProjectController@downloadExcel');
  Route::resource('/projects', 'ProjectController');
  Route::post('/services/all', 'ServiceController@all');
  Route::get('/services/search', 'ServiceController@search');
  Route::post('/services/do', 'ServiceController@do');
  Route::post('/services/cancel', 'ServiceController@cancel');
  Route::post('/services/block', 'ServiceController@block');
  Route::get('/services/pdf/{id}', 'ServiceController@downloadPdf');
  Route::get('/services/excel', 'ServiceController@downloadExcel');
  Route::resource('/services', 'ServiceController');
  Route::post('/banks/all', 'BankController@all');
  Route::get('/banks/search', 'BankController@search');
  Route::post('/banks/do', 'BankController@do');
  Route::post('/banks/cancel', 'BankController@cancel');
  Route::post('/banks/block', 'BankController@block');
  Route::get('/banks/pdf/{id}', 'BankController@downloadPdf');
  Route::get('/banks/excel', 'BankController@downloadExcel');
  Route::resource('/banks', 'BankController');
  Route::post('/incoming-checks/all', 'IncomingCheckController@all');
  Route::get('/incoming-checks/search', 'IncomingCheckController@search');
  Route::post('/incoming-checks/do', 'IncomingCheckController@do');
  Route::post('/incoming-checks/cancel', 'IncomingCheckController@cancel');
  Route::post('/incoming-checks/block', 'IncomingCheckController@block');
  Route::get('/incoming-checks/pdf/{id}', 'IncomingCheckController@downloadPdf');
  Route::get('/incoming-checks/excel', 'IncomingCheckController@downloadExcel');
  Route::resource('/incoming-checks', 'IncomingCheckController');
  Route::post('/outgoing-checks/all', 'OutgoingCheckController@all');
  Route::get('/outgoing-checks/search', 'OutgoingCheckController@search');
  Route::post('/outgoing-checks/do', 'OutgoingCheckController@do');
  Route::post('/outgoing-checks/cancel', 'OutgoingCheckController@cancel');
  Route::post('/outgoing-checks/block', 'OutgoingCheckController@block');
  Route::get('/outgoing-checks/pdf/{id}', 'OutgoingCheckController@downloadPdf');
  Route::get('/outgoing-checks/excel', 'OutgoingCheckController@downloadExcel');
  Route::resource('/outgoing-checks', 'OutgoingCheckController');
  Route::post('/products/all', 'ProductController@all');
  Route::get('/products/search', 'ProductController@search');
  Route::post('/products/do', 'ProductController@do');
  Route::post('/products/cancel', 'ProductController@cancel');
  Route::post('/products/block', 'ProductController@block');
  Route::get('/products/pdf/{id}', 'ProductController@downloadPdf');
  Route::get('/products/excel', 'ProductController@downloadExcel');
  Route::resource('/products', 'ProductController');
  Route::post('/sales/all', 'SaleController@all');
  Route::get('/sales/search', 'SaleController@search');
  Route::post('/sales/do', 'SaleController@do');
  Route::post('/sales/cancel', 'SaleController@cancel');
  Route::post('/sales/block', 'SaleController@block');
  Route::get('/sales/pdf/{id}', 'SaleController@downloadPdf');
  Route::get('/sales/excel', 'SaleController@downloadExcel');
  Route::resource('/sales', 'SaleController');
  Route::post('/sale-fees/all', 'SaleFeeController@all');
  Route::get('/sale-fees/search', 'SaleFeeController@search');
  Route::post('/sale-fees/do', 'SaleFeeController@do');
  Route::post('/sale-fees/cancel', 'SaleFeeController@cancel');
  Route::post('/sale-fees/block', 'SaleFeeController@block');
  Route::get('/sale-fees/pdf/{id}', 'SaleFeeController@downloadPdf');
  Route::get('/sale-fees/excel', 'SaleFeeController@downloadExcel');
  Route::resource('/sale-fees', 'SaleFeeController');
  Route::post('/collections/all', 'CollectionController@all');
  Route::get('/collections/search', 'CollectionController@search');
  Route::post('/collections/do', 'CollectionController@do');
  Route::post('/collections/cancel', 'CollectionController@cancel');
  Route::post('/collections/block', 'CollectionController@block');
  Route::get('/collections/pdf/{id}', 'CollectionController@downloadPdf');
  Route::get('/collections/excel', 'CollectionController@downloadExcel');
  Route::resource('/collections', 'CollectionController');
  Route::post('/expenses-accounts/all', 'ExpensesAccountController@all');
  Route::get('/expenses-accounts/search', 'ExpensesAccountController@search');
  Route::post('/expenses-accounts/do', 'ExpensesAccountController@do');
  Route::post('/expenses-accounts/cancel', 'ExpensesAccountController@cancel');
  Route::post('/expenses-accounts/block', 'ExpensesAccountController@block');
  Route::get('/expenses-accounts/pdf/{id}', 'ExpensesAccountController@downloadPdf');
  Route::get('/expenses-accounts/excel', 'ExpensesAccountController@downloadExcel');
  Route::resource('/expenses-accounts', 'ExpensesAccountController');

  Route::post('/accounts/all', 'AccountController@all');
  Route::get('/accounts/list', 'AccountController@list');
  Route::get('/accounts/search', 'AccountController@search');
  Route::post('/accounts/do', 'AccountController@do');
  Route::post('/accounts/cancel', 'AccountController@cancel');
  Route::post('/accounts/block', 'AccountController@block');
  Route::get('/accounts/pdf/{id}', 'AccountController@downloadPdf');
  Route::get('/accounts/excel', 'AccountController@downloadExcel');
  Route::resource('/accounts', 'AccountController');

  Route::post('/purchases/all', 'PurchaseController@all');
  Route::get('/purchases/search', 'PurchaseController@search');
  Route::post('/purchases/do', 'PurchaseController@do');
  Route::post('/purchases/cancel', 'PurchaseController@cancel');
  Route::post('/purchases/block', 'PurchaseController@block');
  Route::get('/purchases/pdf/{id}', 'PurchaseController@downloadPdf');
  Route::get('/purchases/excel', 'PurchaseController@downloadExcel');
  Route::resource('/purchases', 'PurchaseController');
  Route::post('/purchase-fees/all', 'PurchaseFeeController@all');
  Route::get('/purchase-fees/search', 'PurchaseFeeController@search');
  Route::post('/purchase-fees/do', 'PurchaseFeeController@do');
  Route::post('/purchase-fees/cancel', 'PurchaseFeeController@cancel');
  Route::post('/purchase-fees/block', 'PurchaseFeeController@block');
  Route::get('/purchase-fees/pdf/{id}', 'PurchaseFeeController@downloadPdf');
  Route::get('/purchase-fees/excel', 'PurchaseFeeController@downloadExcel');
  Route::resource('/purchase-fees', 'PurchaseFeeController');
  Route::post('/sale-invoices/all', 'SaleInvoiceController@all');
  Route::get('/sale-invoices/search', 'SaleInvoiceController@search');
  Route::post('/sale-invoices/do', 'SaleInvoiceController@do');
  Route::post('/sale-invoices/cancel', 'SaleInvoiceController@cancel');
  Route::post('/sale-invoices/block', 'SaleInvoiceController@block');
  Route::get('/sale-invoices/pdf/{id}', 'SaleInvoiceController@downloadPdf');
  Route::get('/sale-invoices/excel', 'SaleInvoiceController@downloadExcel');
  Route::resource('/sale-invoices', 'SaleInvoiceController');
  Route::post('/addresses/all', 'AddressController@all');
  Route::get('/addresses/search', 'AddressController@search');
  Route::post('/addresses/do', 'AddressController@do');
  Route::post('/addresses/cancel', 'AddressController@cancel');
  Route::post('/addresses/block', 'AddressController@block');
  Route::get('/addresses/pdf/{id}', 'AddressController@downloadPdf');
  Route::get('/addresses/excel', 'AddressController@downloadExcel');
  Route::resource('/addresses', 'AddressController');
  Route::post('/users/all', 'UserController@all');
  Route::get('/users/search', 'UserController@search');
  Route::post('/users/do', 'UserController@do');
  Route::post('/users/cancel', 'UserController@cancel');
  Route::post('/users/block', 'UserController@block');
  Route::get('/users/pdf/{id}', 'UserController@downloadPdf');
  Route::get('/users/excel', 'UserController@downloadExcel');
  Route::resource('/users', 'UserController');
  Route::post('/roles/all', 'RoleController@all');
  Route::get('/roles/search', 'RoleController@search');
  Route::post('/roles/do', 'RoleController@do');
  Route::post('/roles/cancel', 'RoleController@cancel');
  Route::post('/roles/block', 'RoleController@block');
  Route::get('/roles/pdf/{id}', 'RoleController@downloadPdf');
  Route::get('/roles/excel', 'RoleController@downloadExcel');
  Route::resource('/roles', 'RoleController');
  Route::post('/permissions/all', 'PermissionController@all');
  Route::get('/permissions/search', 'PermissionController@search');
  Route::post('/permissions/do', 'PermissionController@do');
  Route::post('/permissions/cancel', 'PermissionController@cancel');
  Route::post('/permissions/block', 'PermissionController@block');
  Route::get('/permissions/pdf/{id}', 'PermissionController@downloadPdf');
  Route::get('/permissions/excel', 'PermissionController@downloadExcel');
  Route::resource('/permissions', 'PermissionController');
  Route::post('/permissions-groups/all', 'PermissionsGroupController@all');
  Route::get('/permissions-groups/search', 'PermissionsGroupController@search');
  Route::post('/permissions-groups/do', 'PermissionsGroupController@do');
  Route::post('/permissions-groups/cancel', 'PermissionsGroupController@cancel');
  Route::post('/permissions-groups/block', 'PermissionsGroupController@block');
  Route::get('/permissions-groups/pdf/{id}', 'PermissionsGroupController@downloadPdf');
  Route::get('/permissions-groups/excel', 'PermissionsGroupController@downloadExcel');
  Route::resource('/permissions-groups', 'PermissionsGroupController');
  /*main - end*/
  
  
});



