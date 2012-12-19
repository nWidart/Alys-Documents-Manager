<?php

Route::get('/', array(
	'as' => 'homepage',
	'uses' => 'home@index'
));
Route::get('library', array(
	'as' => 'docIndex',
	'uses' => 'documents@index'
));
Route::get('library/new', array(
	'as' => 'new_doc',
	'uses' => 'documents@new'
));
Route::post('library/new', 'documents@new');

Route::get('library/(:num)/edit', array(
	'as' => 'edit_doc',
	'uses' => 'documents@edit'
) );
Route::get('library/(:num)/edit/(:num)', array(
	'as' => 'edit_doc_info',
	'uses' => 'documents@edit_doc'
) );

Route::post('library/update/(:num)', 'documents@update' );

Route::get('library/(:num)/destroy', array(
	'as' => 'destroy_doc',
	'uses' => 'documents@destroy'
) );

Route::post('library/create', 'documents@create');



/*
|--------------------------------------------------------------------------
| Application 404 & 500 Error Handlers
|--------------------------------------------------------------------------
|
| To centralize and simplify 404 handling, Laravel uses an awesome event
| system to retrieve the response. Feel free to modify this function to
| your tastes and the needs of your application.
|
| Similarly, we use an event to handle the display of 500 level errors
| within the application. These errors are fired when there is an
| uncaught exception thrown in the application.
|
*/

Event::listen('404', function()
{
	return Response::error('404');
});

Event::listen('500', function()
{
	return Response::error('500');
});

/*
|--------------------------------------------------------------------------
| Route Filters
|--------------------------------------------------------------------------
|
| Filters provide a convenient method for attaching functionality to your
| routes. The built-in before and after filters are called before and
| after every request to your application, and you may even create
| other filters that can be attached to individual routes.
|
| Let's walk through an example...
|
| First, define a filter:
|
|		Route::filter('filter', function()
|		{
|			return 'Filtered!';
|		});
|
| Next, attach the filter to a route:
|
|		Router::register('GET /', array('before' => 'filter', function()
|		{
|			return 'Hello World!';
|		}));
|
*/

Route::filter('before', function()
{
	// Do stuff before every request to your application...
});

Route::filter('after', function($response)
{
	// Do stuff after every request to your application...
});

Route::filter('csrf', function()
{
	if (Request::forged()) return Response::error('500');
});

Route::filter('auth', function()
{
	if (Auth::guest()) return Redirect::to('login');
});
