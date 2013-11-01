<?php
/************************************************************************************
* Name:				Map Repository													*
* File:				Application\Controller\Map.php 									*
* Author(s):		Vinas de Andrade												*
*																					*
* Description: 		This contains pre-written functions that execute Database tasks	*
*					related to login information.									*
*																					*
* Creation Date:	04/07/2013														*
* Version:			1.13.0606														*
* License:			http://www.opensource.org/licenses/bsd-license.php BSD			*
*************************************************************************************/

	namespace Application\Controller\Repository;

	use Application\Controller\Repository\dbFunctions;

	class Map {
		
		/*
		Get Map by Id - getById($id)
			@param integer	- Map Id
			@return format	- Mixed array
		*/
		public function getById($id = false) {
			// Database Connection
			$db					= $GLOBALS['db'];
			// Initialize variables
			$return				= false;
			// if email was sent
			if ($id) {
				// Query set up
				$table			= 'tb_map';
				$select_what	= '*';
				$conditions		= "id = '{$id}'";
				$return			= $db->getRow($table, $select_what, $conditions);
			}
			// Return
			return $return;
		}

		/*
		Get Tile by Id - getTileById($id)
			@param integer	- Tile Id
			@return format	- Mixed array
		*/
		public function getTileById($id = false) {
			// Database Connection
			$db		= $GLOBALS['db'];
			// Query set up
			$return	= ($id) ? $db->getRow('tb_tile', '*', "id = '{$id}'") : false;
			// Return
			return $return;
		}

		/*
		Get Encounter Tile by Id - getEncounterBkgTileById($id)
			@param integer	- Tile Id
			@return format	- Mixed array
		*/
		public function getEncounterBkgTileById($id = false) {
			// Database Connection
			$db		= $GLOBALS['db'];
			// Query set up
			$return	= ($id) ? $db->getRow('tb_encounter_background', '*', "id = '{$id}'") : false;
			// Return
			return $return;
		}

		/*
		Get Local Area Tile by Id - getLocalBkgTileById($id)
			@param integer	- Tile Id
			@return format	- Mixed array
		*/
		public function getLocalBkgTileById($id = false) {
			// Database Connection
			$db		= $GLOBALS['db'];
			// Query set up
			$return	= ($id) ? $db->getRow('tb_localarea_background', '*', "id = '{$id}'") : false;
			// Return
			return $return;
		}


		/*
		Get Encounter Detail Tile by Id - getEncounterDtlTileById($id)
			@param integer	- Tile Id
			@return format	- Mixed array
		*/
		public function getEncounterDtlTileById($id = false) {
			// Database Connection
			$db		= $GLOBALS['db'];
			// Query set up
			$return	= ($id) ? $db->getRow('tb_encounter_detail', '*', "id = '{$id}'") : false;
			// Return
			return $return;
		}

		/*
		Get Loal area Detail Tile by Id - getLocalDtlTileById($id)
			@param integer	- Tile Id
			@return format	- Mixed array
		*/
		public function getLocalDtlTileById($id = false) {
			// Database Connection
			$db		= $GLOBALS['db'];
			// Query set up
			$return	= ($id) ? $db->getRow('tb_localarea_detail', '*', "id = '{$id}'") : false;
			// Return
			return $return;
		}


		/*
		Get Area by Id - getAreaById($id)
			@param integer	- Area Id
			@return format	- Mixed array
		*/
		public function getAreaById($id = false) {
			// Database Connection
			$db					= $GLOBALS['db'];
			// Initialize variables
			$return				= false;
			// if email was sent
			if ($id) {
				// Query set up
				$table			= 'tb_area';
				$select_what	= '*';
				$conditions		= "id = '{$id}'";
				$return			= $db->getRow($table, $select_what, $conditions);
			}
			// Return
			return $return;
		}

		/*
		Get map info by Id - getMapInfoById($id)
			@param integer	- Area Id
			@return format	- Mixed array
		*/
		public function getMapInfoById($id = false) {
			// Database Connection
			$db		= $GLOBALS['db'];
			// Query set up
			$return	= ($id) ? $db->getRow('tb_areamap', 'id, boo_encounter, id_areatype, vc_name', "id = '{$id}'") : false;
			// Return
			return $return;
		}

		/*
		Get Area by Area Map Id - getAreaByAreaMapId($id)
			@param integer	- Area Map Id
			@return format	- Mixed array
		*/
		public function getAreaByAreaMapId($id = false) {
			// Database Connection
			$db					= $GLOBALS['db'];
			// Initialize variables
			$return				= false;
			// if email was sent
			if ($id) {
				// Query set up
				$table			= 'tb_area';
				$select_what	= '*';
				$conditions		= "id_areamap = '{$id}'";
				$return			= $db->getRow($table, $select_what, $conditions);
			}
			// Return
			return $return;
		}

		/*
		Get World Map by Id - getWorldMapById($id)
			@param integer	- World Id
			@return format	- Mixed array
		*/
		public function getWorldMapById($id = false) {
			// Database Connection
			$db					= $GLOBALS['db'];
			// Initialize variables
			$return				= false;
			// if email was sent
			if ($id) {
				// Query set up
				$table			= 'tb_world AS w JOIN tb_areamap AS am ON w.id_areamap = am.id';
				$select_what	= 'w.id AS id_world, am.*';
				$conditions		= "w.id = '{$id}'";
				$return			= $db->getRow($table, $select_what, $conditions);
			}
			// Return
			return $return;
		}

		/*
		Get links and icons by id - getLinksIconsByAreaId($id)
			@param integer	- Area Id
			@return format	- Mixed array
		*/
		public function getLinksIconsByAreaId($id = false) {
			// Database Connection
			$db		= $GLOBALS['db'];
			// Query
			$return	= ($id) ? $db->getAllRows_Arr('tb_map_link_icon AS mli LEFT JOIN tb_icon AS i ON mli.id_icon = i.id', 'mli.*, i.vc_path', "id_map_orign = {$id}") : false;
			// Return
			return $return;
		}

		/*
		Get parent map id and type by map id- getParentMapIdTypeByMapId($id)
			@param integer	- Area Id
			@return format	- Mixed array
		*/
		public function getParentMapIdTypeByMapId($id = false) {
			// Database Connection
			$db		= $GLOBALS['db'];
			// Query
			$return	= ($id) ? $db->getRow('tb_map_link_icon AS mli JOIN tb_areamap AS am ON mli.id_map_orign = am.id', 'mli.id_map_orign, am.boo_encounter, am.id_areatype', "id_map_target = {$id}") : false;
			// Return
			return $return;
		}

		/*
		Getlocal map's world position - getWorldPosition($parent_id_areamap, $id_areamap)
			@param integer	- Parent Area Id
			@param integer	- Area Id
			@return format	- Mixed array
		*/
		public function getWorldPosition($parent_id_areamap = false, $id_areamap = false) {
			$db		= $GLOBALS['db'];
			$return	= false;
			if (($parent_id_areamap) && ($id_areamap)) {
				$return = ($link = $db->getRow('tb_map_link_icon', 'int_pos', "id_map_orign = {$parent_id_areamap} AND id_map_target = {$id_areamap}")) ? $link['int_pos'] : false;
			}
			return $return;
		}

		/*
		Get All Worlds - getAllWorlds()
			@return format	- Mixed array
		*/
		public function getAllWorlds() {
			// Database Connection
			$db		= $GLOBALS['db'];
			// Query
			$return	= $db->getAllRows_Arr('tb_world', '*', "1");
			// Return
			return $return;
		}

		/*
		Get All World Maps - getAllWorldMaps()
			@return format	- Mixed array
		*/
		public function getAllWorldMaps() {
			// Database Connection
			$db		= $GLOBALS['db'];
			// Query
			$return	= $db->getAllRows_Arr('tb_areamap', 'id, vc_name', "id_areatype = 4");
			// Return
			return $return;
		}

		/*
		Get All Local background Tiles By Tile Type ID - getAllLocalBkgTilesByTileTypeId($id)
			@param integer	- Tile type id
			@return format	- Mixed array
		*/
		public function getAllLocalBkgTilesByTileTypeId($id = false) {
			// Database Connection
			$db		= $GLOBALS['db'];
			// Query
			$return	= ($id) ? $db->getAllRows_Arr('tb_localarea_background', '*', "id_localarea_tiletype = {$id} ORDER BY vc_name") : false;
			// Return
			return $return;
		}

		/*
		Get All Encounter background Tiles By Tile Type ID - getAllEncounterBkgTilesByTileTypeId($id)
			@param integer	- Tile type id
			@return format	- Mixed array
		*/
		public function getAllEncounterBkgTilesByTileTypeId($id = false) {
			// Database Connection
			$db		= $GLOBALS['db'];
			// Query
			$return	= ($id) ? $db->getAllRows_Arr('tb_encounter_background', '*', "id_encounter_tiletype = {$id} ORDER BY vc_name") : false;
			// Return
			return $return;
		}

		/*
		Get All Encounter detail Tiles - getAllEncounterDtlTilesByTileTypeId()
			@return format	- Mixed array
		*/
		public function getAllEncounterDtlTilesByTileTypeId() {
			// Database Connection
			$db		= $GLOBALS['db'];
			// Query
			$return	= $db->getAllRows_Arr('tb_encounter_detail', '*', "1");
			// Return
			return $return;
		}

		/*
		Get All Leval Area detail Tiles - getAllLocalDtlTilesByTileTypeId()
			@return format	- Mixed array
		*/
		public function getAllLocalDtlTilesByTileTypeId() {
			// Database Connection
			$db		= $GLOBALS['db'];
			// Query
			$return	= $db->getAllRows_Arr('tb_localarea_detail', '*', "1");
			// Return
			return $return;
		}

		/*
		Get All AreaTypes - getAllAreaTypes($id)
			@return format	- Mixed array
		*/
		public function getAllAreaTypes() {
			// Database Connection
			$db				= $GLOBALS['db'];
			// Initialize variables
			$return			= false;
			// Query set up
			$table			= 'tb_areatype';
			$select_what	= '*';
			$conditions		= "1 ORDER BY vc_name";
			$return			= $db->getAllRows_Arr($table, $select_what, $conditions);
			// Return
			return $return;
		}

		/*
		Get All Tile Types - getAllTileTypes($id)
			@return format	- Mixed array
		*/
		public function getAllTileTypes() {
			// Database Connection
			$db				= $GLOBALS['db'];
			// Initialize variables
			$return			= false;
			// Query set up
			$table			= 'tb_tiletype';
			$select_what	= '*';
			$conditions		= "1 ORDER BY vc_name";
			$return			= $db->getAllRows_Arr($table, $select_what, $conditions);
			// Return
			return $return;
		}

		/*
		Get All Maps - getAllMaps($max, $num_page, $ordering, $direction)
			@param integer	- Max rows
			@param integer	- Page number
			@param integer	- Ordering
			@param integer	- Ordering direction
			@return format	- Mixed array
		*/
		public function getAllMaps($max = 20, $num_page = 1, $ordering = 'id', $direction = 'ASC') {
			$dbFunctions	= new dbFunctions();
			// Database Connection
			$db				= $GLOBALS['db'];
			// Initialize variables
			$return			= false;
			// Query set up
			$table			= 'tb_map';
			$select_what	= '*';
			$conditions		= "1";
			$return			= $dbFunctions->getPage($select_what, $table, $conditions, $max, $num_page, $ordering, $direction);
			// Return
			return $return;
		}

		/*
		Get All Child Maps - getAllChildMaps($id)
			@param integer	- ID area Map
			@return format	- Mixed array
		*/
		public function getAllChildMaps($id = false) {
			// Database Connection
			$db				= $GLOBALS['db'];
			// Query 
			$return			= ($id) ? $db->getAllRows_Arr('tb_map_link_icon', 'id_map_target AS id', 'id_map_orign = '.$id) : false;
			// Return
			return $return;
		}

		/*
		Get All Icons - getAllIcons(
			@return format	- Mixed array
		*/
		public function getAllIcons() {
			// Database Connection
			$db				= $GLOBALS['db'];
			// Initialize variables
			$return			= false;
			// Query set up
			$table			= 'tb_icon';
			$select_what	= '*';
			$conditions		= "1 ORDER BY vc_name";
			$return			= $db->getAllRows_Arr($table, $select_what, $conditions);
			// Return
			return $return;
		}

		/*
		Get navigation Links By Area Id - getNavigationLinkByAreaId($id)
			@param integer	- Area ID
			@return format	- Mixed array
		*/
		public function getNavigationLinkByAreaId($id = false) {
			// Database Connection
			$db				= $GLOBALS['db'];
			// Query set up
			$return			= ($id) ? $db->getAllRows_Arr('tb_world_navigation', '*', 'id_map_orign = '.$id) : false;
			// Return
			return $return;
		}

		/*
		Get All Monsters By Level - getminAllMonstersByLevel($int_level)
			@param integer	- Level
			@return format	- Mixed array
		*/
		public function getminAllMonstersByLevel($int_level = false) {
			// Database Connection
			$db				= $GLOBALS['db'];
			// Query set up
			$return			= ($int_level) ? $db->getAllRows_Arr('tb_monster', '*', 'int_level = '.$int_level.' ORDER BY vc_name') : false;
			// Return
			return $return;
		}

		/*
		Get All Local Area Tile Types - getAllLocalAreaTileTypes()
			@return format	- Mixed array
		*/
		public function getAllLocalAreaTileTypes() {
			// Database Connection
			$db				= $GLOBALS['db'];
			// Initialize variables
			$return			= false;
			// Query set up
			$table			= 'tb_localarea_tiletype';
			$select_what	= '*';
			$conditions		= "1 ORDER BY vc_name";
			$return			= $db->getAllRows_Arr($table, $select_what, $conditions);
			// Return
			return $return;
		}

		/*
		Get All Encounter Tile Types - getAllEncounterTileTypes()
			@return format	- Mixed array
		*/
		public function getAllEncounterTileTypes() {
			// Database Connection
			$db				= $GLOBALS['db'];
			// Initialize variables
			$return			= false;
			// Query set up
			$table			= 'tb_encounter_tiletype';
			$select_what	= '*';
			$conditions		= "1 ORDER BY vc_name";
			$return			= $db->getAllRows_Arr($table, $select_what, $conditions);
			// Return
			return $return;
		}

		/*
		Get All Encounter Bkg Tiles - getAllEncounterBkgTiles()
			@return format	- Mixed array
		*/
		public function getAllEncounterBkgTilesByTTileId($id = false) {
			// Database Connection
			$db		= $GLOBALS['db'];
			// Initialize variables
			$return	= false;
			// Query set up
			$return	= ($id) ? $db->getAllRows_Arr('tb_encounter_background', '*', "id_tiletype = '{$id}'") : false;
			// Return
			return $return;
		}

		/*
		Get All Monsters in the Map - getMonstersInMap($id_areamap)
			@return format	- Mixed array
		*/
		public function getMonstersInMap($id = false) {
			// Database Connection
			$db		= $GLOBALS['db'];
			// Initialize variables
			$return	= false;
			// Query set up
			$return	= ($id) ? $db->getAllRows_Arr('tb_area_pos_monster AS p JOIN tb_monster AS m ON p.id_monster = m.id', 'p.*, m.vc_name, m.int_level, m.int_treasure_min, m.int_treasure_max', "id_areamap = '{$id}'") : false;
			// Return
			return $return;
		}

		/*
		Get All areas in the Map - getAreasInMap($id_areamap)
			@return format	- Mixed array
		*/
		public function getAreasInMap($id = false) {
			// Database Connection
			$db		= $GLOBALS['db'];
			// Initialize variables
			$return	= false;
			// Query set up
			$return	= ($id) ? $db->getAllRows_Arr('tb_encounter_areaorder', '*', "id_areamap = '{$id}' ORDER BY int_order") : false;
			// Return
			return $return;
		}

		/*
		Get Map Ids byt Parent map id - getMapIdsByParentMapId($id)
			@param integer	- Areamap Id
			@return format	- Mixed array
		*/
		public function getMapIdsByParentMapId($id = false) {
			// Database Connection
			$db				= $GLOBALS['db'];
			// Query set up
			$return			= ($id) ? $db->getAllRows_Arr('tb_map_link_icon', 'id_map_target AS id', 'id_map_orign = '.$id) : false;
			// Return
			return $return;
		}

		/*
		Get World Map by World Id - getWorldMapId($id)
			@param integer	- World Id
			@return format	- Mixed array
		*/
		public function getWorldMapId($id = false) {
			// Database Connection
			$db					= $GLOBALS['db'];
			// Initialize variables
			$return				= false;
			// if email was sent
			if ($id) {
				// Query set up
				$table			= 'tb_world';
				$select_what	= 'id_areamap';
				$conditions		= "id = '{$id}'";
				$return			= ($res = $db->getRow($table, $select_what, $conditions)) ? $res['id_areamap'] : false;
			}
			// Return
			return $return;
		}

		/*
		Get Map by Id - getMapById($id)
			@param integer	- World Id
			@return format	- Mixed array
		*/
		public function getMapById($id = false) {
			// Database Connection
			$db					= $GLOBALS['db'];
			// Initialize variables
			$return				= false;
			// Query set up
			$return			= ($id) ? $return = $db->getRow('tb_areamap', '*', "id = '{$id}'") : false;
			// Return
			return $return;
		}

		/*
		Get Position On Parent Map by Map Id - getPositionOnParentMap($id)
			@param integer	- Parent map Id
			@param integer	- Target map Id
			@return format	- Mixed array
		*/
		public function getPositionOnParentMap($parent_id = false, $id = false) {
			// Database Connection
			$db					= $GLOBALS['db'];
			// Query set up
			$pos			= ($id) ? $return = $db->getRow('tb_map_link_icon', 'int_pos', 'id_map_orign = '.$parent_id.' AND id_map_target = '.$id) : false;
			$return			= ($pos) ? $pos['int_pos'] : false;
			// Return
			return $return;
		}

		/*
		Insert Map into Database - insertMap(boo_encounter, $id_course, $id_areatype, $vc_name, $vc_mouseover, $coords)
			@param boolean	- is it an encounter area
			@param integer	- Area type id
			@param integer	- Course id
			@param string	- Area's name
			@param string	- Mouseover text
			@param array	- map tiles
			@return boolean
		*/
		public function insertMap($boo_encounter = false, $id_course = 0, $id_areatype = false, $vc_name = false, $vc_mouseover = false, $coords = false) {
			// Initialize variables
			$return					= false;
			$map_icon				= false;
			// Database Connection
			$db						= $GLOBALS['db'];
			// Validate sent information
			if (($id_areatype) && ($coords) && ($vc_name) && ($vc_mouseover)) {
				// Prepare map data to be inserted
				$map_data[]			= $boo_encounter;
				$map_data[]			= $id_course;
				$map_data[]			= $id_areatype;
				$map_data[]			= $vc_name;
				$map_data[]			= $vc_mouseover;
				for ($i = 0; $i < 100; $i++) {
					$map_data[]		= $coords[$i][0];
					// Gather icon info (position and icon)
					if (!empty($coords[$i][1])) {
						$map_icon[]	= array(sprintf('%03d', $i+1), $coords[$i][1]);
					}
				}
				// Save area map and prepare return (id_areamap)
				$return				= ($db->insertRow('tb_areamap', $map_data, '')) ? $db->last_id() : false;
				// Save Icons
				if ($map_icon) {
					foreach ($map_icon as $icon) {
						$this->addIconLink($return, 0, $icon[1], $icon[0], $vc_link = '');
					}
				}
			}
			// Return
			return $return;
		}

		/*
		Insert Area into Database - insertArea ($id_world, $id_areatype, $id_field, $id_areamap, $level, $boo_open)
			@param integer	- Area type id
			@param integer	- Field id
			@param integer	- Area Map id
			@param integer	- level
			@param integer	- status
			@return boolean
		*/
		public function insertArea($id_world = false, $id_areatype = false, $id_field = 0, $id_areamap = false, $level = 0, $boo_open = true) {
			// Initialize variables
			$return				= false;
			// Database Connection
			$db					= $GLOBALS['db'];
			// Validate sent information
			if (($id_world !== false) && ($id_areatype !== false) && ($id_field !== false) && ($id_areamap !== false) && ($level !== false) && ($boo_open !== false)) {
				// Save area map and prepare return (id_area)
				$area_info[]	= $id_world;
				$area_info[]	= $id_areatype;
				$area_info[]	= $id_field;
				$area_info[]	= $id_areamap;
				$area_info[]	= $level;
				$area_info[]	= $boo_open;
				$return			= ($db->insertRow('tb_area', $area_info, '')) ? $db->last_id() : false;
			}
			// Return
			return $return;
		}

		/*
		Insert Area into Database - addMonsterToRoom ($id_areamap, $pos, $id_monster)
			@param integer	- Area type id
			@param integer	- Field id
			@param integer	- Area Map id
			@param integer	- level
			@param integer	- status
			@return boolean
		*/
		public function addMonsterToRoom($id_areamap = false, $pos = false, $id_monster = false) {
			// Initialize variables
			$return		= false;
			// Database Connection
			$db			= $GLOBALS['db'];
			// Validate sent information
			if (($id_areamap) && ($pos) && ($id_monster)) {
				// Save area map and prepare return (id_area)
				$info[]	= $id_areamap;
				$info[]	= $pos;
				$info[]	= $id_monster;
				$return	= ($db->insertRow('tb_area_pos_monster', $info, '')) ? $db->last_id() : false;
			}
			// Return
			return $return;
		}

		/*
		Add Area Order info - addAreaOrder ($id_areamap, $area_order, $id_monster)
			@param integer	- Area map id
			@param integer	- Area order
			@param string	- Area tiles
			@return boolean
		*/
		public function addAreaOrder($id_areamap = false, $area_order = false, $area_tiles = false) {
			// Initialize variables
			$return			= false;
			// Database Connection
			$db				= $GLOBALS['db'];
			// Validate sent information
			if (($id_areamap) && ($area_order) && ($area_tiles)) {
				// If there are someone at this position
				$position	= $db->getAllRows_Arr('tb_encounter_areaorder', 'id', "id_areamap = {$id_areamap} AND int_order >= {$area_order}");
				if ($position) {
					// Delete everyone
					foreach ($position as $pos) {
						$this->deleteAreaOrder($pos['id']);
					}
				}
				// Save area map and prepare return (id_area)
				$info[]		= $id_areamap;
				$info[]		= $area_order;
				$info[]		= $area_tiles;
				$return		= ($db->insertRow('tb_encounter_areaorder', $info, '')) ? $db->last_id() : false;
			}
			// Return
			return $return;
		}

		/*
		Update Tile info - updateTile($id_areamap, $world_pos, $bk_image)
			@param integer	- Area map id
			@param string	- position
			@param string	- Imagem de fundo
			@param array	- Mixed with tile info (order like database w/ id)
			@return boolean
		*/
		public function updateWorldMap($id_areamap = false, $world_pos = false, $bk_image = false) {
			// Initialize variables
			$return				= false;
			// Database Connection
			$db					= $GLOBALS['db'];
			// Validate sent information
			if (($id_areamap) && ($world_pos) && ($bk_image)) {
				// Update world Map and prepare return
				$return		= ($db->updateRow('tb_areamap', array('vc_coord_'.$world_pos), array($bk_image), 'id = '.$id_areamap)) ? true : false;
			}
			return $return;
		}

		/*
		Update Map in the Database - updateMap($id_areamap, $id_course, $vc_name, $vc_mouseover, $id_areatype, $coords)
			@param integer	- Area type id
			@param integer	- Course id
			@param integer	- Mouseover text
			@param integer	- Area type id
			@param integer	- Map coords
			@return boolean
		*/
		public function updateMap($id_areamap = false, $id_course = 0, $vc_name = false, $vc_mouseover = false, $id_areatype = false, $coords = false) {
			// Initialize variables
			$return					= false;
			$map_icon				= false;
			// Database Connection
			$db						= $GLOBALS['db'];
			// Validate sent information
			if (($id_areamap) && ($id_areatype) && ($coords) && ($vc_mouseover)) {
				// Prepare map data to be inserted
				$map_data[]			= $id_areatype;
				$map_data[]			= $id_course;
				if ($vc_name) {
					$map_data[]		= $vc_name;
				}
				$map_data[]			= $vc_mouseover;
				$fields[]			= 'id_areatype';
				$fields[]			= 'id_course';
				if ($vc_name) {
					$fields[]		= 'vc_name';
				}
				$fields[]			= 'vc_mouseover';
				for ($i = 0; $i < 100; $i++) {
					$map_data[]		= $coords[$i][0];
					$fields[]		= 'vc_coord_'.sprintf('%03d', $i+1);
					// Gather icon info (position and icon)
					if (!empty($coords[$i][1])) {
						$map_icon[]	= array(sprintf('%03d', $i+1), $coords[$i][1]);
					}
				}
				// Save area map and prepare return (id_areamap)
				$return				= ($db->updateRow('tb_areamap', $fields, $map_data, 'id = '.$id_areamap)) ? $id_areamap : false;
				// Get linking info
				$links				= $this->getLinksIconsByAreaId($id_areamap);
				// Save Icons and links
				if ($map_icon) {
					$db->deleteRow('tb_map_link_icon', 'id_map_orign = '.$id_areamap);
					// Oganize links
					foreach ($map_icon as $icon) {
						$target			= '0';
						// Take links into accout on the query below
						foreach ($links as $link) {
							if ($link['int_pos'] == intval($icon[0])) {
								$target	= $link['id_map_target'];
								break;
							}
						}
						$this->addIconLink($id_areamap, $target, $icon[1], $icon[0], $vc_link = '');
					}
				}
			}
			// Return
			return $return;
		}

		/*
		Update Map Name in the Database - updateMapName($id_areamap, $vc_name)
			@param integer	- Area type id
			@param array	- map info
			@return boolean
		*/
		public function updateMapName($id_areamap = false, $vc_name = false) {
			// Initialize variables
			$return			= false;
			$map_icon		= false;
			// Database Connection
			$db				= $GLOBALS['db'];
			// Validate sent information
			if (($id_areamap) && ($vc_name)) {
				// Prepare map data to be inserted
				$map_data[]	= $vc_name;
				$fields[]	= 'vc_name';
				// Save area map and prepare return (id_areamap)
				$return		= ($db->updateRow('tb_areamap', $fields, $map_data, 'id = '.$id_areamap)) ? $id_areamap : false;
			}
			// Return
			return $return;
		}

		/*
		Update Area in the Database - updateArea($id_areatype, $id_field, $id_areamap, $level, $boo_open)
			@param integer	- Area type id
			@param integer	- Field id
			@param integer	- Area Map id
			@param integer	- level
			@param integer	- status
			@return boolean
		*/
		public function updateArea($id_areatype = false, $id_field = 0, $id_areamap = false, $level = 0, $boo_open = true) {
			// Initialize variables
			$return				= false;
			// Database Connection
			$db					= $GLOBALS['db'];
			// Validate sent information
			if (($id_areatype !== false) && ($id_field !== false) && ($id_areamap !== false) && ($level !== false) && ($boo_open !== false)) {
				// Save area map and prepare return (id_area)
				$area_info[]	= $id_areatype;
				$area_info[]	= $id_field;
				$area_info[]	= $level;
				$area_info[]	= $boo_open;
				$fields[]		= 'id_areatype';
				$fields[]		= 'id_field';
				$fields[]		= 'int_level';
				$fields[]		= 'boo_open';
				$return			= $db->updateRow('tb_area', $fields, $area_info, 'id_areamap = '.$id_areamap);
			}
			// Return
			return $return;
		}

		/*
		Update Target Map id - updateTargetMapId($id, $id_map_target)
			@param integer	- tb_map_link_icon.id
			@param integer	- Target Map Id
			@param integer	- status
			@return boolean
		*/
		public function updateTargetMapId($id = false, $id_map_target = false) {
			// Initialize variables
			$return				= false;
			// Database Connection
			$db					= $GLOBALS['db'];
			// Validate sent information
			if (($id) && ($id_map_target)) {
				// Save area map and prepare return (id_area)
				$area_info[]	= $id_map_target;
				$fields[]		= 'id_map_target';
				$return			= $db->updateRow('tb_map_link_icon', $fields, $area_info, 'id = '.$id);
			}
			// Return
			return $return;
		}

		/*
		Delete user - deleteAllMapInfoByMapId($id)
			@param array	- Mixed with user info (order like database w/ id)
			@return boolean
		*/
		public function deleteAllMapInfoByMapId($id = false) {
			// Initialize variables
			$return			= false;
			// Database Connection
			$db				= $GLOBALS['db'];
			if (is_array($id)) {
				foreach ($id as $ids) {
					// Delete All relates info
					$db->deleteRow('tb_map_link_icon', 'id_map_orign = '.$ids['id']);
					$db->deleteRow('tb_map_link_icon', 'id_map_target = '.$ids['id']);
					$db->deleteRow('tb_area', 'id_areamap = '.$ids['id']);
					$return		= $db->deleteRow('tb_areamap', 'id = '.$ids['id']);
				}
			} else {
				// Delete All relates info
				$db->deleteRow('tb_map_link_icon', 'id_map_orign = '.$id);
				$db->deleteRow('tb_map_link_icon', 'id_map_target = '.$id);
				$db->deleteRow('tb_area', 'id_areamap = '.$id);
				$return		= $db->deleteRow('tb_areamap', 'id = '.$id);
			}
			return $return;
		}

		/*
		Delete monster from tile - deleteMonsterFromRoom($id)
			@param array	- Mixed with user info (order like database w/ id)
			@return boolean
		*/
		public function deleteMonsterFromRoom($id) {
			$db		= $GLOBALS['db'];
			$return	= ($id) ? $db->deleteRow('tb_area_pos_monster', 'id = '.$id) : false;
			return $return;
		}

		/*
		Delete area order - deleteAreaOrder($id)
			@param integer	- area order id
			@return boolean
		*/
		public function deleteAreaOrder($id) {
			$db		= $GLOBALS['db'];
			$return	= ($id) ? $db->deleteRow('tb_encounter_areaorder', 'id = '.$id) : false;
			return $return;
		}

		/*
		Delete Tile info - deleteTileInfo($id_areamap, $pos)
			@param integer	- area mapid
			@param integer	- position
			@return boolean
		*/
		public function deleteTileInfo($id_areamap = false, $pos = false) {
			$db		= $GLOBALS['db'];
			$return	= (($id_areamap) && ($pos)) ? $db->deleteRow('tb_map_link_icon', 'id_map_orign = '.$id_areamap.' AND int_pos = '.$pos) : false;
			return $return;
		}

		/*
		Delete user - eraseWorldTileByMapId($id, $pos)
			@param array	- Mixed with user info (order like database w/ id)
			@return boolean
		*/
		public function eraseWorldTileByMapId($id = false, $pos = false) {
			// Initialize variables
			$return			= false;
			// Database Connection
			$db				= $GLOBALS['db'];
			if (($id) && ($pos)) {
				$area_info[]	= '0';
				$fields[]		= 'vc_coord_'.$pos;
				$return			= $db->updateRow('tb_areamap', $fields, $area_info, 'id = '.$id);
			}
			return $return;
		}

		/*
		Icon an link into into Database - addIconLink($id_map_orign, $id_map_target, $id_icon, $pos, vc_link)
			@return boolean
		*/
		public function addIconLink($id_map_orign = false, $id_map_target = false, $id_icon = false, $pos = false, $vc_link = '') {
			// Initialize variables
			$return			= false;
			$map_icon		= false;
			// Database Connection
			$db				= $GLOBALS['db'];
			// Validate sent information
			if (($id_map_orign) && ($pos) && (($id_icon) || ($id_map_target))) {
				// Check if there's something on the given position
				$res		= $db->getRow('tb_map_link_icon', 'id', 'id_map_orign = '.$id_map_orign.' AND int_pos = '.$pos);
				// If position is occupied
				if ($res) {
					// Update position
					if ($id_icon) {
						$fields[]	= 'id_icon';
						$values[]	= $id_icon;
					}
					if ($id_map_target) {
						$fields[]	= 'id_map_target';
						$values[]	= $id_map_target;
					}
					$return	= $db->updateRow('tb_map_link_icon', $fields, $values, 'id = '.$res['id']);
				// If position is vacant
				} else {
					// Insert position data
					$return	= $db->insertRow('tb_map_link_icon', array($id_map_orign, $id_map_target, $id_icon, $pos, $vc_link));
				}
			}
			// Return
			return $return;
		}

		/*
		  Erase all World related info - resetWorld()
			@return boolean
		*/
		public function resetWorld() {
			// Database Connection
			$db		= $GLOBALS['db'];
			$return	= $db->truncateTable('tb_areamap');
			$return	= $db->truncateTable('tb_area');
			$return	= $db->truncateTable('tb_area_pos_monster');
			$return	= $db->truncateTable('tb_map_link_icon');
			$return	= $db->truncateTable('tb_world_navigation');
			return $return;
		}

		/*
		  Erase all Texture related info - resetTextures()
			@return boolean
		*/
		public function resetTextures() {
			// Database Connection
			$db		= $GLOBALS['db'];
			$return	= $db->truncateTable('tb_icon');
			$return	= $db->truncateTable('tb_encounter_background');
			$return	= $db->truncateTable('tb_encounter_detail');
			$return	= $db->truncateTable('tb_localarea_background');
			$return	= $db->truncateTable('tb_localarea_detail');
			return $return;
		}

		public function addWorldMapLink($id_map_orign = false, $id_map_target = false, $direction = false) {
			// Initialize variables
			$return			= false;
			$map_icon		= false;
			// Database Connection
			$db				= $GLOBALS['db'];
			// Validate sent information
			if (($id_map_orign) && ($id_map_target) && ($direction)) {
				// Insert position data
				$return	= $db->insertRow('tb_world_navigation', array($id_map_orign, $id_map_target, $direction));
			}
			// Return
			return $return;
		}

	}