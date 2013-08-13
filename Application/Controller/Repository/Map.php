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
		Get All Local background Tiles By Tile Type ID - getAllLocalBkgTilesByTileTypeId($id)
			@param integer	- Tile type id
			@return format	- Mixed array
		*/
		public function getAllLocalBkgTilesByTileTypeId($id = false) {
			// Database Connection
			$db		= $GLOBALS['db'];
			// Query
			$return	= ($id) ? $db->getAllRows_Arr('tb_localarea_background', '*', "id_localarea_tiletype = {$id}") : false;
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
			$return	= ($id) ? $db->getAllRows_Arr('tb_encounter_background', '*', "id_encounter_tiletype = {$id}") : false;
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
		Insert Map into Database - insertMap($id_areatype, $vc_name, $coords)
			@param integer	- Area type id
			@param array	- map info
			@return boolean
		*/
		public function insertMap($boo_encounter = false, $id_areatype = false, $vc_name = false, $coords = false) {
			// Initialize variables
			$return					= false;
			$map_icon				= false;
			// Database Connection
			$db						= $GLOBALS['db'];
			// Validate sent information
			if (($id_areatype) && ($coords) && ($vc_name)) {
				// Prepare map data to be inserted
				$map_data[]			= $boo_encounter;
				$map_data[]			= $id_areatype;
				$map_data[]			= $vc_name;
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
		public function insertArea($id_world = false, $id_areatype = false, $id_field = false, $id_areamap = false, $level = false, $boo_open = true) {
			// Initialize variables
			$return				= false;
			// Database Connection
			$db					= $GLOBALS['db'];
			// Validate sent information
			if (($id_world) && ($id_areatype) && ($id_field) && ($id_areamap) && ($level) && ($boo_open)) {
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
		Update Tile info - updateTile($id_tile, $tile_data)
			@param integer	- Tile id
			@param array	- Mixed with tile info (order like database w/ id)
			@return boolean
		*/
		public function updateWorldMap($id_world = false, $world_pos = false, $bk_image = false) {
			// Initialize variables
			$return				= false;
			// Database Connection
			$db					= $GLOBALS['db'];
			// Validate sent information
			if (($id_world) && ($world_pos) && ($bk_image)) {
				// Get Map ID
				$id_map			= $db->getRow('tb_world', 'id_areamap', "id = {$id_world}");
				if (!empty($id_map['id_areamap'])) {
					// Update world Map and prepare return
					$return		= ($db->updateRow('tb_areamap', array('vc_coord_'.$world_pos), array($bk_image), 'id = '.$id_map['id_areamap'])) ? true : false;
				}
			}
			return $return;
		}

		/*
		Update Map in the Database - updateMap($id_areamap, $id_areatype, $coords)
			@param integer	- Area type id
			@param array	- map info
			@return boolean
		*/
		public function updateMap($id_areamap = false, $id_areatype = false, $coords = false) {
			// Initialize variables
			$return					= false;
			$map_icon				= false;
			// Database Connection
			$db						= $GLOBALS['db'];
			// Validate sent information
			if (($id_areamap) && ($id_areatype) && ($coords)) {
				// Prepare map data to be inserted
				$map_data[]			= $id_areatype;
				$fields[]			= 'id_areatype';
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
				// Save Icons
				$db->deleteRow('tb_map_link_icon', 'id_map_orign = '.$id_areamap);
				if ($map_icon) {
					foreach ($map_icon as $icon) {
						$this->addIconLink($id_areamap, 0, $icon[1], $icon[0], $vc_link = '');
					}
				}
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
		public function updateArea($id_areatype = false, $id_field = false, $id_areamap = false, $level = false, $boo_open = true) {
			// Initialize variables
			$return				= false;
			// Database Connection
			$db					= $GLOBALS['db'];
			// Validate sent information
			if (($id_areatype) && ($id_field) && ($id_areamap) && ($level) && ($boo_open)) {
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

	}