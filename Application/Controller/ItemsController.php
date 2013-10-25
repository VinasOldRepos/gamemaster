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
				// Get Field names
				$RepQuestion	= new RepQuestion();
				$fields			= $RepQuestion->getAllFields();
				// Separate returned data an paging info
				$rows			= $result[0];
				$paging_info	= $result[1];
				// Model Result
				$return			= $ModItem->listCombatItems($rows, $fields, 'i.id', 'ASC');
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
		Combat Items list - partialCombatResult()
			@return format	- render view
		*/
		public function partialCombatResult() {
			// Declare Classes
			$RepItem				= new RepItem();
			$ModItem				= new ModItem();
			// Intialize variables
			$return					= '<br />(no combat items found)';
			$num_page				= (isset($_POST['num_page'])) ? trim($_POST['num_page']) : false;
			$ordering				= (isset($_POST['ordering'])) ? trim($_POST['ordering']) : false;
			$offset					= (isset($_POST['offset'])) ? trim($_POST['offset']) : false;
			$limit					= (isset($_POST['limit'])) ? trim($_POST['limit']) : false;
			$direction				= (isset($_POST['direction'])) ? trim($_POST['direction']) : false;
			$str_search				= (isset($_POST['str_search'])) ? trim($_POST['str_search']) : false;
			$pager					= '';
			// If data was sent
			//if (($num_page) && ($ordering) && ($offset) && ($limit) && ($direction)) {
			if (($num_page) && ($ordering) && ($limit) && ($direction)) {
				// Get searched data
				if ($str_search) {
					$result			= $RepItem->getSearchedCombat($str_search, $limit, $num_page, $ordering, $direction);
				} else {
					$result			= $RepItem->getAllCombatItems($limit, $num_page, $ordering, $direction);
				}
				// If there are entries
				if ($result) {
					// Get Field names
					$RepQuestion	= new RepQuestion();
					$fields			= $RepQuestion->getAllFields();
					// Separate returned data and paging info
					$rows			= $result[0];
					$paging_info	= $result[1];
					// Model Result
					$pager			= Pager::pagerOptions($paging_info, 'items', 'partialCombatResult');
					$return			= $ModItem->jqueryCombatItems($rows, $fields, $pager, $ordering, $direction);
				}
			}
			// Print out result
			echo $return;
 		}

		/*
		Non Combat Items list - partialNonCombatResult()
			@return format	- render view
		*/
		public function partialNonCombatResult() {
			// Declare Classes
			$RepItem				= new RepItem();
			$ModItem				= new ModItem();
			// Intialize variables
			$return					= '<br />(no non+combat items found)';
			$num_page				= (isset($_POST['num_page'])) ? trim($_POST['num_page']) : false;
			$ordering				= (isset($_POST['ordering'])) ? trim($_POST['ordering']) : false;
			$offset					= (isset($_POST['offset'])) ? trim($_POST['offset']) : false;
			$limit					= (isset($_POST['limit'])) ? trim($_POST['limit']) : false;
			$direction				= (isset($_POST['direction'])) ? trim($_POST['direction']) : false;
			$str_search				= (isset($_POST['str_search'])) ? trim($_POST['str_search']) : false;
			$pager					= '';
			// If data was sent
			//if (($num_page) && ($ordering) && ($offset) && ($limit) && ($direction)) {
			if (($num_page) && ($ordering) && ($limit) && ($direction)) {
				// Get searched data
				if ($str_search) {
					$result			= $RepItem->getSearchedNonCombat($str_search, $limit, $num_page, $ordering, $direction);
				} else {
					$result			= $RepItem->getAllNonCombatItems($limit, $num_page, $ordering, $direction);
				}
				// If there are entries
				if ($result) {
					// Separate returned data and paging info
					$rows			= $result[0];
					$paging_info	= $result[1];
					// Model Result
					$pager			= Pager::pagerOptions($paging_info, 'items', 'partialNonCombatResult');
					$return			= $ModItem->jqueryNonCombatItems($rows, $pager, $ordering, $direction);
				}
			}
			// Print out result
			echo $return;
 		}

		/*
		Renders Combat Items' Details - combatDetails()
			@return format	- render view
		*/
		public function combatDetails() {
			// Add Clases
			$RepItem	= new RepItem();
			$ModItem	= new ModItem();
			// Initialize variables
			$id			= (isset($GLOBALS['params'][1])) ? trim(($GLOBALS['params'][1])) : false;
			// If values were sent
			if ($id) {
				// Get item's data
				$item					= $RepItem->getCombatById($id);
				if ($item) {
					$types				= $RepItem->getAllCombatItemTypes();
					$RepQuestion		= new RepQuestion();
					if ((isset($item['id_field'])) && ($item['id_field'] != 0)) {
						$vc_field		= ($field = $RepQuestion->getFieldById($item['id_field'])) ? $field['vc_field'] : false;
						$vc_branch		= ($branch = $RepQuestion->getBranchFieldId($item['id_field'])) ? $branch['vc_branch'] : false;
					} else {
						$vc_field		= 'General';
						$vc_branch		= 'General';
					}
					// Model info
					$types				= $ModItem->combo($types, false, $item['id_type']);
					$wearables			= $ModItem->comboWearables(false, $item['vc_wearable']);
					// Prepare data for return
					View::set('id_item',		$item['id']);
					View::set('id_field',		$item['id_field']);
					View::set('int_level',		$item['int_level']);
					View::set('int_me_min',		$item['int_me_min']);
					View::set('int_me_max',		$item['int_me_max']);
					View::set('int_magic_me',	$item['int_magic_me']);
					View::set('int_ds',			$item['int_ds']);
					View::set('int_magic_ds',	$item['int_magic_ds']);
					View::set('int_time',		$item['int_time']);
					View::set('vc_name',		$item['vc_name']);
					View::set('vc_field',		$vc_field);
					View::set('vc_branch',		$vc_branch);
					View::set('types',			$types);
					View::set('wearables',		$wearables);
					// Return
					View::render('partial_combatItemDetails');
				}
			}
		}

		/*
		Renders Non-Combat Items' Details - nonCombatDetails()
			@return format	- render view
		*/
		public function nonCombatDetails() {
			// Add Clases
			$RepItem	= new RepItem();
			$ModItem	= new ModItem();
			// Initialize variables
			$id			= (isset($GLOBALS['params'][1])) ? trim(($GLOBALS['params'][1])) : false;
			// If values were sent
			if ($id) {
				// Get item's data
				$item					= $RepItem->getNonCombatById($id);
				if ($item) {
					$types				= $RepItem->getAllNonCombatItemTypes();
					$RepQuestion		= new RepQuestion();
					// Model info
					$types				= $ModItem->combo($types, false, $item['id_type']);
					// Prepare data for return
					View::set('id_item',			$item['id']);
					View::set('int_level',			$item['int_level']);
					View::set('int_bonus_start',	$item['int_bonus_start']);
					View::set('int_bonus_end',		$item['int_bonus_end']);
					View::set('vc_name',			$item['vc_name']);
					View::set('types',				$types);
					// Return
					View::render('partial_nonCombatItemDetails');
				}
			}
		}

		/*
		Saves a combat item - addCombatItem()
			@return format	- print
		*/
		public function addCombatItem() {
			// Declare classes
			$RepItem		= new RepItem();
			// Initialize variables
			$return			= false;
			$id_field		= (isset($_POST['id_field'])) ? trim($_POST['id_field']) : false;
			$id_type		= (isset($_POST['id_type'])) ? trim($_POST['id_type']) : false;
			$int_level		= (isset($_POST['int_level'])) ? trim($_POST['int_level']) : '0';
			$me_min			= (isset($_POST['me_min'])) ? trim($_POST['me_min']) : '0';
			$me_max			= (isset($_POST['me_max'])) ? trim($_POST['me_max']) : '0';
			$magic_me		= (isset($_POST['magic_me'])) ? trim($_POST['magic_me']) : '0';
			$ds				= (isset($_POST['ds'])) ? trim($_POST['ds']) : '0';
			$magic_ds		= (isset($_POST['magic_ds'])) ? trim($_POST['magic_ds']) : '0';
			$time			= (isset($_POST['time'])) ? trim($_POST['time']) : '0';
			$vc_wearable	= (isset($_POST['vc_wearable'])) ? trim($_POST['vc_wearable']) : false;
			$vc_name		= (isset($_POST['vc_name'])) ? trim($_POST['vc_name']) : false;
			// If values were sent
			if (($id_field) && ($id_type) && ($vc_name)) {
				// Save Item and prepare return
				$return	= ($RepItem->insertCombatItem($id_field, $id_type, $int_level, $me_min, $me_max, $magic_me, $ds, $magic_ds, $time, $vc_wearable, $vc_name)) ? 'ok' : false;
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
			if (($id_type !== false) && ($int_level !== false) && ($int_bonus_start !== false) && ($int_bonus_end !== false) && ($vc_name !== false)) {
				// Save Item and prepare return
				$return	= ($RepItem->insertNonCombatItem($id_type, $int_level, $int_bonus_start, $int_bonus_end, $vc_name)) ? 'ok' : false;
			}
			// Return
			echo $return;
 		}

		/*
		Update a Combat item - updtCombatItem()
			@return format	- print
		*/
		public function updtCombatItem() {
			// Declare classes
			$RepItem		= new RepItem();
			// Initialize variables
			$return			= false;
			$id				= (isset($_POST['id'])) ? trim($_POST['id']) : false;
			$id_field		= (isset($_POST['id_field'])) ? trim($_POST['id_field']) : false;
			$id_type		= (isset($_POST['id_type'])) ? trim($_POST['id_type']) : false;
			$int_level		= (isset($_POST['int_level'])) ? trim($_POST['int_level']) : '0';
			$me_min			= (isset($_POST['me_min'])) ? trim($_POST['me_min']) : '0';
			$me_max			= (isset($_POST['me_max'])) ? trim($_POST['me_max']) : '0';
			$magic_me		= (isset($_POST['magic_me'])) ? trim($_POST['magic_me']) : '0';
			$ds				= (isset($_POST['ds'])) ? trim($_POST['ds']) : '0';
			$magic_ds		= (isset($_POST['magic_ds'])) ? trim($_POST['magic_ds']) : '0';
			$time			= (isset($_POST['time'])) ? trim($_POST['time']) : '0';
			$vc_wearable	= (isset($_POST['vc_wearable'])) ? trim($_POST['vc_wearable']) : false;
			$vc_name		= (isset($_POST['vc_name'])) ? trim($_POST['vc_name']) : false;
			// If values were sent
			if (($id) && ($id_type) && ($vc_name)) {
				// Save Item and prepare return
				$return	= ($RepItem->updateCombatItem($id, $id_field, $id_type, $int_level, $me_min, $me_max, $magic_me, $ds, $magic_ds, $time, $vc_wearable, $vc_name)) ? 'ok' : false;
			}
			// Return
			echo $return;
 		}

		/*
		Update a Non-Combat item - updtNonCombatItem()
			@return format	- print
		*/
		public function updtNonCombatItem() {
			// Declare classes
			$RepItem			= new RepItem();
			// Initialize variables
			$return				= false;
			$id					= (isset($_POST['id'])) ? trim($_POST['id']) : false;
			$id_type			= (isset($_POST['id_type'])) ? trim($_POST['id_type']) : false;
			$int_level			= (isset($_POST['int_level'])) ? trim($_POST['int_level']) : false;
			$int_bonus_start	= (isset($_POST['int_bonus_start'])) ? trim($_POST['int_bonus_start']) : false;
			$int_bonus_end		= (isset($_POST['int_bonus_end'])) ? trim($_POST['int_bonus_end']) : false;
			$vc_name			= (isset($_POST['vc_name'])) ? trim($_POST['vc_name']) : false;
			// If values were sent
			if (($id) && ($id_type) && ($int_level) && ($int_bonus_start) && ($int_bonus_end) && ($vc_name)) {
				// Save Item and prepare return
				$return	= ($RepItem->updateNonCombatItem($id, $id_type, $int_level, $int_bonus_start, $int_bonus_end, $vc_name)) ? 'ok' : false;
			}
			// Return
			echo $return;
 		}

		/*
		Deletes a Combat item - deleteCombatItem()
			@return format	- print
		*/
		public function deleteCombatItem() {
			// Declare classes
			$RepItem	= new RepItem();
			// Initialize variables
			$return		= false;
			$id			= (isset($_POST['id'])) ? trim($_POST['id']) : false;
			// If values were sent
			if ($id) {
				// Save Item and prepare return
				$return	= ($RepItem->deleteCombatItem($id)) ? 'ok' : false;
			}
			// Return
			echo $return;
		}

		/*
		Deletes a Non-Combat item - deleteNonCombatItem()
			@return format	- print
		*/
		public function deleteNonCombatItem() {
			// Declare classes
			$RepItem	= new RepItem();
			// Initialize variables
			$return		= false;
			$id			= (isset($_POST['id'])) ? trim($_POST['id']) : false;
			// If values were sent
			if ($id) {
				// Save Item and prepare return
				$return	= ($RepItem->deleteNonCombatItem($id)) ? 'ok' : false;
			}
			// Return
			echo $return;
		}
	}