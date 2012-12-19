<?php

class Base_Controller extends Controller {

	public function __construct()
	{
		// Assets,
		// Asset::add('jquery','http://code.jquery.com/jquery-latest.min.js');
		Asset::add('jquery','js/jquery.js');
		Asset::add('bootstrap-js', 'js/bootstrap.min.js');
		Asset::add('bootstrap-css', 'css/bootstrap.css');
		Asset::add('boostrap-css-responsive', 'css/bootstrap-responsive.min.css');
		Asset::add('toggle-buttons-css', 'css/bootstrap-toggle-buttons.css');
		Asset::add('toggle-buttons-js', 'js/jquery.toggle.buttons.js');
		Asset::add('style', 'css/style.css');
		Asset::add('alertify-css', 'css/alertify.core.css');
		Asset::add('alertify-js', 'js/alertify.js');
		parent::__construct();
	}
	/**
	 * Catch-all method for requests that can't be matched.
	 *
	 * @param  string    $method
	 * @param  array     $parameters
	 * @return Response
	 */
	public function __call($method, $parameters)
	{
		return Response::error('404');
	}

}
