<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});
Route::get('/', 'RootController@welcome');
Auth::routes();
Route::get('/home', 'HomeController@index');

// PARAMETER ROUTES
Route::get('parameters', 'CzParamController@parameters');
Route::post('/parameters/save/{id_cz_parameter}','CzParamController@save');


// KLANTENROUTES
Route::get('klanten', 'PsCustomerController@customers');
Route::match(['get', 'post'], '/customer_data', 'PsCustomerController@customerData');
Route::match(['get', 'post'], '/klant_address_data/{id_customer}', 'PsCustomerController@addressData');
Route::get('klanten/create','PsCustomerController@create');
Route::get('klanten/edit/{id_customer}','PsCustomerController@edit');
Route::post('klanten/save/{id_customer}','PsCustomerController@save');
Route::post('klanten/edit/address/create','PsAddressController@addressCreate');
Route::get('klanten/edit/address/edit/{id_address}','PsAddressController@addressEdit');
Route::post('klanten/edit/address/update/{id_address}','PsAddressController@addressUpdate');
Route::post('klanten/edit/address/delete/{id_address}','PsAddressController@addressDelete');

// LEVERANCIER Routes
Route::get('leveranciers', 'PsSupplierController@suppliers');
Route::match(['get', 'post'], '/supplier_data', 'PsSupplierController@supplierData');
Route::match(['get', 'post'], '/supplier_address_data/{id_supplier}', 'PsSupplierController@addressData');
Route::get('leveranciers/create','PsSupplierController@create');
Route::get('leveranciers/edit/{id_supplier}','PsSupplierController@edit');

Route::post('leveranciers/save/{id_supplier}','PsSupplierController@save');
Route::post('leveranciers/edit/address/create','PsAddressController@addressCreate');
Route::get('leveranciers/edit/address/edit/{id_address}','PsAddressController@addressEdit');
Route::post('leveranciers/edit/address/update/{id_address}','PsAddressController@addressUpdate');
Route::post('leveranciers/edit/address/delete/{id_address}','PsAddressController@addressDelete');

// PRODUCT Routes

Route::get('producten', 'CzProductController@products');
Route::match(['get', 'post'], '/product_data', 'CzProductController@productData');
Route::get('producten/create','CzProductController@create');
Route::get('producten/edit/{id_cz_product}','CzProductController@edit');
Route::post('producten/save/{id_cz_product}','CzProductController@save');

// BOL-BE routes
Route::get('bol-be/verkopen/nieuwe-orders', 'BolCustOrderController@getBolOrders');
Route::post('bol-be/verkopen/nieuwe-orders/wijzig-status/{id_order}/{newState}','BolCustOrderController@changeState');


// BOL-NL routes
Route::get('bol-nl/verkopen/nieuwe-orders', 'BolNlCustOrderController@getBolOrders');
Route::post('bol-nl/verkopen/nieuwe-orders/wijzig-status/{id_order}/{newState}','BolNlCustOrderController@changeState');


// Bol-TEST routes
Route::get('bol/test', 'BolTestController@test');
Route::get('bol/test/get-offers', 'BolTestController@getOffers');
Route::get('bol/test/del-offers', 'BolTestController@delOffers');



// CZ VERKOPEN routes
Route::get('cz/verkopen/nieuwe-orders','PsCustOrderController@newOrders');
Route::post('cz/verkopen/nieuwe-orders/wijzig-status/{id_order}/{newState}','PsCustOrderController@changeState');
Route::get('cz/verkopen/nieuwe-orders/image/{id_product}');

// CZ invoices routes
Route::get('/verkopen/facturen','CzCustInvoiceController@invoices');
Route::get('/verkopen/facturen/edit/{id_cust_invoice}','CzCustInvoiceController@edit');
Route::match(['get', 'post'], '/verkopen/invoice_data', 'CzCustInvoiceController@invoiceData');
Route::get('/verkopen/facturen/print/{id_cust_invoice}','printCzCustInvoiceController@getInvoicePdf');




// MAIL routes
Route::get('/email','MailController@test');


