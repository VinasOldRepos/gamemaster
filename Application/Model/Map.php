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

	class Map {

		public function world($world = false) {
			$return				= false;
			if ($world) {
				for ($i = 1; $i <= 100; $i++) {
					if ($i == 1) {
						$return	.= '<div class="map_row">'.PHP_EOL;
					}
					$pos		= sprintf('%03d', $i);
					if ($world['vc_coord_'.$pos] != 0) {
						$return	.= '<img class="map_tile world_map_tile" pos="'.$pos.'" key="EditLocalMap" src="/gamemaster/Application/View/img/textures/'.$world['vc_coord_'.$pos].'" width="35" height="35" border="0" alt="" title="" >';
					} else {
						$return	.= '<img class="map_tile world_map_tile" pos="'.$pos.'" key="NewLocalMap" src="/gamemaster/Application/View/img/textures/bk_veil_of_ignorance.gif" width="35" height="35" border="0" alt="" title="" >';
					}
					if ($i % 10 == 0) {
						$return	.= '</div>'.PHP_EOL;
						$return	.= '<div class="map_row">'.PHP_EOL;
					}
				}
				$return			.= '</div>'.PHP_EOL;
			}
			return $return;
		}

		public function newMap($tiles = false) {
			$return				= false;
			if ($tiles) {
				$tot_tiles		= count($tiles);	
				for ($i = 1; $i <= 100; $i++) {
					$tile_pos	=  rand(0, $tot_tiles - 1);
					if ($i == 1) {
						$return	.= '<div class="map_row">'.PHP_EOL;
					}
					$pos		= sprintf('%03d', $i);
					$return	.= '<img class="map_tile local_map_tile" id="pos_'.$pos.'" image="'.$tiles[$tile_pos]['vc_path'].'" src="/gamemaster/Application/View/img/textures/'.$tiles[$tile_pos]['vc_path'].'" width="35" height="35" border="0" alt="" title="" >';
					if ($i % 10 == 0) {
						$return	.= '</div>'.PHP_EOL;
						$return	.= '<div class="map_row">'.PHP_EOL;
					}
				}
				$return			.= '</div>'.PHP_EOL;
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

	}