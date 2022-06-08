<?php

use Illuminate\Support\Facades\Route;


Route::get('/new-test', function () {
    dd("here");
});
Route::get('/get-suggestions', [\App\Http\Controllers\UserConnectionController::class, 'getSuggestion'])->name('get-suggestion');
Route::get('/get-sent-requests', [\App\Http\Controllers\UserConnectionController::class, 'getSentRequests'])->name('get-sent-requests');
Route::get('/get-received-requests', [\App\Http\Controllers\UserConnectionController::class, 'getReceivedRequests'])->name('get-received-requests');
Route::get('/get-connections', [\App\Http\Controllers\UserConnectionController::class, 'getConnections'])->name('get-connections');
Route::post('/send-request', [\App\Http\Controllers\UserConnectionController::class , 'sendRequest'])->name('send-request');
Route::post('/delete-request',[\App\Http\Controllers\UserConnectionController::class , 'deleteRequest'])->name('delete-request');
Route::post('/accept-request',[\App\Http\Controllers\UserConnectionController::class , 'acceptRequest'])->name('accept-request');
Route::post('/remove-connection',[\App\Http\Controllers\UserConnectionController::class , 'removeConnection'])->name('remove-connection');


