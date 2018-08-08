<?php

Route::group(['prefix' => 'api/invoices', 'middleware' => ['web', 'auth']], function() {
    Route::get('/', '\Feikwok\InvoiceNode\Http\Controllers\Api\InvoicesApiController@index');
    Route::get('/{id}', '\Feikwok\InvoiceNode\Http\Controllers\Api\InvoicesApiController@show');
    Route::post('/', '\Feikwok\InvoiceNode\Http\Controllers\Api\InvoicesApiController@store');
    Route::put('/{id}', '\Feikwok\InvoiceNode\Http\Controllers\Api\InvoicesApiController@update');

    Route::post('/{id}/billentries', '\Feikwok\InvoiceNode\Http\Controllers\Api\BillEntryApiController@store');
    Route::delete('/{invoice_id}/billentries/{id}', '\Feikwok\InvoiceNode\Http\Controllers\Api\BillEntryApiController@destroy');
});