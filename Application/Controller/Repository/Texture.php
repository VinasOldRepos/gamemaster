<?php
/************************************************************************************
* Name:				Texture Repository												*
* File:				Application\Controller\Texture.php 								*
* Author(s):		Vinas de Andrade												*
*																					*
* Description: 		This contains pre-written functions that execute Database tasks	*
*					related to login information.									*
*																					*
* Creation Date:	26/06/2013														*
* Version:			1.13.0626														*
* License:			http://www.opensource.org/licenses/bsd-license.php BSD			*
*************************************************************************************/

	namespace Application\Controller\Repository;

	use Application\Controller\Repository\dbFunctions;

	class Texture {
		
		/*
		Get Tile by Id - getTileById($id)
			@param integer	- Tile Id
			@return format	- Mixed array
		*/
		public function getTileById($id = false) {
			// Database Connection
			$db					= $GLOBALS['db'];
			// Initialize variables
			$return				= false;
			// if email was sent
			if ($id) {
				// Query set up
				$table			= 'tb_tile';
				$select_what	= '*';
				$conditions		= "id = '{$id}'";
				$return			= $db->getRow($table, $select_what, $conditions);
			}
			// Return
			return $return;
		}

		/*
		Get All Tiles - getAllLocalBkgTiles($max, $num_page, $ordering, $direction)
			@param integer	- Max rows
			@param integer	- Page number
			@param integer	- Ordering
			@param integer	- Ordering direction
			@return format	- Mixed array
		*/
		public function getAllLocalBkgTiles($max = 20, $num_page = 1, $ordering = 'b.id', $direction = 'ASC') {
			$dbFunctions	= new dbFunctions();
			// Database Connection
			$db				= $GLOBALS['db'];
			// Initialize variables
			$return			= false;
			// Query set up
			$table			= 'tb_localarea_background AS b JOIN tb_localarea_tiletype AS tt ON (b.id_localarea_tiletype = tt.id)';
			$select_what	= 'b.*, tt.vc_name as vc_tiletype';
			$conditions		= "1";
			$return			= $dbFunctions->getPage($select_what, $table, $conditions, $max, $num_page, $ordering, $direction);
			// Return
			return $return;
		}

		/*
		Get All Tiles - getAllEncounterBkgTiles($max, $num_page, $ordering, $direction)
			@param integer	- Max rows
			@param integer	- Page number
			@param integer	- Ordering
			@param integer	- Ordering direction
			@return format	- Mixed array
		*/
		public function getAllEncounterBkgTiles($max = 20, $num_page = 1, $ordering = 'b.id', $direction = 'ASC') {
			$dbFunctions	= new dbFunctions();
			// Database Connection
			$db				= $GLOBALS['db'];
			// Initialize variables
			$return			= false;
			// Query set up
			$table			= 'tb_encounter_background AS b JOIN tb_encounter_tiletype AS tt ON (b.id_encounter_tiletype = tt.id)';
			$select_what	= 'b.*, tt.vc_name as vc_tiletype';
			$conditions		= "1";
			$return			= $dbFunctions->getPage($select_what, $table, $conditions, $max, $num_page, $ordering, $direction);
			// Return
			return $return;
		}

		/*
		Get All Tiles - getAllLocalDtlTiles($max, $num_page, $ordering, $direction)
			@param integer	- Max rows
			@param integer	- Page number
			@param integer	- Ordering
			@param integer	- Ordering direction
			@return format	- Mixed array
		*/
		public function getAllLocalDtlTiles($max = 20, $num_page = 1, $ordering = 'id', $direction = 'ASC') {
			$dbFunctions	= new dbFunctions();
			// Database Connection
			$db				= $GLOBALS['db'];
			// Initialize variables
			$return			= false;
			// Query set up
			$table			= 'tb_localarea_detail';
			$select_what	= '*';
			$conditions		= "1";
			$return			= $dbFunctions->getPage($select_what, $table, $conditions, $max, $num_page, $ordering, $direction);
			// Return
			return $return;
		}

		/*
		Get All Tiles - getAllEncounterDtlTiles($max, $num_page, $ordering, $direction)
			@param integer	- Max rows
			@param integer	- Page number
			@param integer	- Ordering
			@param integer	- Ordering direction
			@return format	- Mixed array
		*/
		public function getAllEncounterDtlTiles($max = 20, $num_page = 1, $ordering = 'id', $direction = 'ASC') {
			$dbFunctions	= new dbFunctions();
			// Database Connection
			$db				= $GLOBALS['db'];
			// Initialize variables
			$return			= false;
			// Query set up
			$table			= 'tb_encounter_detail';
			$select_what	= '*';
			$conditions		= "1";
			$return			= $dbFunctions->getPage($select_what, $table, $conditions, $max, $num_page, $ordering, $direction);
			// Return
			return $return;
		}

		/*
		Get Searched Tiles - getSearchedLocalBkgTiles($vc_search, $max, $num_page, $ordering, $direction)
			@param string	- String to be searched
			@param integer	- Max rows
			@param integer	- Pager number
			@param string	- Order by
			@param string	- Ordering direction
			@return format	- Mixed array
		*/
		public function getSearchedLocalBkgTiles($vc_search = false, $max = 20, $num_page = 1, $ordering = 'c.id', $direction = 'ASC') {
			$dbFunctions		= new dbFunctions();
			// Database Connection
			$db					= $GLOBALS['db'];
			// Initialize variables
			$return				= false;
			if ($vc_search) {
				// Query set up
				$table			= 'tb_localarea_background AS b JOIN tb_localarea_tiletype AS tt ON (b.id_localarea_tiletype = tt.id)';
				$select_what	= 'b.*, tt.vc_name as vc_tiletype';
				$conditions		= "b.id LIKE '%{$vc_search}%' OR b.vc_name LIKE '%{$vc_search}%' OR b.vc_path LIKE '%{$vc_search}%' OR tt.vc_name LIKE '%{$vc_search}%'";
				$return			= $dbFunctions->getPage($select_what, $table, $conditions, $max, $num_page, $ordering, $direction);
			}
			// Return
			return $return;
		}

		/*
		Get Searched Tiles - getSearchedEncounterBkgTiles($vc_search, $max, $num_page, $ordering, $direction)
			@param string	- String to be searched
			@param integer	- Max rows
			@param integer	- Pager number
			@param string	- Order by
			@param string	- Ordering direction
			@return format	- Mixed array
		*/
		public function getSearchedEncounterBkgTiles($vc_search = false, $max = 20, $num_page = 1, $ordering = 'c.id', $direction = 'ASC') {
			$dbFunctions		= new dbFunctions();
			// Database Connection
			$db					= $GLOBALS['db'];
			// Initialize variables
			$return				= false;
			if ($vc_search) {
				// Query set up
				$table			= 'tb_encounter_background AS b JOIN tb_encounter_tiletype AS tt ON (b.id_encounter_tiletype = tt.id)';
				$select_what	= 'b.*, tt.vc_name as vc_tiletype';
				$conditions		= "b.id LIKE '%{$vc_search}%' OR b.vc_name LIKE '%{$vc_search}%' OR b.vc_path LIKE '%{$vc_search}%' OR tt.vc_name LIKE '%{$vc_search}%'";
				$return			= $dbFunctions->getPage($select_what, $table, $conditions, $max, $num_page, $ordering, $direction);
			}
			// Return
			return $return;
		}

		/*
		Get Searched Tiles - getSearchedLocalDtlTiles($vc_search, $max, $num_page, $ordering, $direction)
			@param string	- String to be searched
			@param integer	- Max rows
			@param integer	- Pager number
			@param string	- Order by
			@param string	- Ordering direction
			@return format	- Mixed array
		*/
		public function getSearchedLocalDtlTiles($vc_search = false, $max = 20, $num_page = 1, $ordering = 'c.id', $direction = 'ASC') {
			$dbFunctions		= new dbFunctions();
			// Database Connection
			$db					= $GLOBALS['db'];
			// Initialize variables
			$return				= false;
			if ($vc_search) {
				// Query set up
				$table			= 'tb_localarea_detail';
				$select_what	= '*';
				$conditions		= "id LIKE '%{$vc_search}%' OR vc_name LIKE '%{$vc_search}%' OR vc_path LIKE '%{$vc_search}%'";
				$return			= $dbFunctions->getPage($select_what, $table, $conditions, $max, $num_page, $ordering, $direction);
			}
			// Return
			return $return;
		}

		/*
		Get Searched Tiles - getSearchedEncounterDtlTiles($vc_search, $max, $num_page, $ordering, $direction)
			@param string	- String to be searched
			@param integer	- Max rows
			@param integer	- Pager number
			@param string	- Order by
			@param string	- Ordering direction
			@return format	- Mixed array
		*/
		public function getSearchedEncounterDtlTiles($vc_search = false, $max = 20, $num_page = 1, $ordering = 'c.id', $direction = 'ASC') {
			$dbFunctions		= new dbFunctions();
			// Database Connection
			$db					= $GLOBALS['db'];
			// Initialize variables
			$return				= false;
			if ($vc_search) {
				// Query set up
				$table			= 'tb_encounter_detail';
				$select_what	= '*';
				$conditions		= "id LIKE '%{$vc_search}%' OR vc_name LIKE '%{$vc_search}%' OR vc_path LIKE '%{$vc_search}%'";
				$return			= $dbFunctions->getPage($select_what, $table, $conditions, $max, $num_page, $ordering, $direction);
			}
			// Return
			return $return;
		}

		/*
		Get Texture pre-set dimentions By Texture types - getTextureDimentions($id_texturetype)
			@return format	- Mixed array
		*/
		public function getTextureDimentions($id_texturetype = false) {
			// Database Connection
			$db				= $GLOBALS['db'];
			// Initialize variables
			$return			= false;
			// Query set up
			$table			= 'tb_texturetype';
			$select_what	= 'int_width, int_height';
			$conditions		= "id = {$id_texturetype}";
			$return			= $db->getRow($table, $select_what, $conditions);
			// Return
			return $return;
		}

		/*
		Get All Texture Types - getAllTextureTypes()
			@return format	- Mixed array
		*/
		public function getAllTextureTypes() {
			// Database Connection
			$db				= $GLOBALS['db'];
			// Initialize variables
			$return			= false;
			// Query set up
			$table			= 'tb_texturetype';
			$select_what	= '*';
			$conditions		= "1 ORDER BY vc_name";
			$return			= $db->getAllRows_Arr($table, $select_what, $conditions);
			// Return
			return $return;
		}

		/*
		Get All Tile Types - getAllTileTypes()
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
		Get All Local Area Tile Types - getAllEncounterTileTypes()
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
		Insert Local Area DETAIL TILE Texture into Database - insertLocalAreaDtlTile($tile_data)
			@param array	- Mixed with user info (order like database w/ no id)
			@return boolean
		*/
		public function insertLocalAreaDtlTile($tile_data = false) {
			// Initialize variables
			$return				= false;
			// Database Connection
			$db					= $GLOBALS['db'];
			// Validate sent information
			if ($tile_data) {
				$vc_name		= (isset($tile_data[0])) ? $tile_data[0] : false;
				$vc_path		= (isset($tile_data[1])) ? $tile_data[1] : false;
				if (($vc_name) && ($vc_path)) {
					// Add Tile into Database
					$db->insertRow('tb_localarea_detail', $tile_data, '');
					$return		= true;
				}
			}
			// Return
			return $return;
		}

		/*
		Insert Local Area BACKGROUND TILE Texture into Database - insertLocalAreaTile($tile_data)
			@param array	- Mixed with user info (order like database w/ no id)
			@return boolean
		*/
		public function insertLocalAreaBkgTile($tile_data = false) {
			// Initialize variables
			$return				= false;
			// Database Connection
			$db					= $GLOBALS['db'];
			// Validate sent information
			if ($tile_data) {
				$id_tiletype	= (isset($tile_data[0])) ? $tile_data[0] : false;
				$vc_name		= (isset($tile_data[1])) ? $tile_data[1] : false;
				$vc_path		= (isset($tile_data[2])) ? $tile_data[2] : false;
				if (($id_tiletype) && ($vc_name) && ($vc_path)) {
					// Add Tile into Database
					$db->insertRow('tb_localarea_background', $tile_data, '');
					$return		= true;
				}
			}
			// Return
			return $return;
		}

		/*
		Insert Local Area DETAIL TILE Texture into Database - insertEncounterDtlTile($tile_data)
			@param array	- Mixed with user info (order like database w/ no id)
			@return boolean
		*/
		public function insertEncounterDtlTile($tile_data = false) {
			// Initialize variables
			$return				= false;
			// Database Connection
			$db					= $GLOBALS['db'];
			// Validate sent information
			if ($tile_data) {
				$vc_name		= (isset($tile_data[0])) ? $tile_data[0] : false;
				$vc_path		= (isset($tile_data[1])) ? $tile_data[1] : false;
				if (($vc_name) && ($vc_path)) {
					// Add Tile into Database
					$db->insertRow('tb_encounter_detail', $tile_data, '');
					$return		= true;
				}
			}
			// Return
			return $return;
		}

		/*
		Insert Encounter BACKGROUND TILE Texture into Database - insertEncounterTile($tile_data)
			@param array	- Mixed with user info (order like database w/ no id)
			@return boolean
		*/
		public function insertEncounterBkgTile($tile_data = false) {
			// Initialize variables
			$return				= false;
			// Database Connection
			$db					= $GLOBALS['db'];
			// Validate sent information
			if ($tile_data) {
				$id_tiletype	= (isset($tile_data[0])) ? $tile_data[0] : false;
				$vc_name		= (isset($tile_data[1])) ? $tile_data[1] : false;
				$vc_path		= (isset($tile_data[2])) ? $tile_data[2] : false;
				if (($id_tiletype) && ($vc_name) && ($vc_path)) {
					// Add Tile into Database
					$db->insertRow('tb_encounter_background', $tile_data, '');
					$return		= true;
				}
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
		public function updateTile($id_tile = false, $tile_data = false) {
			// Initialize variables
			$return				= false;
			// Database Connection
			$db					= $GLOBALS['db'];
			// Validate sent information
			if (($id_tile) && ($tile_data)) {
				$id_tiletype	= (isset($tile_data[0])) ? $tile_data[0] : false;
				$vc_name		= (isset($tile_data[1])) ? $tile_data[1] : false;
				if (($id_tiletype) && ($vc_name)) {
					$table		= 'tb_tile';
					$fields[]	= 'id_tiletype';
					$fields[]	= 'vc_name';
					$conditions	= "id = {$id_tile}";
					$return		= $db->updateRow($table, $fields, $tile_data, $conditions);
				}
			}
			return $return;
		}

		/*
		Delete Tile - deleteTile($id)
			@param integer	- Tile ID
			@return boolean
		*/
		public function deleteTile($id = false) {
			// Initialize variables
			$return			= false;
			// Database Connection
			$db				= $GLOBALS['db'];
			// If ID was sent
			if ($id) {
				// Set up query
				$table		= 'tb_tile';
				$conditions	= 'id = '.$id;
				$return		= $db->deleteRow($table, $conditions);
			}
			return $return;
		}

	}