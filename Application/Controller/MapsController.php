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
			// Initialize variables
			$id_world		= 1; // Sophia
			$id_areamap		= (isset($GLOBALS['params'][1])) ? trim(($GLOBALS['params'][1])) : false;
			// Fetch and model worlds for combo
			//$worlds			= $RepMap->getAllWorlds();
			//$worlds			= ($worlds) ? $ModMap->combo($worlds, true) : false;

			// Load World Map info
			if ($id_areamap) {
				$world			= $RepMap->getMapById($id_areamap);
			} else {
				$world			= $RepMap->getWorldMapById($id_world);
			}
			$mapname		= $world['vc_name'];
			$vc_id_areamap	= sprintf('%04d', $world['id']);
			// Get linking info
			$links			= $RepMap->getLinksIconsByAreaId($world['id']);
			// Model world
			$world			= ($world) ? $ModMap->world($world, $links) : false;

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
			View::set('branches',		$branches);
			View::set('id_world',		$id_world);
			View::set('tiletypes',		$tiletypes);
			View::set('world',			$world);
			View::set('mapname',		$mapname);
			View::set('id_areamap',		$id_areamap);
			View::set('vc_id_areamap',	$vc_id_areamap);
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
			$id_areamap				= (isset($GLOBALS['params'][1])) ? trim(($GLOBALS['params'][1])) : false;
			if (!$id_areamap) {
				$id_areamap			= (isset($_POST['id_areamap'])) ? trim(($_POST['id_areamap'])) : false;
			}
			$return					= false;
			// if area map id was sent
			if ($id_areamap) {
				// Load area information
				$area				= $RepMap->getAreaByAreaMapId($id_areamap);
				if ($area) {
					// Load Area Related info
					$map			= $RepMap->getMapById($area['id_areamap']);
					$parent_areamap	= $RepMap->getParentMapIdTypeByMapId($area['id_areamap']);
					$parent_id_areamap	= $parent_areamap['id_map_orign'];
					$mapname		= $map['vc_name'];
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
					View::set('id_areamap',			$area['id_areamap']);
					View::set('map',				$map);
					View::set('worlds',				$worlds);
					View::set('branches',			$branches);
					View::set('fields',				$fields);
					View::set('level',				$area['int_level']);
					View::set('mapname',			$mapname);
					View::set('parent_id_areamap',	$parent_id_areamap);
					View::set('vc_id_areamap',		sprintf('%04d', $area['id_areamap']));
					// Render view
					View::render('mapsEdit');
				}
			}
 		}

		/*
		Edit a Dungeon's first page - EditDungeon()
			@return format	- print
		*/
		public function EditDungeon() {
			// Declare classes
			$RepMap						= new RepMap();
			$ModMap						= new ModMap();
			// Initialize variables
			$id_areamap					= (isset($GLOBALS['params'][1])) ? trim(($GLOBALS['params'][1])) : false;
			$parent_id_areamap			= (isset($GLOBALS['params'][2])) ? trim(($GLOBALS['params'][2])) : false;
			if (!$id_areamap) {
				$id_areamap				= (isset($_POST['id_areamap'])) ? trim(($_POST['id_areamap'])) : false;
				$parent_id_areamap		= (isset($_POST['parent_id_areamap'])) ? trim(($_POST['parent_id_areamap'])) : false;
			}
			$return						= false;
			$link						= false;
			$id_tiletype				= 9; // Dungeon
			// if area map id was sent
			if ($id_areamap) {
				// Load area information
				$area					= $RepMap->getAreaByAreaMapId($id_areamap);
				// Get and model all tiles
				$tiles					= $RepMap->getAllTilesByTileTypeId($id_tiletype);
				$tiles					= ($tiles) ? $ModMap->listTiles($tiles) : false;
				if ($area) {
					// Load Area Related info
					$map				= $RepMap->getMapById($area['id_areamap']);
					$link_icon			= $RepMap->getLinksIconsByAreaId($area['id_areamap']);
					$parent_areamap		= ($parent_id_areamap) ? $RepMap->getMapInfoById($parent_id_areamap) : false;
					$parent_areamap		= (!$parent_areamap) ? $RepMap->getParentMapIdTypeByMapId($area['id_areamap']) : $parent_areamap;
					$parent_id_areamap	= (!$parent_id_areamap) ? $parent_areamap['id_map_orign'] : $parent_id_areamap;
					$worlds				= $RepMap->getAllWorlds();
					$mapname			= $map['vc_name'];
					// Model Area Related info
					$map				= $ModMap->dungeon($map, $link_icon);
					// Select "Back" link
					if ($parent_areamap['id_areatype'] == 2) {
						$link			= '/gamemaster/Maps/EditDungeon/'.$parent_id_areamap;
					} else {
						$link			= '/gamemaster/Maps/EditLocalMap/'.$parent_id_areamap;
					}
					// Define sub menu selection
					$GLOBALS['menu']['maps']['opt1_css'] = 'details_item_on';
					// Prepare return values
					View::set('id_areamap',			$area['id_areamap']);
					View::set('back_link',			$link);
					View::set('vc_id_areamap',		sprintf('%04d', $area['id_areamap']));
					View::set('mapname',			$mapname);
					View::set('id_world',			$area['id_world']);
					View::set('id_field',			$area['id_field']);
					View::set('int_level',			$area['int_level']);
					View::set('map',				$map);
					View::set('tiles',				$tiles);
					View::set('parent_id_areamap',	$parent_id_areamap);
					// Render view
					View::render('dungeonsEdit');
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
		Prints out new Encounter area first page - NewEncounterArea()
			@return format	- print
		*/
		public function NewEncounterArea() {
			// Declare classes
			$RepMap			= new RepMap();
			$ModMap			= new ModMap();
			// Initialize variables
			$return			= false;
			$id_world		= (isset($GLOBALS['params'][1])) ? trim(($GLOBALS['params'][1])) : false;
			$id_field		= (isset($GLOBALS['params'][2])) ? trim(($GLOBALS['params'][2])) : false;
			$int_level		= (isset($GLOBALS['params'][3])) ? trim(($GLOBALS['params'][3])) : false;
			$id_areamap		= (isset($GLOBALS['params'][4])) ? trim(($GLOBALS['params'][4])) : false;
			$parent_pos		= (isset($GLOBALS['params'][5])) ? trim(($GLOBALS['params'][5])) : false;
			$id_areatype	= 2; // Dungeon
			$id_tiletype	= 9; // Dungeon
			// If values were sent
			if (($parent_pos) && ($id_areamap)) {
				// Get and model all tiles
				$tiles		= $RepMap->getAllTilesByTileTypeId($id_tiletype);
				$tiles		= ($tiles) ? $ModMap->listTiles($tiles) : false;
				//$tiles		= $ModMap->list($tiles, true);
				// if tiles were found
				if ($tiles) {
					// Model map
					$map	= $ModMap->newDungeon();
					// Define sub menu selection
					$GLOBALS['menu']['maps']['opt1_css'] = 'details_item_on';
					// Prepare return values
					View::set('map',				$map);
					View::set('parent_pos',			$parent_pos);
					View::set('parent_id_areamap',	$id_areamap);
					View::set('tiles',				$tiles);
					View::set('id_areatype',		$id_areatype);
					View::set('id_world',			$id_world);
					View::set('id_field',			$id_field);
					View::set('int_level',			$int_level);
					// Render view
					View::render('dungeonsNew');
				}
			}
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
				$links	= $RepMap->getLinksIconsByAreaId($world['id']);
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
			$vc_name			= (isset($_POST['vc_name'])) ? trim($_POST['vc_name']) : false;
			if ($_POST['coords']) {
				for ($i = 0; $i < 100; $i++) {
					$coords[$i]	= $_POST['coords'][$i+1];
				}
			}
			// If data was sent
			if (($id_areatype) && ($world_pos) && ($id_world) && ($id_field) && ($coords) && ($level) && ($vc_name)) {
				// Save map area
				$id_areamap		= $RepMap->insertMap($id_areatype, $vc_name, $coords);
				// If map area was saved
				if ($id_areamap) {
					// Save area info
					$id_area	= $RepMap->insertArea($id_world, $id_areatype, $id_field, $id_areamap, $level, 1);
					// If info was saved
					if ($id_area) {
						// Create Link
						$res	= $RepMap->addIconLink($id_world, $id_areamap, false, $world_pos, false);
						if ($res) {
							// Change world map and prepare return
							$return	= ($RepMap->updateWorldMap($id_world, $world_pos, 'unveiled.gif')) ? $id_areamap : 'nok';
						}
					}
				}
			}
			// Return
			echo $return;
		}

		/*
		 Saves a Dungeon - saveDungeon()
			@return format	- print
		*/
		public function saveDungeon() {
			// Declare Classes
			$RepMap				= new RepMap();
			$ModMap				= new ModMap();
			// Initialize variables
			$return				= false;
			$id_world			= (isset($_POST['id_world'])) ? trim($_POST['id_world']) : false;
			$id_field			= (isset($_POST['id_field'])) ? trim($_POST['id_field']) : false;
			$int_level			= (isset($_POST['int_level'])) ? trim($_POST['int_level']) : false;
			$id_areatype		= (isset($_POST['id_areatype'])) ? trim($_POST['id_areatype']) : false;
			$parent_pos			= (isset($_POST['parent_pos'])) ? trim($_POST['parent_pos']) : false;
			$parent_id_areamap	= (isset($_POST['parent_id_areamap'])) ? trim($_POST['parent_id_areamap']) : false;
			$vc_name			= (isset($_POST['vc_name'])) ? trim($_POST['vc_name']) : false;
			if ($_POST['coords']) {
				for ($i = 0; $i < 100; $i++) {
					$coords[$i]	= $_POST['coords'][$i+1];
				}
			}
			// If data was sent
			if (($id_world) && ($id_field) && ($int_level) && ($id_areatype) && ($parent_pos) && ($vc_name) && ($parent_id_areamap) && ($coords)) {
				// Save dungeon area
				$id_areamap		= $RepMap->insertMap($id_areatype, $vc_name, $coords);
				// If map area was saved
				if ($id_areamap) {
					// Save area info
					$id_area	= $RepMap->insertArea($id_world, $id_areatype, $id_field, $id_areamap, $int_level, 1);
					// If info was saved
					if ($id_area) {
						// Create Link
						$res	= $RepMap->addIconLink($parent_id_areamap, $id_areamap, false, $parent_pos, false);
						// Prepare return
						$return	= $id_areamap;
					}
				}
			}
			// Return
			echo $return;
		}

		/*
		 Updates a Map - updateMap()
			@return format	- print
		*/
		public function updateMap() {
			// Declare Classes
			$RepMap				= new RepMap();
			$ModMap				= new ModMap();
			// Initialize variables
			$return				= false;
			$id_areamap			= (isset($_POST['id_areamap'])) ? trim($_POST['id_areamap']) : false;
			$id_areatype		= (isset($_POST['id_areatype'])) ? trim($_POST['id_areatype']) : false;
			$id_field			= (isset($_POST['id_field'])) ? trim($_POST['id_field']) : false;
			$int_level			= (isset($_POST['int_level'])) ? trim($_POST['int_level']) : false;
			if ($_POST['coords']) {
				for ($i = 0; $i < 100; $i++) {
					$coords[$i]	= $_POST['coords'][$i+1];
				}
			}
			// If data was sent
			if (($id_areamap) && ($coords) && ($id_areatype) && ($id_field) && ($int_level)) {
				// Update map and area info
				$id_areamap		= $RepMap->updateMap($id_areamap, $id_areatype, $coords);
				$return			= ($RepMap->updateArea($id_areatype, $id_field, $id_areamap, $int_level, 1)) ? 'ok' : false;
			}
			// Return
			echo $return;
		}

		/*
		 Deeltes a Map - deleteMap()
			@return format	- print
		*/
		public function deleteMap() {
			// Declare Classes
			$RepMap				= new RepMap();
			$ModMap				= new ModMap();
			// Initialize variables
			$return				= false;
			$id_areamap			= (isset($_POST['id_areamap'])) ? trim($_POST['id_areamap']) : false;
			$parent_id_areamap	= (isset($_POST['parent_id_areamap'])) ? trim($_POST['parent_id_areamap']) : false;
			// If data was sent
			if (($id_areamap) && ($parent_id_areamap)) {
				// get this maps position on parent map
				$pos			= $RepMap->getPositionOnParentMap($parent_id_areamap, $id_areamap);
				// Delete maps this map links to
				$linked_maps	= $RepMap->getMapIdsByParentMapId($id_areamap);
				$parent_map		= $RepMap->getMapInfoById($parent_id_areamap);
				if ($linked_maps) {
					$RepMap->deleteAllMapInfoByMapId($linked_maps);
				}
				// Delete this map
				$res			= $RepMap->deleteAllMapInfoByMapId($id_areamap);
				// Prepare return link
				if ($res) {
					$parent_map		= $RepMap->getMapInfoById($parent_id_areamap);
					if ($parent_map['id_areatype'] == 4) { // World
						if ($pos) {
							$RepMap->eraseWorldTileByMapId($parent_id_areamap, sprintf('%03d', $pos));
						}
						$return			= '/gamemaster/Maps/Insert/'.$parent_id_areamap;
					} else if ($parent_map['id_areatype'] == 2) { // Local Map
						$return			= '/gamemaster/Maps/EditDungeon/'.$parent_id_areamap;
					} else {
						$return			= '/gamemaster/Maps/EditLocalMap/'.$parent_id_areamap;
					}
				}
			}
			// Return
			echo $return;
		}

		/*
		 Changes map background tile - changeTile()
			@return format	- print
		*/
		public function changeTile() {
			// Declare Classes
			$RepMap				= new RepMap();
			$ModMap				= new ModMap();
			// Initialize variables
			$return				= false;
			$pos				= (isset($_POST['pos'])) ? trim($_POST['pos']) : false;
			$id_tiletype		= (isset($_POST['id_tiletype'])) ? trim($_POST['id_tiletype']) : false;
			// If values were sent
			if ($pos) {
				// Get and model Tile Types
				$tiletypes		= $RepMap->getAllTileTypes();
				$id_tiletype	= (!$id_tiletype) ? $tiletypes[0]['id'] : $id_tiletype;
				$tiletypes		= ($tiletypes) ? $ModMap->combo($tiletypes, false, $id_tiletype) : false;
				// Get and model tiles
				$tiles			= $RepMap->getTilesByTileTypeId($id_tiletype);
				$tiles			= ($tiles) ? $ModMap->listTiles($tiles) : false;
				// Model return
				$return			= $ModMap->changeTile($tiletypes, $tiles);
			}
			// Return
			echo $return;
		}

		/*
		 Load map background tile - loadTile()
			@return format	- print
		*/
		public function loadTile() {
			// Declare Classes
			$RepMap			= new RepMap();
			$ModMap			= new ModMap();
			// Initialize variables
			$return			= false;
			$id_tile		= (isset($_POST['id_tile'])) ? trim($_POST['id_tile']) : false;
			// If values were sent
			if ($id_tile) {
				// Load and Model Tile
				$tile		= $RepMap->getTileById($id_tile);
				$return		= ($tile) ? $tile['vc_path'] : false;
			}
			// Return
			echo $return;
		}

		/*
		 Load tile list - loadTileList()
			@return format	- print
		*/
		public function loadTileList() {
			// Declare Classes
			$RepMap			= new RepMap();
			$ModMap			= new ModMap();
			// Initialize variables
			$return			= false;
			$id_tiletype	= (isset($_POST['id_tiletype'])) ? trim($_POST['id_tiletype']) : false;
			// If values were sent
			if ($id_tiletype) {
				// Load and Model Tile
				$tiles		= $RepMap->getTilesByTileTypeId($id_tiletype);
				$return		= ($tiles) ? $ModMap->listTiles($tiles) : false;
			}
			// Return
			echo $return;
		}

	}