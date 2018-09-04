<?php

Route::group(['middleware' => ['web', 'auth']], function() {
    Route::get('/innov/invoices', '\Feikwok\InvoiceNode\Http\Controllers\InvoicesController@index');//->middleware('auth');
    Route::get('/innov/invoices/{id}', '\Feikwok\InvoiceNode\Http\Controllers\InvoicesController@show');//->middleware('auth');
    Route::get('/innov/invoices/{ref}/preview', '\Feikwok\InvoiceNode\Http\Controllers\InvoicesController@preview');//->middleware('auth');
    Route::get('/innov/invoices/{ref}/download', '\Feikwok\InvoiceNode\Http\Controllers\InvoicesController@downloadPDF');
    Route::get('/innov/invoices/{id}/resend', '\Feikwok\InvoiceNode\Http\Controllers\InvoicesController@resendMail');
});

Route::get('/innov/invoices/{ref}/payment', '\Feikwok\InvoiceNode\Http\Controllers\InvoicesController@showPayment');
Route::post('/innov/invoices/{ref}/payment', '\Feikwok\InvoiceNode\Http\Controllers\InvoicesController@processPayment');
Route::post('/innov/invoices/{ref}/banktransfer', '\Feikwok\InvoiceNode\Http\Controllers\InvoicesController@bankTransfer');