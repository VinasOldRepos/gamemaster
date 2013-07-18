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

		public function world($world = false, $links = false) {
			$return				= false;
			if ($links) {
				foreach ($links as $link) {
					$id_target[$link['int_pos']]	= $link['id_map_target'];
				}
			}
			if ($world) {
				for ($i = 1; $i <= 100; $i++) {
					if ($i == 1) {
						$return	.= '<div class="map_row">'.PHP_EOL;
					}
					$pos		= sprintf('%03d', $i);
					if ($world['vc_coord_'.$pos] == '0') {
						$return	.= '<img class="map_tile world_map_tile" pos="'.$pos.'" key="NewLocalMap" src="/gamemaster/Application/View/img/textures/bk_veil_of_ignorance.gif" width="35" height="35" border="0" alt="" title="" >';
					} else {
						
						$map	= (isset($id_target[$i])) ? $id_target[$i] : false;
						$return	.= '<img class="map_tile world_map_tile" pos="'.$pos.'" key="EditLocalMap" map="'.$map.'" src="/gamemaster/Application/View/img/textures/'.$world['vc_coord_'.$pos].'" width="35" height="35" border="0" alt="" title="" >';
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

		public function map($map = false, $links = false) {
			$return				= false;
			if ($links) {
				foreach ($links as $link) {
					$id_target[$link['int_pos']]	= $link['id_map_target'];
				}
			}
			if ($map) {
				for ($i = 1; $i <= 100; $i++) {
					if ($i == 1) {
						$return	.= '<div class="map_row">'.PHP_EOL;
					}
					$pos		= sprintf('%03d', $i);
					$target_map	= (isset($id_target[$i])) ? $id_target[$i] : false;
					$return		.= '<img class="map_tile world_map_tile" pos="'.$pos.'" key="EditLocalMap" map="'.$target_map.'" src="/gamemaster/Application/View/img/textures/'.$map['vc_coord_'.$pos].'" width="35" height="35" border="0" alt="" title="" >';
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
					$return		.= '<div class="map_tile local_map_tile" id="'.$pos.'" icon="" status="unselected" bkgrnd="'.$tiles[$tile_pos]['vc_path'].'" image="" style="background-image:url(/gamemaster/Application/View/img/textures/'.$tiles[$tile_pos]['vc_path'].');"></div>';
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

		public function listIcons($entries = false, $ordering = false, $direction = false) {
			$return	= '<br />No icons found'.PHP_EOL;
			if ($entries) {
				$tot_entries	= count($entries);
				$return			= '<span class="title_01">Choose an icon</span><br />'.PHP_EOL;
				$return			.= '<div class="details_result_box" id="result_box">'.PHP_EOL;
				for ($i = 0; $i < $tot_entries; $i++) {
					$return		.= '	<div class="details_return_row" key="'.$entries[$i]['id'].'" image="'.$entries[$i]['vc_path'].'">'.PHP_EOL;
					$return		.= '		<div class="result_field result_id">'.$entries[$i]['id'].'</div>'.PHP_EOL;
					$return		.= '		<div class="result_field result_iconname">'.$entries[$i]['vc_name'].'</div>'.PHP_EOL;
					$return		.= '		<div class="result_field result_icon"><img src="/gamemaster/Application/View/img/textures/'.$entries[$i]['vc_path'].'" width="15" height="15" /></div>'.PHP_EOL;
					$return		.= '	</div><br />'.PHP_EOL;
				}
				$return			.= '</div><br />'.PHP_EOL;
			}
			return $return;
		}
	}