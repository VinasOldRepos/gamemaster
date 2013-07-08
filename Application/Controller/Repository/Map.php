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
				$return			= $db->getRow($table, $conditions, $select_what);
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
				$return			= $db->getRow($table, $conditions, $select_what);
			}
			// Return
			return $return;
		}

		/*
		Get All Worlds - getAllWorlds($id)
			@return format	- Mixed array
		*/
		public function getAllWorlds() {
			// Database Connection
			$db				= $GLOBALS['db'];
			// Initialize variables
			$return			= false;
			// Query set up
			$table			= 'tb_world';
			$select_what	= '*';
			$conditions		= "1";
			$return			= $db->getAllRows_Arr($table, $select_what, $conditions);
			// Return
			return $return;
		}

		/*
		Get All Tiles By Tile Type ID - getAllTilesByTileTypeId($id)
			@param integer	- Tile type id
			@return format	- Mixed array
		*/
		public function getAllTilesByTileTypeId($id_tiletype = false) {
			// Database Connection
			$db				= $GLOBALS['db'];
			// Initialize variables
			$return			= false;
			if ($id_tiletype) {
				// Query set up
				$table			= 'tb_tile';
				$select_what	= '*';
				$conditions		= "id_tiletype = {$id_tiletype}";
				$return			= $db->getAllRows_Arr($table, $select_what, $conditions);
			}
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

	}