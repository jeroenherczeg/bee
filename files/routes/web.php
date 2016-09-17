<?php

/*
|--------------------------------------------------------------------------
| Site Routes
|--------------------------------------------------------------------------
|
| Here we define routes for the public site. These routes are only for
| guests. We send out blade views for quick responses and don't use
| Vue.js because we want search engine crawlers to index the data.
*/

Route::get('{all?}', function () {
    return view('index');
})->where(['all' => '.*']);
