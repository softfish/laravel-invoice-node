<?php

Route::group(['middleware' => ['web', 'auth']], function() {
    Route::get('/innov/invoices', '\Feikwok\InvoiceNova\Http\Controllers\InvoicesController@index');//->middleware('auth');
    Route::get('/innov/invoices/{id}', '\Feikwok\InvoiceNova\Http\Controllers\InvoicesController@show');//->middleware('auth');
    Route::get('/innov/invoices/{ref}/preview', '\Feikwok\InvoiceNova\Http\Controllers\InvoicesController@preview');//->middleware('auth');
    Route::get('/innov/invoices/{ref}/download', '\Feikwok\InvoiceNova\Http\Controllers\InvoicesController@downloadPDF');
    Route::get('/innov/invoices/{id}/resend', '\Feikwok\InvoiceNova\Http\Controllers\InvoicesController@resendMail');
});

Route::get('/innov/invoices/{ref}/payment', '\Feikwok\InvoiceNova\Http\Controllers\InvoicesController@showPayment');
Route::post('/innov/invoices/{ref}/payment', '\Feikwok\InvoiceNova\Http\Controllers\InvoicesController@processPayment');