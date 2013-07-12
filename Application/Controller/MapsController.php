<?php
/************************************************************************************
* Name:				Maps Controller													*
* File:				Application\Controller\MapsController.php 						*
* Author(s):		Vinas de Andrade												*
*																					*
* Description: 		This files holds general functions that relate to user session,	*
*					and can be accessed from anywhere.								*
*																					*
* Creation Date:	04/07/2013														*
* Version:			1.13.0704														*
* License:			http://www.opensource.org/licenses/bsd-license.php BSD			*
*************************************************************************************/

	namespace Application\Controller;

	// Framework Classes
	use SaSeed\View;
	use SaSeed\Session;
	use SaSeed\General;

	// Model Classes
	use Application\Model\Menu;
	use Application\Model\Pager;
	use Application\Model\Map						as ModMap;

	// Repository Classes
	use Application\Controller\Repository\Map		as RepMap;
	use Application\Controller\Repository\Question	as RepQuestion;

	// Other Classes
	use Application\Controller\LogInController		as LogIn;

	class MapsController{

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
				$GLOBALS['this_js']		= '<script type="text/javascript" src="/gamemaster/Application/View/js/scripts/maps.js"></script>'.PHP_EOL;	// Se não houver, definir como vazio ''
				$GLOBALS['this_js']		.= '<script type="text/javascript" src="/gamemaster/Application/View/js/libs/jquery.fancybox-1.3.4.pack.js"></script>'.PHP_EOL;	// Se não houver, definir como vazio ''
				$GLOBALS['this_css']	= '<link href="'.URL_PATH.'/Application/View/css/maps.css" rel="stylesheet">'.PHP_EOL;	// Se não houver, definir como vazio ''
				$GLOBALS['this_css']	.= '<link href="'.URL_PATH.'/Application/View/css/jquery.fancybox-1.3.4.css" rel="stylesheet">'.PHP_EOL;	// Se não houver, definir como vazio ''
				// Define Menu selection
				Menu::defineSelected($GLOBALS['controller_name']);
			}
		}

		/*
		Prints out main blank page - index()
			@return format	- print
		*/
		public function index() {
			View::render('maps');
 		}

		/*
		Prints out new Maps first page - Insert()
			@return format	- print
		*/
		public function Insert() {
			// Declare classes
			$RepMap			= new RepMap();
			$ModMap			= new ModMap();
			// Fetch and model worlds for combo
			$worlds			= $RepMap->getAllWorlds();
			$worlds			= ($worlds) ? $ModMap->combo($worlds, true) : false;
			// Fetch and model tile types for combo
			$tiletypes		= $RepMap->getAllTileTypes();
			$tiletypes		= ($tiletypes) ? $ModMap->combo($tiletypes, true) : false;
			// Fetch and model Branches for combo
			$RepQuestion	= new RepQuestion();
			$branches		= $RepQuestion->getAllBranches();
			$branches		= ($branches) ? $ModMap->combo($branches, true) : false;
			// Define sub menu selection
			$GLOBALS['menu']['maps']['opt1_css'] = 'details_item_on';
			// Prepare return values
			View::set('worlds',		$worlds);
			View::set('tiletypes',	$tiletypes);
			View::set('branches',	$branches);
			// Render view
			View::render('mapsInsert');
 		}

		/*
		Prints out new Maps first page - NewMap()
			@return format	- print
		*/
		public function NewLocalMap() {
			// Declare classes
			$RepMap			= new RepMap();
			$ModMap			= new ModMap();
			// Initialize variables
			$return			= false;
			$id_world		= (isset($_POST['id_world'])) ? trim($_POST['id_world']) : false;
			$position		= (isset($_POST['position'])) ? trim($_POST['position']) : false;
			$id_tiletype	= (isset($_POST['id_tiletype'])) ? trim($_POST['id_tiletype']) : false;
			$id_areatype	= 1;
			// Get all tiles
			$tiles			= $RepMap->getAllTilesByTileTypeId($id_tiletype);
			// if tiles were found
			if ($tiles) {
				// Model map
				$return	= $ModMap->newMap($tiles);
			}
			// Return
			echo $return;
 		}

		/*
		Prints out a world - Insert()
			@return format	- print
		*/
		public function loadWorldMap() {
			// Declare Classes
			$RepMap		= new RepMap();
			$ModMap		= new ModMap();
			// Initialize variables
			$return		= false;
			$id_world	= (isset($_POST['id_world'])) ? trim($_POST['id_world']) : false;
			// If values were sent
			if ($id_world) {
				// Load World Map info
				$world	= $RepMap->getWorldMapById($id_world);
				// Model world
				$return	= ($world) ? $ModMap->world($world) : false;
			}
			// Return
			echo $return;
		}

		/*
		Generates a new local map (random texture) - generateLocalMap()
			@return format	- print
		*/
		public function generateLocalMap() {
			// Declare Classes
			$RepMap		= new RepMap();
			$ModMap		= new ModMap();
			// Initialize variables
			$return		= false;
			$id_world	= (isset($_POST['id_world'])) ? trim($_POST['id_world']) : false;
			// If values were sent
			if ($id_world) {
				// Load World Map info
				$world	= $RepMap->getWorldMapById($id_world);
				// Model world
				$return	= ($world) ? $ModMap->map($world) : false;
			}
			// Return
			echo $return;
		}

		/*
		 Loads Fields for combo box - loadFields()
			@return format	- print
		*/
		public function loadFields() {
			// Declare Classes
			$RepQuestion	= new RepQuestion();
			$ModMap			= new ModMap();
			// Initialize variables
			$return			= false;
			$id_branch		= (isset($_POST['id_branch'])) ? trim($_POST['id_branch']) : false;
			// If values were sent
			if ($id_branch) {
				// Load World Map info
				$fields		= $RepQuestion->getFieldsBranchId($id_branch);
				// Model world
				$return		= ($fields) ? $ModMap->combo($fields) : false;
			}
			// Return
			echo $return;
		}

		/*
		 Loads icons for map building - listIcons()
			@return format	- print
		*/
		public function listIcons() {
			// Declare Classes
			$RepMap			= new RepMap();
			$ModMap			= new ModMap();
			// Load and model Icon list
			$icons		= $RepMap->getAllIcons();
			$return		= ($icons) ? $ModMap->listIcons($icons) : false;
			// Return
			echo $return;
		}


	}