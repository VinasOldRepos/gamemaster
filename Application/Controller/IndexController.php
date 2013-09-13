<?php
/************************************************************************************
* Name:				Index Controller												*
* File:				Application\Controller\IndexController.php 						*
* Author(s):		Vinas de Andrade												*
*																					*
* Description: 		This is the home page's controller.								*
*																					*
* Creation Date:	14/11/2012														*
* Version:			1.12.0723														*
* License:			http://www.opensource.org/licenses/bsd-license.php BSD			*
*************************************************************************************/

	namespace Application\Controller;

	// Framework Classes
	use SaSeed\View;
	use SaSeed\Session;

	// Model Classes
	use Application\Model\Menu;
	use Application\Model\Index					as ModIndex;

	// Other Classes
	use Application\Controller\LogInController	as LogIn;

	class IndexController {

		public function __construct() {
			// Start session
			Session::start();
			// Check if user is Logged
			$SesUser					= LogIn::checkLogin();
			if (!$SesUser) {
				// Redirect to login area when not
				header('location: '.URL_PATH.'/LogIn/');
			} else {
				// Define JSs e CSSs utilizados por este controller
				$GLOBALS['this_js']		= '<script type="text/javascript" src="/gamemaster/Application/View/js/libs/jquery.fancybox-1.3.4.pack.js"></script>'.PHP_EOL;	// Se não houver, definir como vazio ''
				$GLOBALS['this_css']	= '<link href="'.URL_PATH.'/Application/View/css/jquery.fancybox-1.3.4.css" rel="stylesheet">'.PHP_EOL;	// Se não houver, definir como vazio ''
				// Define Menu selection
				Menu::defineSelected($GLOBALS['controller_name']);
			}
		}

		/*
		Prints out main home page - start()
			@return format	- print
		*/
		public static function index() {
			View::render('index');
		}
	}