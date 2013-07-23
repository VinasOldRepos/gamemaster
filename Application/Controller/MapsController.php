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
		Edit A Local Map's first page - EditLocalMap()
			@return format	- print
		*/
		public function EditLocalMap() {
			// Declare classes
			$RepMap					= new RepMap();
			$ModMap					= new ModMap();
			// Initialize variables
			$id_area				= (isset($GLOBALS['params'][1])) ? trim(($GLOBALS['params'][1])) : false;
			if (!$id_area) {
				$id_area			= (isset($_POST['id_area'])) ? trim(($_POST['id_area'])) : false;
			}
			$return					= false;
			// if area id was sent
			if ($id_area) {
				// Load area information
				$area				= $RepMap->getAreaById($id_area);
				if ($area) {
					// Load Area Related info
					$map			= $RepMap->getMapById($area['id_areamap']);
					$link_icon		= $RepMap->getLinksIconsByAreaId($area['id_areamap']);
					$worlds			= $RepMap->getAllWorlds();
					// Model Area Related info
					$map			= $ModMap->map($map, $link_icon);
					$worlds			= ($worlds) ? $ModMap->combo($worlds, false, $area['id_world']) : false;
					// Load Field related info
					$RepQuestion	= new RepQuestion();
					$id_branch		= ($area['id_field']) ? $RepQuestion->getBranchIdByFieldId($area['id_field']) : false;
					$branches		= $RepQuestion->getAllBranches();
					$fields			= $RepQuestion->getAllFields();
					// Model Field Related Info
					$branches		= ($branches) ? $ModMap->combo($branches, false, $id_branch) : false;
					$fields			= ($fields) ? $ModMap->combo($fields, false, $area['id_field']) : false;
					// Define sub menu selection
					$GLOBALS['menu']['maps']['opt1_css'] = 'details_item_on';
					// Prepare return values
					View::set('map',		$map);
					View::set('worlds',		$worlds);
					View::set('branches',	$branches);
					View::set('fields',		$fields);
					View::set('level',		$area['int_level']);
					// Render view
					View::render('mapsEdit');
				}
			}
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
		Prints out a world - loadWorldMap()
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
				// Get linking info
				$links	= $RepMap->getLinksIconsByAreaId($id_world);
				// Model world
				$return	= ($world) ? $ModMap->world($world, $links) : false;
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
				$return		= ($fields) ? $ModMap->combo($fields, true) : false;
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

		/*
		 Saves a Map - saveMap()
			@return format	- print
		*/
		public function saveMap() {
			// Declare Classes
			$RepMap				= new RepMap();
			$ModMap				= new ModMap();
			// Initialize variables
			$return				= false;
			$id_areatype		= (isset($_POST['id_areatype'])) ? trim($_POST['id_areatype']) : false;
			$world_pos			= (isset($_POST['world_pos'])) ? trim($_POST['world_pos']) : false;
			$id_world			= (isset($_POST['id_world'])) ? trim($_POST['id_world']) : false;
			$id_field			= (isset($_POST['id_field'])) ? trim($_POST['id_field']) : false;
			$level				= (isset($_POST['level'])) ? trim($_POST['level']) : false;
			if ($_POST['coords']) {
				for ($i = 0; $i < 100; $i++) {
					$coords[$i]	= $_POST['coords'][$i+1];
				}
			}
			// If data was sent
			if (($id_areatype) && ($world_pos) && ($id_world) && ($id_field) && ($coords) && ($level)) {
				// Save map area
				$id_areamap		= $RepMap->insertMap($id_areatype, $coords);
				// If map area was saved
				if ($id_areamap) {
					// Save area info
					$id_area	= $RepMap->insertArea($id_areatype, $id_field, $id_areamap, $level, 1);
					// If info was saved
					if ($id_area) {
						// Create Link
						$res	= $RepMap->addIconLink($id_world, $id_area, false, $world_pos);
						if ($res) {
							// Change world map and prepare return
							$return	= ($RepMap->updateWorldMap($id_world, $world_pos, 'unveiled.gif')) ? $id_area : 'nok';
						}
					}
				}
			}
			// Return
			echo $return;
		}

	}