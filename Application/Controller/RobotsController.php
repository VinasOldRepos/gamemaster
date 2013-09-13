<?php
/*
					######## DO NOT UPLOAD THIS FILE ########
					######## DO NOT UPLOAD THIS FILE ########
*************************************************************************************
* Name:				Robots Controller												*
* File:				Application\Controller\RobotsController.php 					*
* Author(s):		Vinas de Andrade												*
*																					*
* Description: 		This files holds specific database related functions, designed 	*
*					to save testing time. It MUST NOT be uploaded. 					*
*																					*
* Creation Date:	20/08/2013														*
* Version:			1.13.0820														*
* License:			http://www.opensource.org/licenses/bsd-license.php BSD			*
*************************************************************************************
					######## DO NOT UPLOAD THIS FILE ########
					######## DO NOT UPLOAD THIS FILE ########
*/

	namespace Application\Controller;

	// Framework Classes
	use SaSeed\View;
	use SaSeed\Session;
//	use SaSeed\General;

	// Repository Classes
	use Application\Controller\Repository\Map		as RepMap;
	use Application\Controller\Repository\Monster	as RepMonster;
	use Application\Controller\Repository\Question	as RepQuestion;

	// Model Classes
	use Application\Model\Map						as ModMap;
	use Application\Model\Monster					as ModMonster;
	use Application\Model\Combat					as ModCombat;

	// Other Classes
	use Application\Controller\LogInController		as LogIn;

	class RobotsController{

		public function __construct() {
			// Start session
			Session::start();
			// Check if user is Logged
			$SesUser					= LogIn::checkLogin();
			if (!$SesUser) {
				// Redirect to login area when not
				header('location: '.URL_PATH.'/LogIn/');
			} else {
				$GLOBALS['this_js']		= '<script type="text/javascript" src="/gamemaster/Application/View/js/scripts/combat.js"></script>'.PHP_EOL;	// Se não houver, definir como vazio ''
				$GLOBALS['this_css']	= '<link href="'.URL_PATH.'/Application/View/css/maps.css" rel="stylesheet">'.PHP_EOL;	// Se não houver, definir como vazio ''
				$GLOBALS['this_css']	.= '<link href="'.URL_PATH.'/Application/View/css/combat.css" rel="stylesheet">'.PHP_EOL;	// Se não houver, definir como vazio ''
			}
		}

		public function CombatSimulator() {
			// Classes
			$RepMap		= new RepMap();
			$ModMap		= new ModMap();

			// Variables
			$level		= 1;
			$id_course	= 1;

			// Get all branches
			$RepQuestion	= new RepQuestion();
			$branches		= $RepQuestion->getAllBranches();
			$branches		= ($branches) ? $ModMap->combo($branches, true) : false;
			// Prepare return
			//$GLOBALS['this_js']		= '<script type="text/javascript" src="/gamemaster/Application/View/js/libs/jquery.fancybox-1.3.4.pack.js"></script>'.PHP_EOL;	// Se não houver, definir como vazio ''
			//$GLOBALS['this_css']	= '<link href="'.URL_PATH.'/Application/View/css/jquery.fancybox-1.3.4.css" rel="stylesheet">'.PHP_EOL;	// Se não houver, definir como vazio ''
			//View::set('monsters',	$monsters);
			View::set('id_course',	$id_course);
			View::set('branches',	$branches);
			// Return
			View::render('combatSimulator');
		}
		
		public function loadMonster() {
			// Add Classes
			$RepMonster		= new RepMonster();
			$ModCombat		= new ModCombat();
			// Variables
			$return			= false;
			$time_limit		= false;
			$id_question	= false;
			$id_monster		= (isset($_POST['id_monster'])) ? trim(($_POST['id_monster'])) : false;
			$id_course		= (isset($_POST['id_course'])) ? trim(($_POST['id_course'])) : false;
			if (($id_monster) && ($id_course)) {
				// Get monster's info
				$monster	= $RepMonster->getById($id_monster);
				if ($monster) {
					/*/
					// Get a random question from the course
					$RepQuestion		= new RepQuestion();
					$id_question		= ($id_question = $RepQuestion->getRandomQuestionByCourseId($id_course)) ? $id_question['id_question'] : false;
					$question			= ($id_question) ? $RepQuestion->getQuestionById($id_question) : false;
					$answers			= ($id_question) ? $RepQuestion->getAnswersByQuestionId($id_question) : false;
					// Calculate Monster's treasure drop
					// Model Data
					if ($question) {
						$time_limit		= $question['int_timelimit'];
						$id_question	= $question['id'];
						$question		= $question['tx_question'];
					}
					$answers		= ($answers) ? $ModCombat->answers($answers) : false;
					/*/
					$treasure			= rand($monster['int_treasure_min'], $monster['int_treasure_max']);
					// Prepare return
					$return['monster_hp']		= rand($monster['int_hits_min'], $monster['int_hits_max']);
					$return['monster_min_dmg']	= $monster['int_damage_min'];
					$return['monster_max_dmg']	= $monster['int_damage_max'];
					$return['int_ds']			= $monster['int_ds'];
					$return['int_knowledge']	= $monster['int_knowledge'];
					$return['int_me']			= $monster['int_me'];
					//$return['question']			= $question;
					//$return['answers']			= $answers;
					$return['treasure']			= $treasure;
					//$return['time_limit']		= $time_limit;
					//$return['id_question']		= $id_question;
				}
			}
			// Return
			header('Content-Type: application/json');
			echo json_encode($return);
		}

		public function checkAnswer() {
			$RepQuestion	= new RepQuestion();
			$id_answer		= (isset($_POST['id_answer'])) ? trim($_POST['id_answer']) : false;
			$return			= ($RepQuestion->checkAnswerById($id_answer)) ? 'ok' : false;
			echo $return;
		}

		public function loadQuestion() {
			// Classes
			$RepQuestion	= new RepQuestion();
			$ModCombat		= new ModCombat();
			// Variables
			$return			= false;
			$id_course		= (isset($_POST['id_course'])) ? trim($_POST['id_course']) : false;
			$time_limit		= false;
			$id_question	= false;
			$correct		= false;
			if ($id_course) {
				// Get question and Answers
				$id_question	= ($id_question = $RepQuestion->getRandomQuestionByCourseId($id_course)) ? $id_question['id_question'] : false;
				$question		= ($id_question) ? $RepQuestion->getQuestionById($id_question) : false;
				$answers		= ($id_question) ? $RepQuestion->getAnswersByQuestionId($id_question) : false;
				// Model Data
				if ($question) {
					$time_limit			= $question['int_timelimit'];
					$id_question		= $question['id'];
					$question			= $question['tx_question'];
				}
				if ($answers) {
					foreach ($answers as $answer) {
						if ($answer['boo_correct'] == 1) {
							$correct	= $answer['id'];
							break;
						}
					}
					$answers			= $ModCombat->answers($answers);
				}
				// Prepare return
				$return['question']		= $question;
				$return['answers']		= $answers;
				$return['time_limit']	= $time_limit;
				$return['id_question']	= $id_question;
				$return['correct']		= $correct;
			}
			// Return
			header('Content-Type: application/json');
			echo json_encode($return);
		}

		public function loadMonsters() {
			// Classes
			$RepQuestion	= new RepQuestion();
			$ModMap			= new ModMap();
			$id_course		= (isset($_POST['id_course'])) ? trim($_POST['id_course']) : false;
			$return			= false;
			if ($id_course) {
				$course		= $RepQuestion->getCourseById($id_course);
				// Get all Monsters
				$RepMap		= new RepMap();
				$monsters	= ($course) ? $RepMap->getminAllMonstersByLevel($course['int_level']) : false;
				// Model all monster
				$return		= ($monsters) ? $ModMap->listMonsters($monsters) : false;
			}
			// Return
			echo $return;
		}

		/* ************************************************************* */
		/* ************************** ROBOTS *************************** */
		/* ************************************************************* */

		public function resetWorld() {
			$RepMap		= new RepMap();
			$RepMap->resetWorld();
			echo 'Mundo Resetado';
		}

		public function resetTextures() {
			$RepMap		= new RepMap();
			$RepMap->resetTextures();
			echo 'texturas resetadas!!';
		}

		public function createNewWorldMaps() {
			$RepMap		= new RepMap();
			// Prepare maps' data
			for ($i = 0; $i < 100; $i++) {
				$coords[]	= '0';
				$map_name[]	= 'World Map - '.sprintf('%03d', $i+1);
			}
			// Create Maps
			for ($i = 0; $i < 100; $i++) {
				$RepMap->insertMap(0, 4, $map_name[$i], $coords);
			}
			echo 'Novo mundo criado';
		}

		public function linkWorldMaps() {
			$RepMap		= new RepMap();
			// Get all World Maps
			$maps		= $RepMap->getAllWorldMaps();
			for ($i = 0; $i < 100; $i++) {
				if ($i == 0) {
					// down
					$id_target	= $maps[$i+10]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'down');
					// right
					$id_target	= $maps[$i+1]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'right');
				} else if (($i > 0) && ($i < 9)) {
					// left
					$id_target	= $maps[$i-1]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'left');
					// right
					$id_target	= $maps[$i+1]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'right');
					// down
					$id_target	= $maps[$i+10]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'down');
				} else if ($i == 9) {
					// down
					$id_target	= $maps[$i+10]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'down');
					// left
					$id_target	= $maps[$i-1]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'left');
				} else if ($i == 10) {
					// down
					$id_target	= $maps[$i+10]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'down');
					// right
					$id_target	= $maps[$i+1]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'right');
					// up
					$id_target	= $maps[$i-10]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'up');
				} else if (($i > 10) && ($i < 19)) {
					// left
					$id_target	= $maps[$i-1]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'left');
					// right
					$id_target	= $maps[$i+1]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'right');
					// down
					$id_target	= $maps[$i+10]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'down');
					// up
					$id_target	= $maps[$i-10]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'up');
				} else if ($i == 19) {
					// down
					$id_target	= $maps[$i+10]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'down');
					// left
					$id_target	= $maps[$i-1]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'left');
					// up
					$id_target	= $maps[$i-10]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'up');
				} else if ($i == 20) {
					// down
					$id_target	= $maps[$i+10]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'down');
					// right
					$id_target	= $maps[$i+1]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'right');
					// up
					$id_target	= $maps[$i-10]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'up');
				} else if (($i > 20) && ($i < 29)) {
					// left
					$id_target	= $maps[$i-1]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'left');
					// right
					$id_target	= $maps[$i+1]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'right');
					// down
					$id_target	= $maps[$i+10]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'down');
					// up
					$id_target	= $maps[$i-10]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'up');
				} else if ($i == 29) {
					// down
					$id_target	= $maps[$i+10]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'down');
					// left
					$id_target	= $maps[$i-1]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'left');
					// up
					$id_target	= $maps[$i-10]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'up');
				} else if ($i == 30) {
					// down
					$id_target	= $maps[$i+10]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'down');
					// right
					$id_target	= $maps[$i+1]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'right');
					// up
					$id_target	= $maps[$i-10]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'up');
				} else if (($i > 30) && ($i < 39)) {
					// left
					$id_target	= $maps[$i-1]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'left');
					// right
					$id_target	= $maps[$i+1]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'right');
					// down
					$id_target	= $maps[$i+10]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'down');
					// up
					$id_target	= $maps[$i-10]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'up');
				} else if ($i == 39) {
					// down
					$id_target	= $maps[$i+10]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'down');
					// left
					$id_target	= $maps[$i-1]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'left');
					// up
					$id_target	= $maps[$i-10]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'up');
				} else if ($i == 40) {
					// down
					$id_target	= $maps[$i+10]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'down');
					// right
					$id_target	= $maps[$i+1]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'right');
					// up
					$id_target	= $maps[$i-10]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'up');
				} else if (($i > 40) && ($i < 49)) {
					// left
					$id_target	= $maps[$i-1]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'left');
					// right
					$id_target	= $maps[$i+1]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'right');
					// down
					$id_target	= $maps[$i+10]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'down');
					// up
					$id_target	= $maps[$i-10]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'up');
				} else if ($i == 49) {
					// down
					$id_target	= $maps[$i+10]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'down');
					// left
					$id_target	= $maps[$i-1]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'left');
					// up
					$id_target	= $maps[$i-10]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'up');
				} else if ($i == 50) {
					// down
					$id_target	= $maps[$i+10]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'down');
					// right
					$id_target	= $maps[$i+1]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'right');
					// up
					$id_target	= $maps[$i-10]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'up');
				} else if (($i > 50) && ($i < 59)) {
					// left
					$id_target	= $maps[$i-1]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'left');
					// right
					$id_target	= $maps[$i+1]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'right');
					// down
					$id_target	= $maps[$i+10]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'down');
					// up
					$id_target	= $maps[$i-10]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'up');
				} else if ($i == 59) {
					// down
					$id_target	= $maps[$i+10]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'down');
					// left
					$id_target	= $maps[$i-1]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'left');
					// up
					$id_target	= $maps[$i-10]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'up');
				} else if ($i == 60) {
					// down
					$id_target	= $maps[$i+10]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'down');
					// right
					$id_target	= $maps[$i+1]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'right');
					// up
					$id_target	= $maps[$i-10]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'up');
				} else if (($i > 60) && ($i < 69)) {
					// left
					$id_target	= $maps[$i-1]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'left');
					// right
					$id_target	= $maps[$i+1]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'right');
					// down
					$id_target	= $maps[$i+10]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'down');
					// up
					$id_target	= $maps[$i-10]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'up');
				} else if ($i == 69) {
					// down
					$id_target	= $maps[$i+10]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'down');
					// left
					$id_target	= $maps[$i-1]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'left');
					// up
					$id_target	= $maps[$i-10]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'up');
				} else if ($i == 70) {
					// down
					$id_target	= $maps[$i+10]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'down');
					// right
					$id_target	= $maps[$i+1]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'right');
					// up
					$id_target	= $maps[$i-10]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'up');
				} else if (($i > 70) && ($i < 79)) {
					// left
					$id_target	= $maps[$i-1]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'left');
					// right
					$id_target	= $maps[$i+1]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'right');
					// down
					$id_target	= $maps[$i+10]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'down');
					// up
					$id_target	= $maps[$i-10]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'up');
				} else if ($i == 79) {
					// down
					$id_target	= $maps[$i+10]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'down');
					// left
					$id_target	= $maps[$i-1]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'left');
					// up
					$id_target	= $maps[$i-10]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'up');
				} else if ($i == 80) {
					// down
					$id_target	= $maps[$i+10]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'down');
					// right
					$id_target	= $maps[$i+1]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'right');
					// up
					$id_target	= $maps[$i-10]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'up');
				} else if (($i > 80) && ($i < 89)) {
					// left
					$id_target	= $maps[$i-1]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'left');
					// right
					$id_target	= $maps[$i+1]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'right');
					// down
					$id_target	= $maps[$i+10]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'down');
					// up
					$id_target	= $maps[$i-10]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'up');
				} else if ($i == 89) {
					// left
					$id_target	= $maps[$i-1]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'left');
					// up
					$id_target	= $maps[$i-10]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'up');
				} else if ($i == 90) {
					// right
					$id_target	= $maps[$i+1]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'right');
					// up
					$id_target	= $maps[$i-10]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'up');
				} else if (($i > 91) && ($i < 99)) {
					// left
					$id_target	= $maps[$i-1]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'left');
					// right
					$id_target	= $maps[$i+1]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'right');
					// up
					$id_target	= $maps[$i-10]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'up');
				} else if ($i == 99) {
					// left
					$id_target	= $maps[$i-1]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'left');
					// up
					$id_target	= $maps[$i-10]['id'];
					$RepMap->addWorldMapLink($maps[$i]['id'], $id_target, 'up');
				} 
			}
			echo 'mundo criado';
		}

	}