<?php
/************************************************************************************
* Name:				Maps Controller													*
* File:				Application\Controller\MapsController.php 						*
* Author(s):		Vinas de Andrade												*
*																					*
* Description: 		This file controls Map related information.						*
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
			$id_areamap		= (isset($GLOBALS['params'][1])) ? trim(($GLOBALS['params'][1])) : 45;
			// Fetch and model worlds for combo
			//$worlds			= $RepMap->getAllWorlds();
			//$worlds			= ($worlds) ? $ModMap->combo($worlds, true) : false;
			// Load World Map info
			if ($id_areamap) {
				$world		= $RepMap->getMapById($id_areamap);
			} else {
				$world		= $RepMap->getWorldMapById($id_world);
			}
			$mapname		= $world['vc_name'];
			$vc_id_areamap	= sprintf('%04d', $world['id']);
			$id_areamap		= $world['id'];
			// Get directional links (world navigation)
			$navigation		= $RepMap->getNavigationLinkByAreaId($world['id']);
			// Get linking info
			$links			= $RepMap->getLinksIconsByAreaId($world['id']);
			// Model world
			$world			= ($world) ? $ModMap->world($world, $links, $navigation) : false;
			// Fetch and model tile types for combo
			$tiletypes		= $RepMap->getAllLocalAreaTileTypes();
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
			$RepMap						= new RepMap();
			$ModMap						= new ModMap();
			// Initialize variables
			$id_areamap					= (isset($GLOBALS['params'][1])) ? trim(($GLOBALS['params'][1])) : false;
			if (!$id_areamap) {
				$id_areamap				= (isset($_POST['id_areamap'])) ? trim(($_POST['id_areamap'])) : false;
			}
			$return						= false;
			// if area map id was sent
			if ($id_areamap) {
				// Load area information
				$area					= $RepMap->getAreaByAreaMapId($id_areamap);
				if ($area) {
					// Load Area Related info
					$map				= $RepMap->getMapById($area['id_areamap']);
					$id_areatype		= ($map) ? $map['id_areatype'] : false;
					$parent_areamap		= $RepMap->getParentMapIdTypeByMapId($area['id_areamap']);
					$parent_id_areamap	= $parent_areamap['id_map_orign'];
					$mapname			= $map['vc_name'];
					$mouseover			= $map['vc_mouseover'];
					$link_icon			= $RepMap->getLinksIconsByAreaId($area['id_areamap']);
					$worlds				= $RepMap->getAllWorlds();
					$world_pos			= $RepMap->getWorldPosition($parent_id_areamap, $id_areamap);
					// Model Area Related info
					$map				= $ModMap->map($map, $link_icon);
					$worlds				= ($worlds) ? $ModMap->combo($worlds, false, $area['id_world']) : false;
					// Load Field related info
					$RepQuestion		= new RepQuestion();
					$id_branch			= ($branch = $RepQuestion->getBranchFieldId($area['id_field'])) ? $branch['id'] : false;
					$branches			= $RepQuestion->getAllBranches();
					$fields				= $RepQuestion->getAllFields();
					// Model Field Related Info
					$branches			= ($branches) ? $ModMap->combo($branches, false, $id_branch) : false;
					$fields				= ($fields) ? $ModMap->combo($fields, false, $area['id_field']) : false;
					// Define sub menu selection
					$GLOBALS['menu']['maps']['opt1_css'] = 'details_item_on';
					// Prepare return values
					View::set('id_areamap',			$area['id_areamap']);
					View::set('id_areatype',		$area['id_areatype']);
					View::set('id_tiletype',		$area['id_areatype']);
					View::set('map',				$map);
					View::set('worlds',				$worlds);
					View::set('branches',			$branches);
					View::set('fields',				$fields);
					View::set('level',				$area['int_level']);
					View::set('mapname',			$mapname);
					View::set('mouseover',			$mouseover);
					View::set('parent_id_areamap',	$parent_id_areamap);
					View::set('vc_id_areamap',		sprintf('%04d', $area['id_areamap']));
					View::set('world_pos',			sprintf('%03d', $world_pos));
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
			$id_tiletype				= false;
			$id_areatype				= false;
			$id_course					= false;
			$mapname					= false;
			$mouseover					= false;
			$tot_treasure_min			= 0;
			$tot_treasure_max			= 0;
			$tot_monsters				= 0;
			// if area map id was sent
			if ($id_areamap) {
				// Load area information
				$area					= $RepMap->getAreaByAreaMapId($id_areamap);
				// Get and model detail tiles
				$detail_tiles			= $RepMap->getAllEncounterDtlTilesByTileTypeId();
				$detail_tiles			= ($detail_tiles) ? $ModMap->listEncounterDtlTiles($detail_tiles) : false;
				if ($area) {
					// Get and model tile types
					$tiletypes			= $RepMap->getAllEncounterTileTypes();
					$tiletypes			= $ModMap->combo($tiletypes, false, $area['id_areatype']);
					// Load Area Related info
					$map				= $RepMap->getMapById($area['id_areamap']);
					if ($map) {
						$id_tiletype	= $map['id_areatype'];
						$id_areatype	= $map['id_areatype'];
						$id_course		= $map['id_course'];
						$mapname		= $map['vc_name'];
						$mouseover		= $map['vc_mouseover'];
					}
					$tiles				= $RepMap->getAllEncounterBkgTilesByTileTypeId($id_tiletype);
					$link_icon			= $RepMap->getLinksIconsByAreaId($area['id_areamap']);
					$monsters			= $RepMap->getMonstersInMap($area['id_areamap']);
					$parent_areamap		= ($parent_id_areamap) ? $RepMap->getMapInfoById($parent_id_areamap) : false;
					$parent_areamap		= (!$parent_areamap) ? $RepMap->getParentMapIdTypeByMapId($area['id_areamap']) : $parent_areamap;
					$parent_id_areamap	= (!$parent_id_areamap) ? $parent_areamap['id_map_orign'] : $parent_id_areamap;
					$worlds				= $RepMap->getAllWorlds();
					$areas				= $RepMap->getAreasInMap($id_areamap);
					$RepQuestion		= new RepQuestion();
					$courses			= $RepQuestion->getCoursesByFieldId($area['id_field']);
					// Calculate total treasure drop
					if ($monsters) {
						$tot_monsters			= count($monsters);
						foreach ($monsters as $monster) {
							$tot_treasure_min	= $tot_treasure_min + $monster['int_treasure_min'];
							$tot_treasure_max	= $tot_treasure_max + $monster['int_treasure_max'];
						}
					}
					// Model Area Related info
					$map				= $ModMap->dungeon($map, $link_icon);
					$tiles				= ($tiles) ? $ModMap->listEncounterBkgTiles($tiles) : false;
					$monsters			= ($monsters) ? $ModMap->mapMonsters($monsters) : '';
					$areas				= ($areas)  ? $ModMap->listAreaOrder($areas) : false;
					$courses			= ($courses) ? $ModMap->combo($courses, false, $id_course) : false;
					// Select "Back" link
					if ($parent_areamap['boo_encounter'] == 1) {
						$link			= '/gamemaster/Maps/EditDungeon/'.$parent_id_areamap;
					} else {
						$link			= '/gamemaster/Maps/EditLocalMap/'.$parent_id_areamap;
					}
					// Define sub menu selection
					$GLOBALS['menu']['maps']['opt1_css'] = 'details_item_on';
					// Prepare return values
					View::set('id_areamap',			$area['id_areamap']);
					View::set('id_tiletype',		$id_tiletype);
					View::set('id_areatype',		$area['id_areatype']);
					View::set('back_link',			$link);
					View::set('vc_id_areamap',		sprintf('%04d', $area['id_areamap']));
					View::set('mapname',			$mapname);
					View::set('mouseover',			$mouseover);
					View::set('id_world',			$area['id_world']);
					View::set('id_field',			$area['id_field']);
					View::set('int_level',			$area['int_level']);
					View::set('map',				$map);
					View::set('tiles',				$tiles);
					View::set('detail_tiles',		$detail_tiles);
					View::set('tiletypes',			$tiletypes);
					View::set('parent_id_areamap',	$parent_id_areamap);
					View::set('monsters',			$monsters);
					View::set('tot_treasure_min',	$tot_treasure_min);
					View::set('tot_treasure_max',	$tot_treasure_max);
					View::set('tot_monsters',		$tot_monsters);
					View::set('areas',				$areas);
					View::set('courses',			$courses);
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
			$id_tiletype	= (isset($_POST['id_tiletype'])) ? trim($_POST['id_tiletype']) : false;
			// Get all tiles
			$tiles			= $RepMap->getAllLocalBkgTilesByTileTypeId($id_tiletype);
			// if tiles were found
			if ($tiles) {
				// Model map
				$return	= $ModMap->newMap($tiles);
			}
			// Return
			echo $return;
 		}

		/*
		Prints out new Dungeon first Map  page - NewEncounterMap()
			@return format	- print
		*/
		public function NewEncounterMap() {
			// Declare classes
			$RepMap			= new RepMap();
			$ModMap			= new ModMap();
			// Initialize variables
			$return			= false;
			$id_tiletype	= (isset($_POST['id_tiletype'])) ? trim($_POST['id_tiletype']) : false;
			// Define background
			$tiles[]['vc_path']	= 'blank_01.png';
			// Model map
			$return	= $ModMap->newMap($tiles);
			// Return
			echo $return;
 		}

		/*
		Prints out new Encounter area first page - NewEncounterArea()
			@return format	- print
		*/
		public function NewEncounterArea() {
			// Declare classes
			$RepMap				= new RepMap();
			$ModMap				= new ModMap();
			// Initialize variables
			$return				= false;
			$id_world			= (isset($GLOBALS['params'][1])) ? trim(($GLOBALS['params'][1])) : false;
			$id_field			= (isset($GLOBALS['params'][2])) ? trim(($GLOBALS['params'][2])) : false;
			$int_level			= (isset($GLOBALS['params'][3])) ? trim(($GLOBALS['params'][3])) : false;
			$id_areamap			= (isset($GLOBALS['params'][4])) ? trim(($GLOBALS['params'][4])) : false;
			$parent_pos			= (isset($GLOBALS['params'][5])) ? trim(($GLOBALS['params'][5])) : false;
			$id_areatype		= 2; // Dungeon
			$id_tiletype		= 9; // Dungeon
			// If values were sent
			if (($parent_pos) && ($id_areamap)) {
				// Get and model all encounter tiles types
				$tiletypes		= $RepMap->getAllEncounterTileTypes();
				$RepQuestion	= new RepQuestion();
				$courses		= ($id_field) ? $RepQuestion->getCoursesByFieldId($id_field) : false;
				$tiletypes		= ($tiletypes) ? $ModMap->combo($tiletypes, true) : false;
				$courses		= ($courses) ? $ModMap->combo($courses, true, $id_field) : false;
				// Define sub menu selection
				$GLOBALS['menu']['maps']['opt1_css'] = 'details_item_on';
				// Prepare return values
				View::set('parent_pos',			$parent_pos);
				View::set('parent_id_areamap',	$id_areamap);
				View::set('id_world',			$id_world);
				View::set('id_field',			$id_field);
				View::set('int_level',			$int_level);
				View::set('tiletypes',			$tiletypes);
				View::set('courses',			$courses);
				// Render view
				View::render('dungeonsNew');
			}
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
		 Loads Courses for combo box - loadCourses()
			@return format	- print
		*/
		public function loadCourses() {
			// Declare Classes
			$RepQuestion	= new RepQuestion();
			$ModMap			= new ModMap();
			// Initialize variables
			$return			= false;
			$id_field		= (isset($_POST['id_field'])) ? trim($_POST['id_field']) : false;
			// If values were sent
			if ($id_field) {
				// Load courses
				$fields		= $RepQuestion->getCoursesByFieldId($id_field);
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
			$id_areamap_orign	= (isset($_POST['id_areamap'])) ? trim($_POST['id_areamap']) : false;
			$id_field			= (isset($_POST['id_field'])) ? trim($_POST['id_field']) : false;
			$level				= (isset($_POST['level'])) ? trim($_POST['level']) : false;
			$vc_name			= (isset($_POST['vc_name'])) ? trim($_POST['vc_name']) : false;
			$vc_mouseover		= (isset($_POST['vc_mouseover'])) ? trim($_POST['vc_mouseover']) : false;
			if ($_POST['coords']) {
				for ($i = 0; $i < 100; $i++) {
					$coords[$i]	= $_POST['coords'][$i+1];
				}
			}
			// If data was sent
			if (($id_areatype) && ($id_areamap_orign) && ($world_pos) && ($id_world) && ($id_field) && ($coords) && ($level) && ($vc_name) && ($vc_mouseover)) {
				// Save map area
				$id_areamap		= $RepMap->insertMap(0, 0, $id_areatype, $vc_name, $vc_mouseover, $coords);
				// If map area was saved
				if ($id_areamap) {
					// Save area info
					$id_area	= $RepMap->insertArea($id_areamap_orign, $id_areatype, $id_field, $id_areamap, $level, 1);
					// If info was saved
					if ($id_area) {
						// Create Link
						$res	= $RepMap->addIconLink($id_areamap_orign, $id_areamap, false, $world_pos, false);
						if ($res) {
							// Creates world bckgrnd tile
							$map	= $RepMap->getMapById($id_areamap);
							$image	= $this->shrink($map);
							$image	= ($image) ? $image : 'unveiled.gif';
							// Change world map and prepare return'
							$return	= ($RepMap->updateWorldMap($id_areamap_orign, $world_pos, $image)) ? $id_areamap : 'nok';
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
			$vc_mouseover		= (isset($_POST['vc_mouseover'])) ? trim($_POST['vc_mouseover']) : false;
			$id_course			= (isset($_POST['id_course'])) ? trim($_POST['id_course']) : false;
			if ($_POST['coords']) {
				for ($i = 0; $i < 100; $i++) {
					$coords[$i]	= $_POST['coords'][$i+1];
				}
			}
			// If data was sent
			if (($id_world) && ($id_field) && ($int_level) && ($id_areatype) && ($parent_pos) && ($vc_name) && ($id_course) && ($vc_mouseover) && ($parent_id_areamap) && ($coords)) {
				// Save dungeon area
				$id_areamap		= $RepMap->insertMap(1, $id_course, $id_areatype, $vc_name, $vc_mouseover, $coords);
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
			$id_tiletype		= (isset($_POST['id_tiletype'])) ? trim($_POST['id_tiletype']) : false;
			$id_areatype		= (isset($_POST['id_areatype'])) ? trim($_POST['id_areatype']) : false;
			$id_field			= (isset($_POST['id_field'])) ? trim($_POST['id_field']) : false;
			$int_level			= (isset($_POST['int_level'])) ? trim($_POST['int_level']) : false;
			$world_pos			= (isset($_POST['world_pos'])) ? trim($_POST['world_pos']) : false;
			$id_areamap_orign	= (isset($_POST['id_areamap_orign'])) ? trim($_POST['id_areamap_orign']) : false;
			$vc_mouseover		= (isset($_POST['vc_mouseover'])) ? trim($_POST['vc_mouseover']) : false;
			$id_course			= (isset($_POST['id_course'])) ? trim($_POST['id_course']) : 0;
			if ($_POST['coords']) {
				for ($i = 0; $i < 100; $i++) {
					$coords[$i]	= $_POST['coords'][$i+1];
				}
			}
			// If data was sent
			if (($id_areamap) && ($coords) && ($id_tiletype) && ($id_field) && ($int_level) && ($vc_mouseover)) {
				// Update map and area info
				$id_areamap		= $RepMap->updateMap($id_areamap, $id_course, $vc_mouseover, $id_tiletype, $coords);
				// If we're updating a local map
				if (($id_areamap_orign) && ($id_areamap_orign)) {
					// Get and shrink Map
					$map			= $RepMap->getMapById($id_areamap);
					$image			= $this->shrink($map);
					$image			= ($image) ? $image : 'unveiled.gif';
					// Change world map and prepare return'
					$RepMap->updateWorldMap($id_areamap_orign, $world_pos, $image);
				}
				$return			= ($RepMap->updateArea($id_areatype, $id_field, $id_areamap, $int_level, 1)) ? 'ok' : false;
			}
			// Return
			echo $return;
		}

		/*
		 Updates a Map name - updateMapName()
			@return format	- print
		*/
		public function updateMapName() {
			// Declare Classes
			$RepMap				= new RepMap();
			$ModMap				= new ModMap();
			// Initialize variables
			$return				= false;
			$id_areamap			= (isset($_POST['id_areamap'])) ? trim($_POST['id_areamap']) : false;
			$vc_name			= (isset($_POST['vc_name'])) ? trim($_POST['vc_name']) : false;
			// If data was sent
			if (($id_areamap) && ($vc_name)) {
				// Update map and area info
				$return			= ($RepMap->updateMapName($id_areamap, $vc_name)) ? 'ok' : false;
			}
			// Return
			echo $return;
		}

		/*
		 Deletes a Map - deleteMap()
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
		 Deletes tile info - deleteTileInfo()
			@return format	- print
		*/
		public function deleteTileInfo() {
			// Declare Classes
			$RepMap		= new RepMap();
			$ModMap		= new ModMap();
			// Initialize variables
			$return		= false;
			$id_areamap	= (isset($_POST['id_areamap'])) ? trim($_POST['id_areamap']) : false;
			$pos		= (isset($_POST['pos'])) ? trim($_POST['pos']) : false;
			// If data was sent
			if (($id_areamap) && ($pos)) {
				// Delete this tile's info
				$return	= ($RepMap->deleteTileInfo($id_areamap, $pos)) ? 'ok' : false;
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
				$tiletypes		= $RepMap->getAllLocalAreaTileTypes();
				$tiletypes		= ($tiletypes) ? $ModMap->combo($tiletypes, false, $id_tiletype) : false;
				// Get and model tiles
				$tiles			= $RepMap->getAllLocalBkgTilesByTileTypeId($id_tiletype);
				$tiles			= ($tiles) ? $ModMap->listTiles($tiles) : false;
				// Model return
				$return			= $ModMap->changeTile($tiles, $tiletypes);
			}
			// Return
			echo $return;
		}

		/*
		 Changes map detail tile - changeDtlTile()
			@return format	- print
		*/
		public function changeDtlTile() {
			// Declare Classes
			$RepMap				= new RepMap();
			$ModMap				= new ModMap();
			// Initialize variables
			$return				= false;
			$pos				= (isset($_POST['pos'])) ? trim($_POST['pos']) : false;
			$id_tiletype		= (isset($_POST['id_tiletype'])) ? trim($_POST['id_tiletype']) : false;
			// If values were sent
			if ($pos) {
				// Get and model tiles
				$tiles			= $RepMap->getAllLocalDtlTilesByTileTypeId($id_tiletype);
				$tiles			= ($tiles) ? $ModMap->listDtlTiles($tiles) : false;
				// Model return
				$return			= $ModMap->changeTile($tiles);
			}
			// Return
			echo $return;
		}

		/*
		 Load Local Area background tile - loadTile()
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
				$tile		= $RepMap->getLocalBkgTileById($id_tile);
				$return		= ($tile) ? $tile['vc_path'] : false;
			}
			// Return
			echo $return;
		}

		/*
		 Load Local area detail tile - loadDtlTile()
			@return format	- print
		*/
		public function loadDtlTile() {
			// Declare Classes
			$RepMap			= new RepMap();
			$ModMap			= new ModMap();
			// Initialize variables
			$return			= false;
			$id_tile		= (isset($_POST['id_tile'])) ? trim($_POST['id_tile']) : false;
			// If values were sent
			if ($id_tile) {
				// Load and Model Tile
				$tile		= $RepMap->getLocalDtlTileById($id_tile);
				$return		= ($tile) ? $tile['vc_path'] : false;
			}
			// Return
			echo $return;
		}

		/*
		 Load Encounter map background tile - loadEncounterBkgTile()
			@return format	- print
		*/
		public function loadEncounterBkgTile() {
			// Declare Classes
			$RepMap			= new RepMap();
			$ModMap			= new ModMap();
			// Initialize variables
			$return			= false;
			$id_tile		= (isset($_POST['id_tile'])) ? trim($_POST['id_tile']) : false;
			// If values were sent
			if ($id_tile) {
				// Load and Model Tile
				$tile		= $RepMap->getEncounterBkgTileById($id_tile);
				$return		= ($tile) ? $tile['vc_path'] : false;
			}
			// Return
			echo $return;
		}

		/*
		 Load Encounter map background tile - loadEncounterDtlTile()
			@return format	- print
		*/
		public function loadEncounterDtlTile() {
			// Declare Classes
			$RepMap			= new RepMap();
			$ModMap			= new ModMap();
			// Initialize variables
			$return			= false;
			$id_tile		= (isset($_POST['id_tile'])) ? trim($_POST['id_tile']) : false;
			// If values were sent
			if ($id_tile) {
				// Load and Model Tile
				$tile		= $RepMap->getEncounterDtlTileById($id_tile);
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
				$tiles		= $RepMap->getAllLocalBkgTilesByTileTypeId($id_tiletype);
				$return		= ($tiles) ? $ModMap->listTiles($tiles) : 'No Tiles found.';
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
				$links	= $RepMap->getLinksIconsByAreaId($world['id']);
				// Model world
				$return	= ($world) ? $ModMap->world($world, $links) : false;
			}
			// Return
			echo $return;
		}

		/*
		Loads Background Tile List - loadBackgroundTiles()
			@return format	- print
		*/
		public function loadBackgroundTiles() {
			// Declare Classes
			$RepMap			= new RepMap();
			$ModMap			= new ModMap();
			// Initialize variables
			$return			= false;
			$id_tiletype	= (isset($GLOBALS['params'][1])) ? trim(($GLOBALS['params'][1])) : false;
			// If values were sent
			if ($id_tiletype) {
				// Get tiles
				$tiles		= $RepMap->getAllEncounterBkgTilesByTileTypeId($id_tiletype);
				// Model tiles and prepare return
				$return		= ($tiles) ? $ModMap->listEncounterBkgTiles($tiles) : false;
			}
			// Return
			echo $return;
		}

		/*
		Loads Details Tile List - loadDetailTiles()
			@return format	- print
		*/
		public function loadDetailTiles() {
			// Declare Classes
			$RepMap			= new RepMap();
			$ModMap			= new ModMap();
			// Initialize variables
			$return			= false;
			//$id_tiletype	= (isset($GLOBALS['params'][1])) ? trim(($GLOBALS['params'][1])) : false;
			// If values were sent
			//if ($id_tiletype) {
				// Get tiles
				$tiles		= $RepMap->getAllEncounterDtlTilesByTileTypeId();
				// Model tiles and prepare return
				$return		= ($tiles) ? $ModMap->listEncounterDtlTiles($tiles) : false;
			//}
			// Return
			echo $return;
		}

		/*
		 Load Monster' list - loadMonsters()
		 	@return format	- View render
		*/
		public function loadMonsters() {
			// Declare classes
			$RepMap			= new RepMap();
			$ModMap			= new ModMap();
			// Initialize variables
			$int_level		= (isset($_POST['int_level'])) ? trim($_POST['int_level']) : false;
			$return			= false;
			// If level was sent
			if ($int_level) {
				// Get and model all monsters from the given level
				$monsters	= $RepMap->getminAllMonstersByLevel($int_level);
				$return		= $ModMap->listMonsters($monsters);
			}
			// Return
			echo $return;
		}

		/*
		 Load Monster' list - Monsters()
		 	@return format	- View render
		*/
		public function Monsters() {
			// Declare classes
			$RepMap				= new RepMap();
			$ModMap				= new ModMap();
			// Initialize variables
			$int_level			= (isset($GLOBALS['params'][1])) ? trim(($GLOBALS['params'][1])) : false;
			// If level was sent
			if ($int_level) {
				// Get and model all monsters from the given level
				$monsters		= $RepMap->getminAllMonstersByLevel($int_level);
				$monsters		= $ModMap->listMonsters($monsters);
				$level_options	= $ModMap->levelOptions($int_level);
				// Prepare return
				View::set('monsters',		$monsters);
				View::set('level_options',	$level_options);
				// Return
				View::render('partial_listMonsters');
			}
		}

		/*
		 Add Monster to map - addMonster()
		 	@return format	- View render
		*/
		public function addMonster() {
			// Declare classes
			$RepMap				= new RepMap();
			$ModMap				= new ModMap();
			// Initialize variables
			$id_monster			= (isset($_POST['id_monster'])) ? trim(($_POST['id_monster'])) : false;
			$pos				= (isset($_POST['pos'])) ? trim(($_POST['pos'])) : false;
			$id_areamap			= (isset($_POST['id_areamap'])) ? trim(($_POST['id_areamap'])) : false;
			$return				= false;
			// If level was sent
			if (($id_monster) &&  ($pos) &&  ($id_areamap)){ 
				// Add Monster to the room and prepare return
				$res			= $RepMap->addMonsterToRoom($id_areamap, $pos, $id_monster);
				if ($res) {
					$monsters	= $RepMap->getMonstersInMap($id_areamap);
					$return		= ($monsters)  ? $ModMap->mapMonsters($monsters) : false;
				}
			}
			// Return
			echo $return;
		}

		/*
		 Add Area order to encounter map - addAreaOrder()
		 	@return format	- View render
		*/
		public function addAreaOrder() {
			// Declare classes
			$RepMap			= new RepMap();
			$ModMap			= new ModMap();
			// Initialize variables
			$id_areamap		= (isset($_POST['id_areamap'])) ? trim(($_POST['id_areamap'])) : false;
			$area_tiles		= (isset($_POST['area_tiles'])) ? trim(($_POST['area_tiles'])) : false;
			$area_order		= (isset($_POST['area_order'])) ? trim(($_POST['area_order'])) : false;
			$return			= false;
			// If data was sent
			if (($id_areamap) && ($area_tiles) &&  ($area_order)) { 
				// Order Area
				$res		= $RepMap->addAreaOrder($id_areamap, $area_order, $area_tiles);
				if ($res) {
					$areas	= $RepMap->getAreasInMap($id_areamap);
					$return	= ($areas)  ? $ModMap->listAreaOrder($areas) : false;
				}
			}
			// Return
			echo $return;
		}

		/*
		 Remove Monster from tile - removeMonster()
		 	@return format	- View render
		*/
		public function removeMonster() {
			// Declare classes
			$RepMap				= new RepMap();
			$ModMap				= new ModMap();
			// Initialize variables
			$key				= (isset($_POST['key'])) ? trim(($_POST['key'])) : false;
			$id_areamap			= (isset($_POST['id_areamap'])) ? trim(($_POST['id_areamap'])) : false;
			$return				= false;
			// If info was sent
			if (($key) && ($id_areamap)) { 
				// Delete Monster to the room and prepare return
				$res			= $RepMap->deleteMonsterFromRoom($key);
				// If monster was deleted
				if ($res) {
					// Reload and model map monster list
					$monsters	= $RepMap->getMonstersInMap($id_areamap);
					$return		= ($monsters)  ? $ModMap->mapMonsters($monsters) : false;
				}
			}
			// Return
			echo $return;
		}

		/*
		 Coutnts Dungeon's treasure drop - countTotalTreasure()
		 	@return format	- print
		*/
		public function countTotalTreasure() {
			// Declare classes
			$RepMap				= new RepMap();
			$ModMap				= new ModMap();
			// Initialize variables
			$id_areamap			= (isset($_POST['id_areamap'])) ? trim(($_POST['id_areamap'])) : false;
			$return				= false;
			$tot_treasure_min	= 0;
			$tot_treasure_max	= 0;
			// If info was sent
			if ($id_areamap) { 
				$monsters		= $RepMap->getMonstersInMap($id_areamap);
				// Calculate total treasure drop
				if ($monsters) {
					$tot_monsters			= count($monsters);
					foreach ($monsters as $monster) {
						$tot_treasure_min	= $tot_treasure_min + $monster['int_treasure_min'];
						$tot_treasure_max	= $tot_treasure_max + $monster['int_treasure_max'];
					}
				}
				// Model return
				$return			= $ModMap->totals($tot_monsters, $tot_treasure_min, $tot_treasure_max);
			}
			// Return
			echo $return;
		}

		public function shrink($map = false) {
			$base_path			= VIEW_PATH."img/textures/";
			$x					= 0;
			$y					= 0;
			if ($map) {
				// Cria modelo virtual da imagem
				$final_image	= imagecreate(320, 320);
				$final_thumb	= imagecreate(32, 32);
				imagealphablending($final_image, false);
				imagesavealpha($final_image, true);
				for ($i = 1; $i <= 100; $i++) {
					$image		= $base_path.$map['vc_coord_'.sprintf('%03d', $i)];
					$extension			= substr($image, -3);
					if ($extension == 'jpg') {
						$image_model	= imagecreatefromjpeg($image);
					} else if ($extension == 'gif') {
						$image_model	= imagecreatefromgif($image);
					} else if ($extension == 'png') {
						$image_model	= imagecreatefrompng($image);
					}
					imagecopymerge($final_image, $image_model, $x, $y, 0, 0, 32, 32, 100);
					//imagefilledrectangle($final_thumb, 0, 0, 10, 10, $transparent);
					if ($x >= 288) {
						$x = 0;
						$y = $y + 32;
					} else {
						$x = $x + 32;
					}
				}
				imagecopyresampled($final_thumb, $final_image, 0, 0, 0, 0, 32, 32, 320, 320);
			}
			$filename		= General::randomString(15).'.'.$extension;
			$return			= (imagepng($final_thumb, $base_path.$filename)) ? $filename : false;
			return $return;
		}

	}