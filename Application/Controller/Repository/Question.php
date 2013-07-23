<?php
/************************************************************************************
* Name:				Question Repository												*
* File:				Application\Controller\Questionphp 								*
* Author(s):		Vinas de Andrade												*
*																					*
* Description: 		This contains pre-written functions that execute Database tasks	*
*					related to login information.									*
*																					*
* Creation Date:	11/07/2013														*
* Version:			1.13.0713														*
* License:			http://www.opensource.org/licenses/bsd-license.php BSD			*
*************************************************************************************/

	namespace Application\Controller\Repository;

	use Application\Controller\Repository\dbFunctions;
	use SaSeed\Database;

	class Question {


		public function __construct() {
			// 	Question Database Connection
			if (DB_NAME_Q) {
				$GLOBALS['db_q']	= new Database();
				$GLOBALS['db_q']->DBConnection(DB_HOST_Q, DB_USER_Q, DB_PASS_Q, DB_NAME_Q);
			}
		}

		/*
		Get All Branches - getAllBranches()
			@return format	- Mixed array
		*/
		public function getAllBranches() {
			// Database Connection
			$db				= $GLOBALS['db_q'];
			// Initialize variables
			$return			= false;
			// Query set up	
			$table			= 'tb_branch AS b';
			$select_what	= 'b.id, b.vc_branch AS vc_name';
			$conditions		= "1 ORDER BY vc_branch ASC";
			$return			= $db->getAllRows_Arr($table, $select_what, $conditions);
			// Return
			return $return;
		}

		/*
		Get All Fields  - getAllFields()
			@return format	- Mixed array
		*/
		public function getAllFields() {
			// Database Connection
			$db				= $GLOBALS['db_q'];
			// Initialize variables
			$return			= false;
			// Query set up	
			$table			= 'tb_field';
			$select_what	= 'id, vc_field AS vc_name';
			$conditions		= "1 ORDER BY vc_field ASC";
			$return			= $db->getAllRows_Arr($table, $select_what, $conditions);
			// Return
			return $return;
		}

		/*
		Get Branch Id by Field Id - getBranchIdByFieldId($id)
			@param integer	- Field ID
			@return format	- Mixed array
		*/
		public function getBranchIdByFieldId($id = false) {
			// Database Connection
			$db					= $GLOBALS['db_q'];
			// Initialize variables
			$return				= false;
			if ($id) {
				// Query set up	
				$table			= 'tb_field AS f JOIN tb_branch AS b ON f.id_branch = b.id';
				$select_what	= 'b.vc_branch AS vc_name';
				$conditions		= "f.id = {$id}";
				$return			= $db->getRow($table, $select_what, $conditions);
			}
			// Return
			return $return;
		}

		/*
		Get Fields by Branch Id - getFieldsBranchId($id)
			@param integer	- Branch id ID
			@return format	- Mixed array
		*/
		public function getFieldsBranchId($id = false) {
			// Database Connection
			$db					= $GLOBALS['db_q'];
			// Initialize variables
			$return				= ($id) ? $db->getAllRows_Arr('tb_field', 'id, vc_field as vc_name', "id_branch = {$id}") :  false;
			// Return
			return $return;
		}

	}