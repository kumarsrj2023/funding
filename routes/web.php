<?php

use Illuminate\Support\Facades\Route;

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

Route::any('/login', ['as' => 'login', 'uses' => 'AuthController@login']);
Route::get('/logout', ['as' => 'logout', 'uses' => 'AuthController@logout']);

Route::group(['middleware' => ['user']], function() {

    Route::get('/', ['as' => 'home', 'uses' => 'BusinessController@index']);
    Route::post('/businesses/remove', ['as' => 'businesses.remove', 'uses' => 'BusinessController@remove']);
    Route::any('/businesses/customer-info/{id?}', ['as' => 'businesses.customer.info', 'uses' => 'BusinessController@customerInfo']);
    Route::any('/businesses/business-info/{id}', ['as' => 'businesses.business.info', 'uses' => 'BusinessController@businessInfo']);
    
    Route::any('/businesses/director-info/add/{business_id}/{id?}', ['as' => 'businesses.director.info.add', 'uses' => 'BusinessController@directorInfoAdd']);
    Route::post('/businesses/director-info/remove', ['as' => 'businesses.director.info.remove', 'uses' => 'BusinessController@directorInfoRemove']);
    Route::get('/businesses/director-info/{id}', ['as' => 'businesses.director.info', 'uses' => 'BusinessController@directorInfo']);
    Route::post('/businesses/director-info/export/{id}', ['as' => 'businesses.director.info.export', 'uses' => 'BusinessController@directorExport']);
    Route::get('/businesses/director-info/export/file/{type?}/{id}', ['as' => 'businesses.director.info.export.file', 'uses' => 'BusinessController@directorExportFile']);
    // by suraj
    Route::get('/businesses/director-info-ajax/{id}', ['as' => 'businesses.director.info.ajax', 'uses' => 'BusinessController@directorInfoDataforSOP']);
    Route::post('/businesses/send-sop', ['as' => 'send.sop', 'uses' => 'BusinessController@sendSOP']);
    Route::any('statement-of-position/{id}', ['as' => 'sop.form', 'uses' => 'BusinessController@sopForm'])->withoutMiddleware('user');
    Route::any('price-model', ['as' => 'sop.price.model', 'uses' => 'BusinessController@sopPriceModel'])->withoutMiddleware('user');
    Route::any('migrate-db-files', ['as' => 'migratefiles', 'uses' => 'BusinessController@migratefile']);

    Route::any('/businesses/loan-info/add/{business_id}/{id?}', ['as' => 'businesses.loan.info.add', 'uses' => 'BusinessController@loanInfoAdd']);
    Route::post('/businesses/loan-info/remove', ['as' => 'businesses.loan.info.remove', 'uses' => 'BusinessController@loanInfoRemove']);
    Route::any('/businesses/loan-info/{id}', ['as' => 'businesses.loan.info', 'uses' => 'BusinessController@loanInfo']);

    Route::any('/businesses/open-banking-accounts/{id}', ['as' => 'businesses.open.banking.accounts', 'uses' => 'BusinessController@openBankingAccounts']);

    Route::any('/businesses/committee-meeting/{id}', ['as' => 'businesses.committee.meeting', 'uses' => 'BusinessController@committeeMeeting']);

    Route::post('/businesses/card-payment/remove', ['as' => 'businesses.card.payment.remove', 'uses' => 'BusinessController@cardPaymentRemove']);
    Route::any('/businesses/card-payment/add/{business_id}/{id?}', ['as' => 'businesses.card.payment.add', 'uses' => 'BusinessController@cardPaymentAdd']);
    Route::any('/businesses/card-payment/get/{business_id}/{id?}', ['as' => 'businesses.card.payment.get', 'uses' => 'BusinessController@cardPaymentGet']);
    Route::any('/businesses/card-payment/{id}', ['as' => 'businesses.card.payment', 'uses' => 'BusinessController@cardPayment']);

    Route::post('/businesses/open-banking-payments/remove', ['as' => 'businesses.open.banking.payments.remove', 'uses' => 'BusinessController@openBankingPaymentsRemove']);
    Route::any('/businesses/open-banking-payments/add/{business_id}/{id?}', ['as' => 'businesses.open.banking.payments.add', 'uses' => 'BusinessController@openBankingPaymentsAdd']);
    Route::any('/businesses/open-banking-payments/get/{business_id}/{id?}', ['as' => 'businesses.open.banking.payments.get', 'uses' => 'BusinessController@openBankingPaymentsGet']);
    Route::any('/businesses/open-banking-payments/{id}', ['as' => 'businesses.open.banking.payments', 'uses' => 'BusinessController@openBankingPayments']);

    Route::get('/director-search', ['as' => 'director.search', 'uses' => 'BusinessController@directorSearch']);

    Route::get('/keywords', ['as' => 'keywords', 'uses' => 'KeywordController@list']);
    Route::post('/keywords/import', ['as' => 'keywords.import', 'uses' => 'KeywordController@import']);
    Route::post('/keywords/export', ['as' => 'keywords.export', 'uses' => 'KeywordController@export']);
    Route::get('/keywords/export/file/{type?}', ['as' => 'keywords.export.file', 'uses' => 'KeywordController@exportFile']);
    Route::post('/keywords/remove', ['as' => 'keywords.remove', 'uses' => 'KeywordController@remove']);
    Route::any('/keywords/add', ['as' => 'keywords.add', 'uses' => 'KeywordController@add']);
    Route::any('/keywords/update/{id?}', ['as' => 'keywords.update', 'uses' => 'KeywordController@add']);

    Route::get('/brokers', ['as' => 'broker.list', 'uses' => 'BrokerController@list']);
    Route::post('/brokers/import', ['as' => 'broker.list.import', 'uses' => 'BrokerController@import']);
    Route::post('/brokers/export', ['as' => 'broker.list.export', 'uses' => 'BrokerController@export']);
    Route::get('/brokers/export/file/{type?}', ['as' => 'broker.list.export.file', 'uses' => 'BrokerController@exportFile']);
    Route::post('/brokers/remove', ['as' => 'broker.list.remove', 'uses' => 'BrokerController@remove']);
    Route::any('/brokers/add', ['as' => 'broker.list.add', 'uses' => 'BrokerController@add']);
    Route::any('/brokers/update/{id?}', ['as' => 'broker.list.update', 'uses' => 'BrokerController@add']);

    Route::any('/dd-control', ['as' => 'dd.control.add', 'uses' => 'DDControlController@add']);

    Route::get('/transaction-form', ['as' => 'transaction.form', 'uses' => 'TransactionFormController@list']);
    Route::any('/transaction-form/add', ['as' => 'transaction.form.add', 'uses' => 'TransactionFormController@add']);

    Route::get('/cais-file', ['as' => 'cais.file', 'uses' => 'CAISController@list']);
    Route::any('/cais-file/add', ['as' => 'cais.file.add', 'uses' => 'CAISController@add']);
    Route::post('/cais-file/update-values', ['as' => 'cais.file.update.values', 'uses' => 'CAISController@updateValues']);
    Route::post('/cais-file/action', ['as' => 'cais.file.action', 'uses' => 'CAISController@action']);

    Route::get('/open-banking-transactions', ['as' => 'open.banking.transactions', 'uses' => 'OpenBankingTransactionsController@list']);
    Route::any('/open-banking-transactions/add', ['as' => 'open.banking.transactions.add', 'uses' => 'OpenBankingTransactionsController@add']);
    Route::post('/open-banking-transactions/remove', ['as' => 'open.banking.transactions.remove', 'uses' => 'OpenBankingTransactionsController@remove']);
});