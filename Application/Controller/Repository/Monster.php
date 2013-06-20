<?php
/************************************************************************************
* Name:				Monster Repository												*
* File:				Application\Controller\Monster.php 								*
* Author(s):		Vinas de Andrade												*
*																					*
* Description: 		This contains pre-written functions that execute Database tasks	*
*					related to login information.									*
*																					*
* Creation Date:	06/06/2013														*
* Version:			1.13.0606														*
* License:			http://www.opensource.org/licenses/bsd-license.php BSD			*
*************************************************************************************/

	namespace Application\Controller\Repository;

	use Application\Controller\Repository\dbFunctions;

	class Monster {
		
		/*
		Get User by Id - getById($id)
			@param integer	- User Id
			@return format	- Mixed array
		*/
		public function getById($id = false) {
			// Database Connection
			$db					= $GLOBALS['db'];
			// Initialize variables
			$return				= false;
			// if email was sent
			if ($id !== false) {
				// Query set up
				$table			= 'tb_monster';
				$select_what	= '*';
				$conditions		= "id = '{$id}'";
				$return			= $db->getRow($table, $conditions, $select_what);
			}
			// Return
			return $return;
		}


		/*
		Get All Monsters - getAll($max, $num_page, $ordering, $direction)
			@param integer	- Max rows
			@param integer	- Page number
			@param integer	- Ordering
			@param integer	- Ordering direction
			@return format	- Mixed array
		*/
		public function getAll($max = 20, $num_page = 1, $ordering = 'id', $direction = 'ASC') {
			$dbFunctions	= new dbFunctions();
			// Database Connection
			$db				= $GLOBALS['db'];
			// Initialize variables
			$return			= false;
			// Query set up
			$table			= 'tb_monster';
			$select_what	= '*';
			$conditions		= "1";
			$return			= $dbFunctions->getPage($select_what, $table, $conditions, $max, $num_page, $ordering, $direction);
			// Return
			return $return;
		}

		/*
		Get Searched Courses - getSearched($vc_search, $max, $num_page, $ordering, $direction)
			@param string	- String to be searched
			@param integer	- Max rows
			@param integer	- Pager number
			@param string	- Order by
			@param string	- Ordering direction
			@return format	- Mixed array
		*/
		public function getSearched($vc_search = false, $max = 20, $num_page = 1, $ordering = 'id', $direction = 'ASC') {
			$dbFunctions		= new dbFunctions();
			// Database Connection
			$db					= $GLOBALS['db'];
			// Initialize variables
			$return				= false;
			if ($vc_search !== false) {
				// Query set up
				$table			= 'tb_monster';
				$select_what	= '*';
				$conditions		= "id LIKE '%{$vc_search}%' OR vc_id LIKE '%{$vc_search}%'  OR vc_name LIKE '%{$vc_search}%' OR tx_description LIKE '%{$vc_search}%'";
				$return			= $dbFunctions->getPage($select_what, $table, $conditions, $max, $num_page, $ordering, $direction);
			}
			// Return
			return $return;
		}

		/*
		Insert monster into Database - insert($user_data)
			@param array	- Mixed with monster info (order like database w/ no id)
			@return boolean
		*/
		public function insert($user_data = false) {
			// Initialize variables
			$return						= false;
			// Database Connection
			$db							= $GLOBALS['db'];
			// Validate sent information
			if ($user_data) {
				$vc_id					= (isset($user_data[0])) ? $user_data[0] : false;
				$vc_name				= (isset($user_data[1])) ? $user_data[1] : false;
				$int_hits_min			= (isset($user_data[2])) ? $user_data[2] : false;
				$int_hits_max			= (isset($user_data[3])) ? $user_data[3] : false;
				$int_me					= (isset($user_data[4])) ? $user_data[4] : false;
				$int_damage_min			= (isset($user_data[5])) ? $user_data[5] : false;
				$int_damage_max			= (isset($user_data[6])) ? $user_data[6] : false;
				$int_ds					= (isset($user_data[7])) ? $user_data[7] : false;
				$int_knowledge			= (isset($user_data[8])) ? $user_data[8] : false;
				$int_treasure_min		= (isset($user_data[9])) ? $user_data[9] : false;
				$int_treasure_max		= (isset($user_data[10])) ? $user_data[10] : false;
				$tx_description			= (isset($user_data[11])) ? $user_data[11] : false;
				if (($vc_id !== false) && ($vc_name !== false) && ($int_hits_min !== false) && ($int_hits_max !== false) && ($int_me !== false) && ($int_damage_min !== false) && ($int_damage_max !== false) && ($int_ds !== false) && ($int_knowledge !== false) && ($int_treasure_min !== false) && ($int_treasure_max !== false) && ($tx_description !== false)) {
					// Add Monster to Database
					$db->insertRow('tb_monster', $user_data, '');
					$return			= true;
				}
			}
			return $return;
		}

		/*
		Update monster info - update(id_monster, $user_data)
			@param array	- Mixed with monster info (order like database w/ id)
			@return boolean
		*/
		public function update($id_monster = false, $user_data = false) {
			// Initialize variables
			$return						= false;
			// Database Connection
			$db							= $GLOBALS['db'];
			// Validate sent information
			if (($id_monster !== false) && ($user_data !== false)) {
				$vc_id					= (isset($user_data[0])) ? $user_data[0] : false;
				$vc_name				= (isset($user_data[1])) ? $user_data[1] : false;
				$int_hits_min			= (isset($user_data[2])) ? $user_data[2] : false;
				$int_hits_max			= (isset($user_data[3])) ? $user_data[3] : false;
				$int_me					= (isset($user_data[4])) ? $user_data[4] : false;
				$int_damage_min			= (isset($user_data[5])) ? $user_data[5] : false;
				$int_damage_max			= (isset($user_data[6])) ? $user_data[6] : false;
				$int_ds					= (isset($user_data[7])) ? $user_data[7] : false;
				$int_knowledge			= (isset($user_data[8])) ? $user_data[8] : false;
				$int_treasure_min		= (isset($user_data[9])) ? $user_data[9] : false;
				$int_treasure_max		= (isset($user_data[10])) ? $user_data[10] : false;
				$tx_description			= (isset($user_data[11])) ? $user_data[11] : false;
				if (($id_monster !== false) && ($vc_id !== false) && ($vc_name !== false) && ($int_hits_min !== false) && ($int_hits_max !== false) && ($int_me !== false) && ($int_damage_min !== false) && ($int_damage_max !== false) && ($int_ds !== false) && ($int_knowledge !== false) && ($int_treasure_min !== false) && ($int_treasure_max !== false) && ($tx_description !== false)) {
					$table				= 'tb_monster';
					$fields[]			= 'vc_id';
					$fields[]			= 'vc_name';
					$fields[]			= 'int_hits_min';
					$fields[]			= 'int_hits_max';
					$fields[]			= 'int_me';
					$fields[]			= 'int_damage_min';
					$fields[]			= 'int_damage_max';
					$fields[]			= 'int_ds';
					$fields[]			= 'int_knowledge';
					$fields[]			= 'int_treasure_min';
					$fields[]			= 'int_treasure_max';
					$fields[]			= 'tx_description';
					$conditions			= "id = {$id_monster}";
					$return				= $db->updateRow($table, $fields, $user_data, $conditions);
				}
			}
			return $return;
		}

		/*
		Delete monster - delete($id)
			@param integer	- Monster ID
			@return boolean
		*/
		public function delete($id = false) {
			// Initialize variables
			$return			= false;
			// Database Connection
			$db				= $GLOBALS['db'];
			// If user ID was sent
			if ($id !== false) {
				// Set up query
				$table		= 'tb_monster';
				$conditions	= 'id = '.$id;
				$return		= $db->deleteRow($table, $conditions);
			}
			return $return;
		}

	}