<?php
/************************************************************************************
* Name:				Textures Controller												*
* File:				Application\Controller\TexturesController.php 					*
* Author(s):		Vinas de Andrade												*
*																					*
* Description: 		This file controls Textures' related information.				*
*																					*
* Creation Date:	26/06/2013														*
* Version:			1.13.0626														*
* License:			http://www.opensource.org/licenses/bsd-license.php BSD			*
*************************************************************************************/

	namespace Application\Controller;

	// Framework Classes
	use SaSeed\View;
	use SaSeed\Session;
	//use SaSeed\General;

	// Model Classes
	use Application\Model\Menu;
	use Application\Model\Pager;
	use Application\Model\Texture					as ModTexture;

	// Repository Classes
	use Application\Controller\Repository\Texture	as RepTexture;
	use Application\Controller\Repository\Upload	as Upload;

	// Other Classes
	use Application\Controller\LogInController		as LogIn;

	class TexturesController {

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
				$GLOBALS['this_js']		= '<script type="text/javascript" src="/gamemaster/Application/View/js/scripts/textures.js"></script>'.PHP_EOL;	// Se não houver, definir como vazio ''
				$GLOBALS['this_js']		.= '<script type="text/javascript" src="/gamemaster/Application/View/js/libs/jquery.fancybox-1.3.4.pack.js"></script>'.PHP_EOL;	// Se não houver, definir como vazio ''
				$GLOBALS['this_css']	= '<link href="'.URL_PATH.'/Application/View/css/textures.css" rel="stylesheet">'.PHP_EOL;	// Se não houver, definir como vazio ''
				$GLOBALS['this_css']	.= '<link href="'.URL_PATH.'/Application/View/css/jquery.fancybox-1.3.4.css" rel="stylesheet">'.PHP_EOL;	// Se não houver, definir como vazio ''
				// Define Menu selection
				Menu::defineSelected($GLOBALS['controller_name']);
			}
		}

		/*
		Prints out main login page - start()
			@return format	- print
		*/
		public function index() {
			View::render('textures');
 		}

		/*
		Upload image - Insert()
			@return format	- print
		*/
		public function uploadImage() {
			// Declare classes
			$Upload			= new Upload();
			$RepTexture		= new RepTexture();
			// Initialize variables
			$return			= false;
			$filename		= false;
			$id_texturetype	= (isset($_POST['id_texturetype'])) ? trim($_POST['id_texturetype']) : false;
			$id_tiletype	= (isset($_POST['id_tiletype'])) ? trim($_POST['id_tiletype']) : false;
			$tile_name		= (isset($_POST['tile_name'])) ? trim($_POST['tile_name']) : false;
			$icon_name		= (isset($_POST['icon_name'])) ? trim($_POST['icon_name']) : false;
			$image_name		= (isset($_POST['image_name'])) ? trim($_POST['image_name']) : false;
			$img_type		= (isset($_FILES['image']['type'])) ? $_FILES['image']['type'] : false;
			$image			= (isset($_FILES['image']['tmp_name'])) ? $_FILES['image']['tmp_name'] : false;
			$img_error		= (isset($_FILES['image']['error'])) ? $_FILES['image']['error'] : false;
			$img_size		= (isset($_FILES['image']['size'])) ? $_FILES['image']['size'] : false;
			// If values were sent
			if (($id_texturetype) && ($img_type) && ($image) && ($img_error == 0) && ($img_size)) {
				// If image if jpeg, gif or png
				// Get file pre-set dimentions
				$format		= $RepTexture->getTextureDimentions($id_texturetype);
				// Upload file
				$filename	= $Upload->Upload($image, $img_type, $format);
				// If file was uploaded
				if ($filename) {
					// Check Texture type and save info into DB
					if ($id_texturetype == 1) { // TILE
						$res	= $RepTexture->insertTile(array($id_tiletype, $tile_name, $filename));
					} else if ($id_texturetype == 2) { // MAP ICON
					} else if ($id_texturetype == 3) { // IMAGE
					}
					// If info was saved ok
					if ($res) {
						View::set('texture',	$filename);
						View::render('texturesInserted');
					}
				}
			}
		}

		/*
		Prints out new Texture page - Insert()
			@return format	- print
		*/
		public function Insert() {
			// Declare classes
			$RepTexture		= new RepTexture();
			$ModTexture		= new ModTexture();
			// Initialize variables
			$branches		= false;
			// Fetch and Model Texture Types
			$textureTypes	= $RepTexture->getAllTextureTypes();
			$textureTypes	= ($textureTypes) ? $ModTexture->comboTextureTypes($textureTypes) : false;
			// Fetch and Model Tile Types
			$tileTypes		= $RepTexture->getAllTileTypes('vc_name');
			$tileTypes		= $ModTexture->comboTileTypes($tileTypes);
			// Define sub menu selection
			$GLOBALS['menu']['textures']['opt1_css'] = 'details_item_on';
			// Prepare return values
			View::set('textureTypes',	$textureTypes);
			View::set('tileTypes',		$tileTypes);
			// Render view
			View::render('texturesInsert');
 		}

		/*
		Prints out search tiles page - Search()
			@return format	- render view
		*/
		public function SearchTiles() {
			// Declare Classes
			$RepTexture			= new RepTexture();
			$ModTexture			= new ModTexture();
			// Define sub menu selection
			$GLOBALS['menu']['textures']['opt2_css'] = 'details_item_on';
			// Intialize variables
			$return				= '<br />(no tiles found)';
			$search				= (isset($_POST['search'])) ? trim($_POST['search']) : false;
			$search				= ((!$search) && (isset($GLOBALS['params'][1]))) ? trim($GLOBALS['params'][1]) : false;
			// Get first 20 entries
			$result				= $RepTexture->getAllTiles(20);
			// If there are entries
			if ($result) {
				// Separate returned data an paging info
				$rows			= $result[0];
				$paging_info	= $result[1];
				// Model Result
				$return			= $ModTexture->listTiles($rows, 't.id', 'ASC');
				// Define Pager info
				$pager			= Pager::pagerOptions($paging_info, 'textures', 'partialResultTiles');
			}
			// Prepare info to be displayed
			View::set('pager', $pager);
			View::set('return', $return);
			// render view
			View::render('tilesSearch');
 		}

		/*
		Prints partial results - partialResult()
			@return format	- render view
		*/
		public function partialResultTiles() {
			// Declare Classes
			$RepTexture				= new RepTexture();
			$ModTexture				= new ModTexture();
			// Intialize variables
			$return					= '<br />(no tiles found)';
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
					$result			= $RepTexture->getSearchedTiles($str_search, $limit, $num_page, $ordering, $direction);
				} else {
					$result			= $RepTexture->getAllTiles($limit, $num_page, $ordering, $direction);
				}
				// If there are entries
				if ($result) {
					// Separate returned data and paging info
					$rows			= $result[0];
					$paging_info	= $result[1];
					// Model Result
					$pager			= Pager::pagerOptions($paging_info, 'textures', 'partialResultTiles');
					$return			= $ModTexture->jqueryTiles($rows, $pager, $ordering, $direction);
				}
			}
			// Print out result
			echo $return;
 		}

		/*
		Open Tile details main page - detailsTile()
			@return format	- print
		*/
		public function detailsTile() {
			// Declare classes
			$RepTexture			= new RepTexture();
			$ModTexture			= new ModTexture();
			// Initialize variables
			$id_tile			= (isset($GLOBALS['params'][1])) ? trim(($GLOBALS['params'][1])) : false;
			// If tile id was sent
			if ($id_tile) {
				// Get branch Info
				$tile			= $RepTexture->getTileById($id_tile);
				// If user was found
				if ($tile) {
					// Get and model Title Type's combo
					$tileTypes	= $RepTexture->getAllTileTypes('vc_name');
					$tileTypes	= ($tileTypes) ? $ModTexture->comboTileTypes($tileTypes, $tile['id_tiletype']) : false;
					// Prepare data to be sent
					View::set('tileTypes',		$tileTypes);
					View::set('id_tile',		$id_tile);
					View::set('vc_name',		$tile['vc_name']);
					View::set('vc_path',		$tile['vc_path']);
					// Render page
					View::render('partial_tileDetails');
				}
			}
		}

		/*
		Delete Tile - deleteTile()
			@return format	- print
		*/
		public function deleteTile() {
			// Declare classes
			$RepTexture		= new RepTexture();
			// Initialize variables
			$return			= false;
			$id_tile		= (isset($_POST['id_tile'])) ? trim($_POST['id_tile']) : false;
			// If values were sent
			if ($id_tile) {
				// Delete branch
				$RepTexture->deleteTile($id_tile);
				$return		= 'ok';
			}
			// Return
			echo $return;
		}

		/*
		Update Tile info - updateTile()
			@return format	- print
		*/
		public function updateTile() {
			// Declare classes
			$RepTexture		= new RepTexture();
			// Initialize variable
			$id_tile		= (isset($_POST['id_tile'])) ? trim($_POST['id_tile']) : false;
			$id_tiletype	= (isset($_POST['id_tiletype'])) ? trim($_POST['id_tiletype']) : false;
			$vc_name		= (isset($_POST['vc_name'])) ? trim($_POST['vc_name']) : false;
			$return			= false;
			// If values were sent
			if (($id_tile) && ($id_tiletype) && ($vc_name)) {
				// Update User				
				$return		= $RepTexture->updateTile($id_tile, array($id_tiletype, $vc_name));
				// Prepare return
				$return		= ($return) ? 'ok' : false;
			}
			// Return
			echo $return;
 		}

	}