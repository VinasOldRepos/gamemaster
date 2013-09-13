<?php
/************************************************************************************
* Name:				Monsters Controller												*
* File:				Application\Controller\MonstersController.php 					*
* Author(s):		Vinas de Andrade												*
*																					*
* Description: 		This file controls Courses' related information.				*
*																					*
* Creation Date:	06/06/2013														*
* Version:			1.13.0606														*
* License:			http://www.opensource.org/licenses/bsd-license.php BSD			*
*************************************************************************************/

	namespace Application\Controller;

	// Framework Classes
	use SaSeed\View;
	use SaSeed\Session;
	use SaSeed\General;

	// Model Classes
	use Application\Model\Menu;
	use Application\Model\Pager;
	use Application\Model\Monster					as ModMonster;

	// Repository Classes
	use Application\Controller\Repository\Monster	as RepMonster;

	// Other Classes
	use Application\Controller\LogInController		as LogIn;

	class MonstersController {

		public function __construct() {
			// Start session
			Session::start();
			// Check if user is Logged
			$SesMonster					= LogIn::checkLogin();
			if (!$SesMonster) {
				// Redirect to login area when not
				header('location: '.URL_PATH.'/LogIn/');
			} else {
				// Define JSs e CSSs utilizados por este controller
				$GLOBALS['this_js']		= '<script type="text/javascript" src="/gamemaster/Application/View/js/scripts/monsters.js"></script>'.PHP_EOL;	// Se não houver, definir como vazio ''
				$GLOBALS['this_css']	= '<link href="'.URL_PATH.'/Application/View/css/monsters.css" rel="stylesheet">'.PHP_EOL;	// Se não houver, definir como vazio ''
				// Define Menu selection
				Menu::defineSelected($GLOBALS['controller_name']);
			}
		}

		/*
		Prints out main login page - start()
			@return format	- print
		*/
		public function index() {
			View::render('monsters');
 		}

		/*
		Prints out new course page - New()
			@return format	- print
		*/
		public function Insert() {
			// Define sub menu selection
			$GLOBALS['menu']['monsters']['opt1_css'] = 'details_item_on';
			// Render view
			View::render('monstersInsert');
 		}

		/*
		Prints out search course page - Search()
			@return format	- render view
		*/
		public function Search() {
			// Declare Classes
			$RepMonster			= new RepMonster();
			$ModMonster			= new ModMonster();
			// Define sub menu selection
			$GLOBALS['menu']['monsters']['opt2_css'] = 'details_item_on';
			// Intialize variables
			$return				= '<br />(no monsters found)';
			$search				= (isset($_POST['search'])) ? trim($_POST['search']) : false;
			$search				= ((!$search) && (isset($GLOBALS['params'][1]))) ? trim($GLOBALS['params'][1]) : false;
			// Get first 20 entries
			$result				= $RepMonster->getAll(20);
			// If there are entries
			if ($result) {
				// Separate returned data an paging info
				$rows			= $result[0];
				$paging_info	= $result[1];
				// Model Result
				$return			= $ModMonster->listMonsters($rows, 'id', 'ASC');
				// Define Pager info
				$pager				= Pager::pagerOptions($paging_info, 'monsters', 'partialResult');
			}
			// Prepare info to be displayed
			View::set('pager', $pager);
			View::set('return', $return);
			// render view
			View::render('usersSearch');
 		}

		/*
		Prints partial results - partialResult()
			@return format	- render view
		*/
		public function partialResult() {
			// Declare Classes
			$RepMonster				= new RepMonster();
			$ModMonster				= new ModMonster();
			// Intialize variables
			$return					= '<br />(no users found)';
			$num_page				= (isset($_POST['num_page'])) ? trim($_POST['num_page']) : false;
			$ordering				= (isset($_POST['ordering'])) ? trim($_POST['ordering']) : false;
			$offset					= (isset($_POST['offset'])) ? trim($_POST['offset']) : false;
			$limit					= (isset($_POST['limit'])) ? trim($_POST['limit']) : false;
			$direction				= (isset($_POST['direction'])) ? trim($_POST['direction']) : false;
			$str_search				= (isset($_POST['str_search'])) ? trim($_POST['str_search']) : false;
			$pager					= '';
			// If data was sent
			if (($num_page !== false) && ($ordering !== false) && ($offset !== false) && ($limit !== false) && ($direction !== false)) {
			//if (($num_page) && ($ordering) && ($limit) && ($direction)) {
				// Get searched data
				if ($str_search) {
					$result			= $RepMonster->getSearched($str_search, $limit, $num_page, $ordering, $direction);
				} else {
					$result			= $RepMonster->getAll($limit, $num_page, $ordering, $direction);
				}
				// If there are entries
				if ($result) {
					// Separate returned data and paging info
					$rows			= $result[0];
					$paging_info	= $result[1];
					// Model Result
					$pager			= Pager::pagerOptions($paging_info, 'monsters', 'partialResult');
					$return			= $ModMonster->jqueryMonsters($rows, $pager, $ordering, $direction);
				}
			}
			// Print out result
			echo $return;
 		}

		/*
		Open Monster details main page - details()
			@return format	- print
		*/
		public function details() {
			// Declare classes
			$RepMonster		= new RepMonster();
			$ModMonster		= new ModMonster();
			// Initialize variables
			$id_monster		= (isset($GLOBALS['params'][1])) ? trim(($GLOBALS['params'][1])) : false;
			// If user id was sent
			if ($id_monster) {
				// Get branch Info
				$monster	= $RepMonster->getById($id_monster);
				// If user was found
				if ($monster) {
					// Prepare data to be sent
					View::set('id_monster',			$id_monster);
					View::set('vc_id',				$monster['vc_id']);
					View::set('vc_name',			$monster['vc_name']);
					View::set('int_level',			$monster['int_level']);
					View::set('int_hits_min',		$monster['int_hits_min']);
					View::set('int_hits_max',		$monster['int_hits_max']);
					View::set('int_me',				$monster['int_me']);
					View::set('int_damage_min',		$monster['int_damage_min']);
					View::set('int_damage_max',		$monster['int_damage_max']);
					View::set('int_ds',				$monster['int_ds']);
					View::set('int_knowledge',		$monster['int_knowledge']);
					View::set('int_treasure_min',	$monster['int_treasure_min']);
					View::set('int_treasure_max',	$monster['int_treasure_max']);
					View::set('tx_description',		$monster['tx_description']);
					// Render page
					View::render('partial_monsterDetails');
				}
			}
		}

		/*
		Create a new monster - addMonster()
			@return format	- print
		*/
		public function addMonster() {
			// Declare classes
			$RepMonster		= new RepMonster();
			// Initialize variable
			$vc_id				= (isset($_POST['vc_id'])) ? trim($_POST['vc_id']) : false;
			$int_level			= (isset($_POST['int_level'])) ? trim($_POST['int_level']) : false;
			$vc_name			= (isset($_POST['vc_name'])) ? trim($_POST['vc_name']) : false;
			$int_hits_min		= (isset($_POST['int_hits_min'])) ? trim($_POST['int_hits_min']) : false;
			$int_hits_max		= (isset($_POST['int_hits_max'])) ? trim($_POST['int_hits_max']) : false;
			$int_me				= (isset($_POST['int_me'])) ? trim($_POST['int_me']) : false;
			$int_damage_min		= (isset($_POST['int_damage_min'])) ? trim($_POST['int_damage_min']) : false;
			$int_damage_max		= (isset($_POST['int_damage_max'])) ? trim($_POST['int_damage_max']) : false;
			$int_ds				= (isset($_POST['int_ds'])) ? trim($_POST['int_ds']) : false;
			$int_knowledge		= (isset($_POST['int_knowledge'])) ? trim($_POST['int_knowledge']) : false;
			$int_treasure_min	= (isset($_POST['int_treasure_min'])) ? trim($_POST['int_treasure_min']) : false;
			$int_treasure_max	= (isset($_POST['int_treasure_max'])) ? trim($_POST['int_treasure_max']) : false;
			$tx_description		= (isset($_POST['tx_description'])) ? General::quotes(trim($_POST['tx_description'])) : false;
			$return				= false;
			// If values were sent
			if (($vc_id !== false) && ($int_level !== false) && ($vc_name !== false) && ($int_hits_min !== false) && ($int_hits_max !== false) && ($int_me !== false) && ($int_damage_min !== false) && ($int_damage_max !== false) && ($int_ds !== false) && ($int_knowledge !== false) && ($int_treasure_min !== false) && ($int_treasure_max !== false) && ($tx_description !== false)) {
				// Create Monster
				$monster_data[]	= $vc_id;
				$monster_data[]	= $vc_name;
				$monster_data[]	= $int_level;
				$monster_data[]	= $int_hits_min;
				$monster_data[]	= $int_hits_max;
				$monster_data[]	= $int_me;
				$monster_data[]	= $int_damage_min;
				$monster_data[]	= $int_damage_max;
				$monster_data[]	= $int_ds;
				$monster_data[]	= $int_knowledge;
				$monster_data[]	= $int_treasure_min;
				$monster_data[]	= $int_treasure_max;
				$monster_data[]	= $tx_description;
				$return			= $RepMonster->insert($monster_data);
				// Prepare return
				
				$return			= ($return) ? 'ok' : false;
			}	
			// Return
			echo $return;
 		}

		/*
		Update Monster info - updateMonster()
			@return format	- print
		*/
		public function updateMonster() {
			// Declare classes
			$RepMonster			= new RepMonster();
			// Initialize variable
			$id_monster			= (isset($_POST['id_monster'])) ? trim($_POST['id_monster']) : false;
			$vc_id				= (isset($_POST['vc_id'])) ? trim($_POST['vc_id']) : false;
			$int_level			= (isset($_POST['int_level'])) ? trim($_POST['int_level']) : false;
			$vc_name			= (isset($_POST['vc_name'])) ? trim($_POST['vc_name']) : false;
			$int_hits_min		= (isset($_POST['int_hits_min'])) ? trim($_POST['int_hits_min']) : false;
			$int_hits_max		= (isset($_POST['int_hits_max'])) ? trim($_POST['int_hits_max']) : false;
			$int_me				= (isset($_POST['int_me'])) ? trim($_POST['int_me']) : false;
			$int_damage_min		= (isset($_POST['int_damage_min'])) ? trim($_POST['int_damage_min']) : false;
			$int_damage_max		= (isset($_POST['int_damage_max'])) ? trim($_POST['int_damage_max']) : false;
			$int_ds				= (isset($_POST['int_ds'])) ? trim($_POST['int_ds']) : false;
			$int_knowledge		= (isset($_POST['int_knowledge'])) ? trim($_POST['int_knowledge']) : false;
			$int_treasure_min	= (isset($_POST['int_treasure_min'])) ? trim($_POST['int_treasure_min']) : false;
			$int_treasure_max	= (isset($_POST['int_treasure_max'])) ? trim($_POST['int_treasure_max']) : false;
			$tx_description		= (isset($_POST['tx_description'])) ? General::quotes(trim($_POST['tx_description'])) : false;
			$return				= false;
			// If values were sent
			if (($id_monster !== false) && ($vc_id !== false) && ($int_level !== false) && ($vc_name !== false) && ($int_hits_min !== false) && ($int_hits_max !== false) && ($int_me !== false) && ($int_damage_min !== false) && ($int_damage_max !== false) && ($int_ds !== false) && ($int_knowledge !== false) && ($int_treasure_min !== false) && ($int_treasure_max !== false) && ($tx_description !== false)) {
				// Update Monster				
				$monster_data[]	= $vc_id;
				$monster_data[]	= $vc_name;
				$monster_data[]	= $int_level;
				$monster_data[]	= $int_hits_min;
				$monster_data[]	= $int_hits_max;
				$monster_data[]	= $int_me;
				$monster_data[]	= $int_damage_min;
				$monster_data[]	= $int_damage_max;
				$monster_data[]	= $int_ds;
				$monster_data[]	= $int_knowledge;
				$monster_data[]	= $int_treasure_min;
				$monster_data[]	= $int_treasure_max;
				$monster_data[]	= $tx_description;
				$return			= $RepMonster->update($id_monster, $monster_data);
				// Prepare return
				$return			= ($return) ? 'ok' : false;
			}
			// Return
			echo $return;
 		}

		/*
		Delete Monster - deleteMonster()
			@return format	- print
		*/
		public function deleteMonster() {
			// Declare classes
			$RepMonster		= new RepMonster();
			// Initialize variables
			$return			= false;
			$id_monster		= (isset($_POST['id_monster'])) ? trim($_POST['id_monster']) : false;
			// If values were sent
			if ($id_monster) {
				// Delete branch
				$RepMonster->delete($id_monster);
				$return		= 'ok';
			}
			// Return
			echo $return;
		}
	}