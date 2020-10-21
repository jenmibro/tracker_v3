<?php

/**
 * Application endpoints
 */
Route::get('/home', 'AppController@index')->name('home');
Route::get('/', 'AppController@index')->name('index');
Route::get('/impersonate-end/', 'ImpersonationController@endImpersonation')->name('end-impersonation');
Route::get('/impersonate/{user}', 'ImpersonationController@impersonate')->name('impersonate');

Route::group(['prefix' => 'training'], function () {
    Route::get('sgt', 'TrainingController@sgtTraining')->name('training.sgt');
    Route::get('ssgt', 'TrainingController@index')->name('training.ssgt');
    Route::get('msgt', 'TrainingController@index')->name('training.msgt');
    Route::post('', 'TrainingController@update')->name('training.update');
});

if (config('app.ticketing_enabled')) {
    Route::name('help.tickets.')->prefix('help/tickets')->group(function () {
        Route::get('', 'TicketController@index')->name('index');
        Route::get('create', 'TicketController@create')->name('create');
        Route::post('', 'TicketController@store')->name('store');
        Route::delete('', 'TicketController@store')->name('store');
        Route::get('{ticket}', 'TicketController@show')->name('show');
    });
}


Route::group(['prefix' => 'help'], function () {
    Route::get('/', 'HelpController@index')->name('help');
    Route::get('/division-structures', 'HelpController@divisionStructures')->name('divisionStructures');
});

Route::get('changelog', 'AppController@changelog')->name('changelog');
Route::get('developers', 'DeveloperController@index')->name('developer');


/**
 * Application UI
 */
Route::group(['prefix' => 'primary-nav'], function () {
    Route::get('collapse', function () {
        session(['primary_nav_collapsed' => true]);
    });
    Route::get('decollapse', function () {
        session(['primary_nav_collapsed' => false]);
    });
});
