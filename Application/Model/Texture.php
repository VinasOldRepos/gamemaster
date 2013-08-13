<?php
/************************************************************************************
* Name:				User Model														*
* File:				Application\Model\User.php 										*
* Author(s):		Vinas de Andrade												*
*																					*
* Description: 		This is the User's model.										*
*																					*
* Creation Date:	15/11/2012														*
* Version:			1.12.1115														*
* License:			http://www.opensource.org/licenses/bsd-license.php BSD			*
*************************************************************************************/

	namespace Application\Model;

	class Texture {

		public function listBkgTiles($entries = false, $ordering = false, $direction = false) {
			$return				= false;
			if ($entries) {
				$return			= $this->resultBkgHeaderTiles($ordering, $direction);
				$tot_entries	= count($entries);
				for ($i = 0; $i < $tot_entries; $i++) {
					$return		.= '<div class="return_row tile_row" key="'.$entries[$i]['id'].'">'.PHP_EOL;
					$return		.= '	<div class="result_field result_id">'.$entries[$i]['id'].'</div>'.PHP_EOL;
					$return		.= '	<div class="result_field result_tiletype">'.$entries[$i]['vc_tiletype'].'</div>'.PHP_EOL;
					$return		.= '	<div class="result_field result_tilename">'.$entries[$i]['vc_name'].'</div>'.PHP_EOL;
					$return		.= '	<div class="result_field result_tile"><img src="/gamemaster/Application/View/img/textures/t_'.$entries[$i]['vc_path'].'" width="10" height="10" /></div>'.PHP_EOL;
					$return		.= '</div><br />'.PHP_EOL;
				}
			}
			return $return;
		}
 
		public function listDtlTiles($entries = false, $ordering = false, $direction = false) {
			$return				= false;
			if ($entries) {
				$return			= $this->resultlDtlHeaderTiles($ordering, $direction);
				$tot_entries	= count($entries);
				for ($i = 0; $i < $tot_entries; $i++) {
					$return		.= '<div class="return_row tile_row" key="'.$entries[$i]['id'].'">'.PHP_EOL;
					$return		.= '	<div class="result_field result_id">'.$entries[$i]['id'].'</div>'.PHP_EOL;
					$return		.= '	<div class="result_field result_tilename">'.$entries[$i]['vc_name'].'</div>'.PHP_EOL;
					$return		.= '	<div class="result_field result_tile"><img src="/gamemaster/Application/View/img/textures/t_'.$entries[$i]['vc_path'].'" width="10" height="10" /></div>'.PHP_EOL;
					$return		.= '</div><br />'.PHP_EOL;
				}
			}
			return $return;
		}

		public function jqueryBkgTiles($entries = false, $pager = false, $ordering = 'b.id', $direction = 'ASC') {
			$return				= false;
			$rows				= '';
			if (($entries) && ($pager)) {
				$tot_entries	= count($entries);
				for ($i = 0; $i < $tot_entries; $i++) {
					$rows		.= '<div class="return_row tile_row" key="'.$entries[$i]['id'].'">'.PHP_EOL;
					$rows		.= '	<div class="result_field result_id">'.$entries[$i]['id'].'</div>'.PHP_EOL;
					$rows		.= '	<div class="result_field result_tiletype">'.$entries[$i]['vc_tiletype'].'</div>'.PHP_EOL;
					$rows		.= '	<div class="result_field result_tilename">'.$entries[$i]['vc_name'].'</div>'.PHP_EOL;
					$rows		.= '	<div class="result_field result_tile"><img src="/gamemaster/Application/View/img/textures/t_'.$entries[$i]['vc_path'].'" width="10" height="10" /></div>'.PHP_EOL;
					$rows		.= '</div><br />'.PHP_EOL;
				}
				$return			.= '<div class="navigation_box" id="up_nav_box">'.PHP_EOL;
				$return			.= $pager.PHP_EOL;
				$return			.= '</div>'.PHP_EOL;
				$return			.= '<div class="result_box" id="result_box">'.PHP_EOL;
				$return			.= $this->resultBkgHeaderTiles($ordering, $direction).PHP_EOL;
				$return			.= $rows.PHP_EOL;
				$return			.= '</div>'.PHP_EOL;
				$return			.= '<div class="navigation_box" id="down_nav_box">'.PHP_EOL;
				$return			.= $pager.PHP_EOL;
				$return			.= '</div>'.PHP_EOL;
			}
			return $return;
		}

		public function jqueryDtlTiles($entries = false, $pager = false, $ordering = 'id', $direction = 'ASC') {
			$return				= false;
			$rows				= '';
			if (($entries) && ($pager)) {
				$tot_entries	= count($entries);
				for ($i = 0; $i < $tot_entries; $i++) {
					$rows		.= '<div class="return_row tile_row" key="'.$entries[$i]['id'].'">'.PHP_EOL;
					$rows		.= '	<div class="result_field result_id">'.$entries[$i]['id'].'</div>'.PHP_EOL;
					$rows		.= '	<div class="result_field result_tilename">'.$entries[$i]['vc_name'].'</div>'.PHP_EOL;
					$rows		.= '	<div class="result_field result_tile"><img src="/gamemaster/Application/View/img/textures/t_'.$entries[$i]['vc_path'].'" width="10" height="10" /></div>'.PHP_EOL;
					$rows		.= '</div><br />'.PHP_EOL;
				}
				$return			.= '<div class="navigation_box" id="up_nav_box">'.PHP_EOL;
				$return			.= $pager.PHP_EOL;
				$return			.= '</div>'.PHP_EOL;
				$return			.= '<div class="result_box" id="result_box">'.PHP_EOL;
				$return			.= $this->resultlDtlHeaderTiles($ordering, $direction).PHP_EOL;
				$return			.= $rows.PHP_EOL;
				$return			.= '</div>'.PHP_EOL;
				$return			.= '<div class="navigation_box" id="down_nav_box">'.PHP_EOL;
				$return			.= $pager.PHP_EOL;
				$return			.= '</div>'.PHP_EOL;
			}
			return $return;
		}

		public function comboTextureTypes($textureTypes = false, $id_texturetype = false) {
			$return				= false;
			if ($textureTypes) {
				$return			.= '<option value="0">Select...</option>'.PHP_EOL;
				foreach ($textureTypes as $textureType) {
					if ($textureType['id'] == $id_texturetype) {
						$return	.= '<option value="'.$textureType['id'].'" selected="selected">'.$textureType['vc_name'].'</option>'.PHP_EOL;
					} else {
						$return	.= '<option value="'.$textureType['id'].'">'.$textureType['vc_name'].'</option>'.PHP_EOL;
					}
				}
			}
			return $return;
		}

		public function comboTileTypes($tileTypes = false, $id_tiletype = false) {
			//$return			= false;
			$return				= '<option value="0">Select...</option>'.PHP_EOL;
			if ($tileTypes) {
				foreach ($tileTypes as $tileType) {
					if ($tileType['id'] == $id_tiletype) {
						$return	.= '<option value="'.$tileType['id'].'" selected="selected">'.$tileType['vc_name'].'</option>'.PHP_EOL;
					} else {
						$return	.= '<option value="'.$tileType['id'].'">'.$tileType['vc_name'].'</option>'.PHP_EOL;
					}
				}
			}
			return $return;
		}

		public function combo($entries = false, $null_select = false, $id = false) {
			$return				= false;
			if ($entries) {
				$return			.= ($null_select) ? '<option value="0">Select...</option>' : ''.PHP_EOL;
				foreach ($entries as $entry) {
					if ($entry['id'] == $id) {
						$return	.= '<option value="'.$entry['id'].'" selected="selected">'.$entry['vc_name'].'</option>'.PHP_EOL;
					} else {
						$return	.= '<option value="'.$entry['id'].'">'.$entry['vc_name'].'</option>'.PHP_EOL;
					}
				}
			}
			return $return;
		}

		private	function resultBkgHeaderTiles($ordering = 'b.id', $direction = 'ASC') {
			$return		= '<div class="result_header">'.PHP_EOL;
			if (($ordering == 'b.id') && ($direction == 'ASC')) {
				$return	.= '	<div class="result_header_tile result_id" key="b.id" direction="DESC"><img src="/gamemaster/Application/View/img/arrow_down_mini.gif" width="16" height="16" align="absmiddle" />ID</div>'.PHP_EOL;
			} else if (($ordering == 'b.id') && ($direction == 'DESC')) {
				$return	.= '	<div class="result_header_tile result_id" key="b.id" direction="ASC"><img src="/gamemaster/Application/View/img/arrow_up_mini.gif" width="16" height="16" align="absmiddle" />ID</div>'.PHP_EOL;
			} else {
				$return	.= '	<div class="result_header_tile result_id" key="b.id" direction="ASC">ID</div>'.PHP_EOL;
			}
			if (($ordering == 'tt.vc_name') && ($direction == 'ASC')) {
				$return	.= '	<div class="result_header_tile result_tiletype"  key="tt.vc_name" direction="DESC" style="text-align: center;"><img src="/gamemaster/Application/View/img/arrow_down_mini.gif" width="16" height="16" align="absmiddle" />Tile Type</div>'.PHP_EOL;
			} else if (($ordering == 'tt.vc_name') && ($direction == 'DESC')) {
				$return	.= '	<div class="result_header_tile result_tiletype"  key="tt.vc_name" direction="ASC" style="text-align: center;"><img src="/gamemaster/Application/View/img/arrow_up_mini.gif" width="16" height="16" align="absmiddle" />Tile Type</div>'.PHP_EOL;
			} else {
				$return	.= '	<div class="result_header_tile result_tiletype"  key="tt.vc_name" direction="ASC" style="text-align: center;">Tile Type</div>'.PHP_EOL;
			}
			if (($ordering == 'b.vc_name') && ($direction == 'ASC')) {
				$return	.= '	<div class="result_header_tile result_tilename"  key="b.vc_name" direction="DESC" style="text-align: center;"><img src="/gamemaster/Application/View/img/arrow_down_mini.gif" width="16" height="16" align="absmiddle" />Tile Name</div>'.PHP_EOL;
			} else if (($ordering == 'b.vc_name') && ($direction == 'DESC')) {
				$return	.= '	<div class="result_header_tile result_tilename"  key="b.vc_name" direction="ASC" style="text-align: center;"><img src="/gamemaster/Application/View/img/arrow_up_mini.gif" width="16" height="16" align="absmiddle" />Tile Name</div>'.PHP_EOL;
			} else {
				$return	.= '	<div class="result_header_tile result_tilename"  key="b.vc_name" direction="ASC" style="text-align: center;">Tile Name</div>'.PHP_EOL;
			}

			$return	.= '	<div class="result_header_tile result_tile"  key="" direction="DESC" style="text-align: center;">Tile</div>'.PHP_EOL;
			$return	.= '</div><br />'.PHP_EOL;
			return $return;
		}

		private	function resultlDtlHeaderTiles($ordering = 'id', $direction = 'ASC') {
			$return		= '<div class="result_header">'.PHP_EOL;
			if (($ordering == 'id') && ($direction == 'ASC')) {
				$return	.= '	<div class="result_header_tile result_id" key="bid" direction="DESC"><img src="/gamemaster/Application/View/img/arrow_down_mini.gif" width="16" height="16" align="absmiddle" />ID</div>'.PHP_EOL;
			} else if (($ordering == 'id') && ($direction == 'DESC')) {
				$return	.= '	<div class="result_header_tile result_id" key="id" direction="ASC"><img src="/gamemaster/Application/View/img/arrow_up_mini.gif" width="16" height="16" align="absmiddle" />ID</div>'.PHP_EOL;
			} else {
				$return	.= '	<div class="result_header_tile result_id" key="id" direction="ASC">ID</div>'.PHP_EOL;
			}
			if (($ordering == 'vc_name') && ($direction == 'ASC')) {
				$return	.= '	<div class="result_header_tile result_tilename"  key="vc_name" direction="DESC" style="text-align: center;"><img src="/gamemaster/Application/View/img/arrow_down_mini.gif" width="16" height="16" align="absmiddle" />Tile Name</div>'.PHP_EOL;
			} else if (($ordering == 'vc_name') && ($direction == 'DESC')) {
				$return	.= '	<div class="result_header_tile result_tilename"  key="vc_name" direction="ASC" style="text-align: center;"><img src="/gamemaster/Application/View/img/arrow_up_mini.gif" width="16" height="16" align="absmiddle" />Tile Name</div>'.PHP_EOL;
			} else {
				$return	.= '	<div class="result_header_tile result_tilename"  key="vc_name" direction="ASC" style="text-align: center;">Tile Name</div>'.PHP_EOL;
			}

			$return	.= '	<div class="result_header_tile result_tile"  key="" direction="DESC" style="text-align: center;">Tile</div>'.PHP_EOL;
			$return	.= '</div><br />'.PHP_EOL;
			return $return;
		}

	}