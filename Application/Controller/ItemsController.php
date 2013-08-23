<?php
/************************************************************************************
* Name:				Items Controller												*
* File:				Application\Controller\ItemsController.php 						*
* Author(s):		Vinas de Andrade												*
*																					*
* Description: 		This file controls Items related information.					*
*																					*
* Creation Date:	23/08/2013														*
* Version:			1.13.0823														*
* License:			http://www.opensource.org/licenses/bsd-license.php BSD			*
*************************************************************************************/

	namespace Application\Controller;

	// Framework Classes
	use SaSeed\View;
	use SaSeed\Session;
	use SaSeed\General;

	// Repository Classes
	use Application\Controller\Repository\Item		as RepItem;
	use Application\Controller\Repository\Question	as RepQuestion;

	// Model Classes
	use Application\Model\Menu;
	use Application\Model\Pager;
	use Application\Model\Item						as ModItem;

	// Other Classes
	use Application\Controller\LogInController		as LogIn;

	class ItemsController{

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
				$GLOBALS['this_js']		= '<script type="text/javascript" src="/gamemaster/Application/View/js/scripts/items.js"></script>'.PHP_EOL;	// Se não houver, definir como vazio ''
				$GLOBALS['this_js']		.= '<script type="text/javascript" src="/gamemaster/Application/View/js/libs/jquery.fancybox-1.3.4.pack.js"></script>'.PHP_EOL;	// Se não houver, definir como vazio ''
				$GLOBALS['this_css']	= '<link href="'.URL_PATH.'/Application/View/css/items.css" rel="stylesheet">'.PHP_EOL;	// Se não houver, definir como vazio ''
				$GLOBALS['this_css']	.= '<link href="'.URL_PATH.'/Application/View/css/jquery.fancybox-1.3.4.css" rel="stylesheet">'.PHP_EOL;	// Se não houver, definir como vazio ''
				// Define Menu selection
				Menu::defineSelected($GLOBALS['controller_name']);
			}
		}

		/*
		Prints out new Item page - Insert()
			@return format	- render view
		*/
		public function Insert() {
			// Declare classes
			$RepItem			= new RepItem();
			$ModItem			= new ModItem();
			// Fetch and model Item types
			$combat_types		= $RepItem->getAllCombatItemTypes();
			$combat_types		= ($combat_types) ? $ModItem->combo($combat_types) : false;
			$noncombat_types	= $RepItem->getAllNonCombatItemTypes();
			$noncombat_types	= ($noncombat_types) ? $ModItem->combo($noncombat_types) : false;
			// Fetch and model fields for combo
			$RepQuestion		= new RepQuestion();
			$branches			= $RepQuestion->getAllBranches();
			$branches			= ($branches) ? $ModItem->combo($branches, true) : false;
			// Define sub menu selection
			$GLOBALS['menu']['items']['opt1_css'] = 'details_item_on';
			View::set('branches',			$branches);
			View::set('combat_types',		$combat_types);
			View::set('noncombat_types',	$noncombat_types);
			// Render view
			View::render('itemsInsert');
 		}

		/*
		Prints out Inserted Item page - Insert()
			@return format	- render view
		*/
		public function Inserted() {
			// Define sub menu selection
			$GLOBALS['menu']['items']['opt1_css'] = 'details_item_on';
			// Render view
			View::render('itemsInserted');
 		}

		/*
		Combat Items list - SearchComatItems()
			@return format	- render view
		*/
		public function SearchCombatItems() {
			// Declare Classes
			$RepItem			= new RepItem();
			$ModItem			= new ModItem();
			// Intialize variables
			$return				= '<br />(no Combat Items found)';
			// Get first 20 entries
			$result				= $RepItem->getAllCombatItems(20);
			// If there are entries
			if ($result) {
				// Separate returned data an paging info
				$rows			= $result[0];
				$paging_info	= $result[1];
				// Model Result
				$return			= $ModItem->listCombatItems($rows, 'i.id', 'ASC');
				// Define Pager info
				$pager			= Pager::pagerOptions($paging_info, 'items', 'partialCombatResult');
			}
			// Define sub menu selection
			$GLOBALS['menu']['items']['opt2_css'] = 'details_item_on';
			// Prepare info to be displayed
			View::set('pager', $pager);
			View::set('return', $return);
			// render view
			View::render('usersSearch');
 		}

		/*
		Non-Combat Items list - SearchNonCombatItems()
			@return format	- render view
		*/
		public function SearchNonCombatItems() {
			// Declare Classes
			$RepItem			= new RepItem();
			$ModItem			= new ModItem();
			// Intialize variables
			$return				= '<br />(no Non-Combat Items found)';
			// Get first 20 entries
			$result				= $RepItem->getAllNonCombatItems(20);
			// If there are entries
			if ($result) {
				// Separate returned data an paging info
				$rows			= $result[0];
				$paging_info	= $result[1];
				// Model Result
				$return			= $ModItem->listNonCombatItems($rows, 'i.id', 'ASC');
				// Define Pager info
				$pager			= Pager::pagerOptions($paging_info, 'items', 'partialNonCombatResult');
			}
			// Define sub menu selection
			$GLOBALS['menu']['items']['opt3_css'] = 'details_item_on';
			// Prepare info to be displayed
			View::set('pager', $pager);
			View::set('return', $return);
			// render view
			View::render('usersSearch');
 		}

		/*
		Saves a combat item - addCombatItem()
			@return format	- print
		*/
		public function addCombatItem() {
			// Declare classes
			$RepItem			= new RepItem();
			// Initialize variables
			$return				= false;
			$id_field			= (isset($_POST['id_field'])) ? trim($_POST['id_field']) : false;
			$id_type			= (isset($_POST['id_type'])) ? trim($_POST['id_type']) : false;
			$int_level			= (isset($_POST['int_level'])) ? trim($_POST['int_level']) : false;
			$int_bonus			= (isset($_POST['int_bonus'])) ? trim($_POST['int_bonus']) : false;
			$vc_name			= (isset($_POST['vc_name'])) ? trim($_POST['vc_name']) : false;
			// If values were sent
			if (($id_field) && ($id_type) && ($int_level) && ($int_bonus) && ($vc_name)) {
				// Save Item and prepare return
				$return	= ($RepItem->insertCombatItem($id_field, $id_type, $int_level, $int_bonus, $vc_name)) ? 'ok' : false;
			}
			// Return
			echo $return;
 		}

		/*
		Saves a non-combat item - addNonCombatItem()
			@return format	- print
		*/
		public function addNonCombatItem() {
			// Declare classes
			$RepItem			= new RepItem();
			// Initialize variables
			$return				= false;
			$id_type			= (isset($_POST['id_type'])) ? trim($_POST['id_type']) : false;
			$int_level			= (isset($_POST['int_level'])) ? trim($_POST['int_level']) : false;
			$int_bonus_start	= (isset($_POST['int_bonus_start'])) ? trim($_POST['int_bonus_start']) : false;
			$int_bonus_end		= (isset($_POST['int_bonus_end'])) ? trim($_POST['int_bonus_end']) : false;
			$vc_name			= (isset($_POST['vc_name'])) ? trim($_POST['vc_name']) : false;
			// If values were sent
			if (($id_type) && ($int_level) && ($int_bonus_start) && ($int_bonus_end) && ($vc_name)) {
				// Save Item and prepare return
				$return	= ($RepItem->insertNonCombatItem($id_type, $int_level, $int_bonus_start, $int_bonus_end, $vc_name)) ? 'ok' : false;
			}
			// Return
			echo $return;
 		}

	}