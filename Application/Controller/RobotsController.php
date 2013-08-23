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
	use SaSeed\General;

	// Repository Classes
	use Application\Controller\Repository\Map		as RepMap;

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
			}
		}

		public function resetWorld() {
			$RepMap		= new RepMap();
			$RepMap->resetWorld();
			echo 'terminou chefe!!';
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
			echo 'terminou chapa!!';
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
			echo 'demorou mas funcionou!!!';
		}

	}