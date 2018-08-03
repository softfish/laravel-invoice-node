<?php

Route::group(['prefix' => 'api/invoices', 'middleware' => ['web', 'auth']], function() {
    Route::get('/', '\Feikwok\InvoiceNova\Http\Controllers\Api\InvoicesApiController@index');
    Route::get('/{id}', '\Feikwok\InvoiceNova\Http\Controllers\Api\InvoicesApiController@show');
    Route::post('/', '\Feikwok\InvoiceNova\Http\Controllers\Api\InvoicesApiController@store');
    Route::put('/{id}', '\Feikwok\InvoiceNova\Http\Controllers\Api\InvoicesApiController@update');

    Route::post('/{id}/billentries', '\Feikwok\InvoiceNova\Http\Controllers\Api\BillEntryApiController@store');
    Route::delete('/{invoice_id}/billentries/{id}', '\Feikwok\InvoiceNova\Http\Controllers\Api\BillEntryApiController@destroy');
});