<?php
/************************************************************************************
* Name:				Item Repository													*
* File:				Application\Controller\Item.php 								*
* Author(s):		Vinas de Andrade												*
*																					*
* Description: 		This contains pre-written functions that execute Database tasks	*
*					related to login information.									*
*																					*
* Creation Date:	23/08/2013														*
* Version:			1.13.0823														*
* License:			http://www.opensource.org/licenses/bsd-license.php BSD			*
*************************************************************************************/

	namespace Application\Controller\Repository;

	use Application\Controller\Repository\dbFunctions;

	class Item {
		
		/*
		Get Combat Item by Id - getCombatById($id)
			@param integer	- Item Id
			@return format	- Mixed array
		*/
		public function getCombatById($id = false) {
			// Database Connection
			$db		= $GLOBALS['db'];
			// Query set up
			$return	= ($id) ? $db->getRow('tb_combat_item', '*', "id = '{$id}'") : false;
			// Return
			return $return;
		}

		/*
		Get Non-Combat Item by Id - getNonCombatById($id)
			@param integer	- Item Id
			@return format	- Mixed array
		*/
		public function getNonCombatById($id = false) {
			// Database Connection
			$db		= $GLOBALS['db'];
			// Query set up
			$return	= ($id) ? $db->getRow('tb_noncombat_item', '*', "id = '{$id}'") : false;
			// Return
			return $return;
		}

		/*
		Get All Combat Item Types - getAllCombatItemTypes()
			@return format	- Mixed array
		*/
		public function getAllCombatItemTypes() {
			// Database Connection
			$db		= $GLOBALS['db'];
			// Query set up
			$return	= $db->getAllRows_Arr('tb_combat_item_type', '*', "1 ORDER BY vc_name");
			// Return
			return $return;
		}

		/*
		Get All Non-Combat Item Types - getAllNonCombatItemTypes()
			@return format	- Mixed array
		*/
		public function getAllNonCombatItemTypes() {
			// Database Connection
			$db		= $GLOBALS['db'];
			// Query set up
			$return	= $db->getAllRows_Arr('tb_noncombat_item_type', '*', "1 ORDER BY vc_name");
			// Return
			return $return;
		}

		/*
		Get All Users - getAllCombatItems($max, $num_page, $ordering, $direction)
			@param integer	- Max rows
			@param integer	- Page number
			@param integer	- Ordering
			@param integer	- Ordering direction
			@return format	- Mixed array
		*/
		public function getAllCombatItems($max = 20, $num_page = 1, $ordering = 'i.id', $direction = 'ASC') {
			$dbFunctions	= new dbFunctions();
			// Database Connection
			$db				= $GLOBALS['db'];
			// Initialize variables
			$return			= false;
			// Query set up
			$table			= 'tb_combat_item AS i JOIN tb_combat_item_type AS t ON (i.id_type = t.id)';
			$select_what	= 'i.*, t.vc_name as vc_type';
			$conditions		= "1";
			$return			= $dbFunctions->getPage($select_what, $table, $conditions, $max, $num_page, $ordering, $direction);
			// Return
			return $return;
		}

		/*
		Get All Users - getAllCombatItemsByType($id)
			@param integer	- Combat type ID
			@return format	- Mixed array
		*/
		public function getAllCombatItemsByType($id = false) {
			// Database Connection
			$db		= $GLOBALS['db'];
			// Query set up
			$return	= ($id) ? $db->getAllRows_Arr('tb_combat_item', '*', "id_type = {$id}") : false;
			// Return
			return $return;
		}

		/*
		Get Searched Combat Items - getSearchedCombat($max, $num_page, $ordering, $direction)
			@param string	- Searched string
			@param integer	- Max rows
			@param integer	- Page number
			@param integer	- Ordering
			@param integer	- Ordering direction
			@return format	- Mixed array
		*/
		public function getSearchedCombat($vc_search = false, $max = 20, $num_page = 1, $ordering = 'i.id', $direction = 'ASC') {
			$dbFunctions		= new dbFunctions();
			// Database Connection
			$db					= $GLOBALS['db'];
			// Initialize variables
			$return				= false;
			if ($vc_search) {
				// Query set up
				$table			= 'tb_combat_item AS i JOIN tb_combat_item_type AS t ON (i.id_type = t.id)';
				$select_what	= 'i.*, t.vc_name as vc_type';
				$conditions		= "i.id LIKE '%{$vc_search}%' OR i.vc_name LIKE '%{$vc_search}%' OR t.vc_name LIKE '%{$vc_search}%'";
				$return			= $dbFunctions->getPage($select_what, $table, $conditions, $max, $num_page, $ordering, $direction);
			}
			// Return
			return $return;
		}

		/*
		Get All Users - getAllNonCombatItems($vc_search, $max, $num_page, $ordering, $direction)
			@param integer	- Max rows
			@param integer	- Page number
			@param integer	- Ordering
			@param integer	- Ordering direction
			@return format	- Mixed array
		*/
		public function getAllNonCombatItems($max = 20, $num_page = 1, $ordering = 'i.id', $direction = 'ASC') {
			$dbFunctions	= new dbFunctions();
			// Database Connection
			$db				= $GLOBALS['db'];
			// Initialize variables
			$return			= false;
			// Query set up
			$table			= 'tb_noncombat_item AS i JOIN tb_noncombat_item_type AS t ON (i.id_type = t.id)';
			$select_what	= 'i.*, t.vc_name as vc_type';
			$conditions		= "1";
			$return			= $dbFunctions->getPage($select_what, $table, $conditions, $max, $num_page, $ordering, $direction);
			// Return
			return $return;
		}

		/*
		Get Searched Non-Combat Items - getSearchedNonCombat($vc_search, $max, $num_page, $ordering, $direction)
			@param string	- Searched string
			@param integer	- Max rows
			@param integer	- Page number
			@param integer	- Ordering
			@param integer	- Ordering direction
			@return format	- Mixed array
		*/
		public function getSearchedNonCombat($vc_search = false, $max = 20, $num_page = 1, $ordering = 'i.id', $direction = 'ASC') {
			$dbFunctions		= new dbFunctions();
			// Database Connection
			$db					= $GLOBALS['db'];
			// Initialize variables
			$return				= false;
			if ($vc_search) {
				// Query set up
				$table			= 'tb_noncombat_item AS i JOIN tb_noncombat_item_type AS t ON (i.id_type = t.id)';
				$select_what	= 'i.*, t.vc_name as vc_type';
				$conditions		= "i.id LIKE '%{$vc_search}%' OR i.vc_name LIKE '%{$vc_search}%' OR t.vc_name LIKE '%{$vc_search}%'";
				$return			= $dbFunctions->getPage($select_what, $table, $conditions, $max, $num_page, $ordering, $direction);
			}
			// Return
			return $return;
		}

		/*
		Insert Combat Item into Database - insertCombatItem($id_field, $id_type, $int_level, $me_min, $me_max, $magic_me, $ds, $magic_ds, $time, $vc_wearable, $vc_name)
			@param integer	- Field id
			@param integer	- Type id
			@param integer	- level
			@param integer	- bonus me min
			@param integer	- bonus me max
			@param integer	- bonus magic me
			@param integer	- bonus ds
			@param integer	- bonus magic ds
			@param integer	- bonus time
			@param integer	- where to wear it
			@param string	- name
			@return boolean
		*/
		public function insertCombatItem($id_field = false, $id_type = false, $int_level = false, $me_min = false, $me_max = false, $magic_me = false, $ds = false, $magic_ds = false, $time = false, $vc_wearable = false, $vc_name = false) {
			// Initialize variables
			$return		= false;
			// Database Connection
			$db			= $GLOBALS['db'];
			// Validate sent information
			if (($id_field !== false) && ($id_type !== false) && ($int_level !== false) && ($me_min !== false) && ($me_max !== false) && ($magic_me !== false) && ($ds !== false) && ($magic_ds !== false) && ($vc_name !== false) && ($time !== false)) {
				// Save area map and prepare return (id_area)
				$info[]	= $id_field;
				$info[]	= $id_type;
				$info[]	= $int_level;
				$info[]	= $me_min;
				$info[]	= $me_max;
				$info[]	= $magic_me;
				$info[]	= $ds;
				$info[]	= $magic_ds;
				$info[]	= $time;
				$info[]	= $vc_wearable;
				$info[]	= $vc_name;
				$return	= ($db->insertRow('tb_combat_item', $info, '')) ? $db->last_id() : false;
			}
			// Return
			return $return;
		}

		/*
		Insert Non Combat Item into Database - insertNonCombatItem($id_type, $int_level, $int_bonus_start, $int_bonus_end, $vc_name)
			@param integer	- Type id
			@param integer	- level
			@param integer	- bonus start
			@param integer	- bouns end
			@param string	- name
			@return boolean
		*/
		public function insertNonCombatItem($id_type = false, $int_level = false, $int_bonus_start = false, $int_bonus_end = false, $vc_name = false) {
			// Initialize variables
			$return		= false;
			// Database Connection
			$db			= $GLOBALS['db'];
			// Validate sent information
			if (($id_type !== false) && ($int_level !== false) && ($int_bonus_start !== false) && ($int_bonus_end !== false) && ($vc_name !== false)) {
				// Save area map and prepare return (id_area)
				$info[]	= $id_type;
				$info[]	= $int_level;
				$info[]	= $int_bonus_start;
				$info[]	= $int_bonus_end;
				$info[]	= $vc_name;
				$return	= ($db->insertRow('tb_noncombat_item', $info, '')) ? $db->last_id() : false;
			}
			// Return
			return $return;
		}

		/*
		Update Combat Item into Database - updateCombatItem($id, $id_field, $id_type, $int_level, $int_bonus, $vc_name)
			@param integer	- Item id
			@param integer	- Field id
			@param integer	- Type id
			@param integer	- level
			@param integer	- bonus
			@param string	- name
			@return boolean
		*/
		public function updateCombatItem ($id = false, $id_field = false, $id_type = false, $int_level = 0, $me_min = false, $me_max = false, $magic_me = false, $ds = false, $magic_ds = false, $time = false, $vc_wearable = false, $vc_name = false) {
			// Initialize variables
			$return			= false;
			// Database Connection
			$db				= $GLOBALS['db'];
			// Validate sent information
			if (($id !== false) && ($id_field !== false) && ($id_type !== false) && ($int_level !== false) && ($me_min !== false) && ($me_max !== false) && ($magic_me !== false) && ($ds !== false) && ($magic_ds !== false) && ($vc_wearable !== false) && ($vc_name !== false) && ($time !== false)) {
				// Save area map and prepare return (id_area)
				$info[]		= $id_field;
				$info[]		= $id_type;
				$info[]		= $int_level;
				$info[]		= $me_min;
				$info[]		= $me_max;
				$info[]		= $magic_me;
				$info[]		= $ds;
				$info[]		= $magic_ds;
				$info[]		= $time;
				$info[]		= $vc_wearable;
				$info[]		= $vc_name;
				$fields[]	= 'id_field';
				$fields[]	= 'id_type';
				$fields[]	= 'int_level';
				$fields[]	= 'int_me_min';
				$fields[]	= 'int_me_max';
				$fields[]	= 'int_magic_me';
				$fields[]	= 'int_ds';
				$fields[]	= 'int_magic_ds';
				$fields[]	= 'int_time';
				$fields[]	= 'vc_wearable';
				$fields[]	= 'vc_name';
				$return		= $db->updateRow('tb_combat_item', $fields, $info, "id = {$id}");
			}
			// Return
			return $return;
		}

		/*
		Update Combat Item into Database - updateNonCombatItem($id, $id_type, $int_level, $int_bonus_start, $int_bonus_end, $vc_name)
			@param integer	- Item id
			@param integer	- Type id
			@param integer	- level
			@param integer	- bonus min
			@param integer	- bonus max
			@param string	- name
			@return boolean
		*/
		public function updateNonCombatItem($id = false, $id_type = false, $int_level = false, $int_bonus_start = false, $int_bonus_end = false, $vc_name = false) {
			// Initialize variables
			$return			= false;
			// Database Connection
			$db				= $GLOBALS['db'];
			// Validate sent information
			if (($id) && ($id_type) && ($int_level) && ($int_bonus_start) && ($int_bonus_end) && ($vc_name)) {
				// Save area map and prepare return (id_area)
				$info[]		= $id_type;
				$info[]		= $int_level;
				$info[]		= $int_bonus_start;
				$info[]		= $int_bonus_end;
				$info[]		= $vc_name;
				$fields[]	= 'id_type';
				$fields[]	= 'int_level';
				$fields[]	= 'int_bonus_start';
				$fields[]	= 'int_bonus_end';
				$fields[]	= 'vc_name';
				$return		= $db->updateRow('tb_noncombat_item', $fields, $info, "id = {$id}");
			}
			// Return
			return $return;
		}

		/*
		Delete Combat Item into Database - deleteCombatItem($id)
			@param integer	- item id
			@return boolean
		*/
		public function deleteCombatItem($id = false) {
			// Database Connection
			$db			= $GLOBALS['db'];
			// Query
			$return		= ($id) ? $db->deleteRow('tb_combat_item', "id = {$id}") : false;
			// Return
			return $return;
		}

		/*
		Delete Non-Combat Item into Database - deleteNonCombatItem($id)
			@param integer	- item id
			@return boolean
		*/
		public function deleteNonCombatItem($id = false) {
			// Database Connection
			$db			= $GLOBALS['db'];
			// Query
			$return		= ($id) ? $db->deleteRow('tb_noncombat_item', "id = {$id}") : false;
			// Return
			return $return;
		}

	}