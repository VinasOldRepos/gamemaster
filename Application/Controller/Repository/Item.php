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
		Get All Users - getAllNonCombatItems($max, $num_page, $ordering, $direction)
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
		Insert Combat Item into Database - insertCombatItem($id_field, $id_type, $int_level, $int_bonus, $vc_name)
			@param integer	- Field id
			@param integer	- Type id
			@param integer	- level
			@param integer	- bonus
			@param string	- name
			@return boolean
		*/
		public function insertCombatItem($id_field = false, $id_type = false, $int_level = false, $int_bonus = false, $vc_name = false) {
			// Initialize variables
			$return		= false;
			// Database Connection
			$db			= $GLOBALS['db'];
			// Validate sent information
			if (($id_field) && ($id_type) && ($int_level) && ($int_bonus) && ($vc_name)) {
				// Save area map and prepare return (id_area)
				$info[]	= $id_field;
				$info[]	= $id_type;
				$info[]	= $int_level;
				$info[]	= $int_bonus;
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
			if (($id_type) && ($int_level) && ($int_bonus_start) && ($int_bonus_end) && ($vc_name)) {
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

	}