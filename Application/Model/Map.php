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

		public function world($world = false, $links = false, $navigation = false) {
			$return				= false;
			if ($links) {
				foreach ($links as $link) {
					$id_target[$link['int_pos']]	= $link['id_map_target'];
				}
			}
			if ($world) {
				if ($navigation) {
					foreach ($navigation as $link) {
						if ($link['vc_direction'] == 'up') {
							$return	.= '<div class="go_up go pointer" key="'.$link['id_map_target'].'">up</div>'.PHP_EOL;
						} else if ($link['vc_direction'] == 'left') {
							$return	.= '<div class="go_left go pointer" key="'.$link['id_map_target'].'">&lt;-</div>'.PHP_EOL;
						} else if ($link['vc_direction'] == 'right') {
							$return	.= '<div class="go_right go pointer" key="'.$link['id_map_target'].'">-&gt;</div>'.PHP_EOL;
						} else if ($link['vc_direction'] == 'down') {
							$return	.= '<div class="go_down go pointer" key="'.$link['id_map_target'].'">down</div>'.PHP_EOL;
						}
					}
				}
				for ($i = 1; $i <= 100; $i++) {
					if ($i == 1) {
						$return	.= '<div class="map_row">'.PHP_EOL;
					}
					$pos		= sprintf('%03d', $i);
					if ($world['vc_coord_'.$pos] == '0') {
						$return	.= '<img class="map_tile world_map_tile" pos="'.$pos.'" key="NewLocalMap" src="/gamemaster/Application/View/img/textures/bk_veil_of_ignorance.gif" width="32" height="32" border="0" alt="" title="" >';
					} else {
						$map	= (isset($id_target[$i])) ? $id_target[$i] : false;
						$return	.= '<img class="map_tile world_map_tile" pos="'.$pos.'" key="EditLocalMap" map="'.$map.'" src="/gamemaster/Application/View/img/textures/'.$world['vc_coord_'.$pos].'" width="32" height="32" border="0" alt="" title="" >';
					}
					if ($i % 10 == 0) {
						$return	.= '</div>'.PHP_EOL;
						$return	.= '<div class="map_row">'.PHP_EOL;
					}
				}
				$return			.= '</div>'.PHP_EOL;
				$return			.= '<br />'.PHP_EOL;
			}
			return $return;
		}

		public function map($map = false, $links = false) {
			$return				= false;
			if ($links) {
				foreach ($links as $link) {
					$targets[$link['int_pos']][0]	= $link['id_icon'];
					$targets[$link['int_pos']][1]	= $link['vc_path'];
					$targets[$link['int_pos']][2]	= ($link['id_map_target'] !== false) ? $link['id_map_target'] : false;
				}
			}
			if ($map) {
				for ($i = 1; $i <= 100; $i++) {
					if ($i == 1) {
						$return	.= '<div class="map_row">'.PHP_EOL;
					}
					$pos		= sprintf('%03d', $i);
					if (isset($targets[$i])) {
						$return		.= '<div class="map_tile local_map_tile" id="'.$pos.'" icon="'.$targets[$i][0].'" status="unselected" bkgrnd="'.$map['vc_coord_'.$pos].'" target="'.$targets[$i][2].'" image="" style="background-image:url(/gamemaster/Application/View/img/textures/'.$map['vc_coord_'.$pos].');">'.PHP_EOL;
						$return		.= '	<img src="/gamemaster/Application/View/img/textures/'.$targets[$i][1].'" width="32" height="32" />'.PHP_EOL;
						$return		.= '</div>'.PHP_EOL;
					} else {
						$return		.= '<div class="map_tile local_map_tile" id="'.$pos.'" icon="" status="unselected" bkgrnd="'.$map['vc_coord_'.$pos].'" image="" style="background-image:url(/gamemaster/Application/View/img/textures/'.$map['vc_coord_'.$pos].');"></div>'.PHP_EOL;
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

		public function dungeon($map = false, $links = false, $monsters = false) {
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
					$return		.= '<div class="map_tile dungeon_map_tile" id="'.$pos.'" icon="" target="'.$target_map.'" status="unselected" bkgrnd="'.$map['vc_coord_'.$pos].'" image="" style="background-image:url(/gamemaster/Application/View/img/textures/'.$map['vc_coord_'.$pos].');"></div>'.PHP_EOL;
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

		public function newDungeon() {
			$return			= false;
			for ($i = 1; $i <= 100; $i++) {
				if ($i == 1) {
					$return	.= '<div class="map_row">'.PHP_EOL;
				}
				$pos		= sprintf('%03d', $i);
				//$return		.= '<div class="map_tile dungeon_map_tile" id="'.$pos.'" icon="" status="unselected" bkgrnd="bk_dungeon_01.gif" image="" style="background-image:url(/gamemaster/Application/View/img/textures/blank_01.gif);"></div>'.PHP_EOL;
				$return		.= '<div class="map_tile dungeon_map_tile" id="'.$pos.'" icon="" status="unselected" bkgrnd="blank_01.png" image="" style="background-image:url(/gamemaster/Application/View/img/textures/blank_01.png);"></div>'.PHP_EOL;
				if ($i % 10 == 0) {
					$return	.= '</div>'.PHP_EOL;
					$return	.= '<div class="map_row">'.PHP_EOL;
				}
			}
			$return			.= '</div>'.PHP_EOL;
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

		public function levelOptions($int_level = false) {
			$return				= false;
			for ($i = 1; $i <= 13; $i++) {
				if ($i == $int_level) {
					$return	.= '<option value="'.$i.'" selected="selected">'.$i.'</option>'.PHP_EOL;
				} else {
					$return	.= '<option value="'.$i.'">'.$i.'</option>'.PHP_EOL;
				}
			}
			return $return;
		}

		public function listIcons($entries = false) {
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

		public function listTiles($entries = false) {
			$return	= '<br />No icons found'.PHP_EOL;
			if ($entries) {
				$tot_entries	= count($entries);
				$return			= '<div class="details_result_box" id="tiles_result_box">'.PHP_EOL;
				for ($i = 0; $i < $tot_entries; $i++) {
					$return		.= '	<div class="tiles_return_row" key="'.$entries[$i]['id'].'" image="'.$entries[$i]['vc_path'].'">'.PHP_EOL;
					$return		.= '		<div class="result_field result_id">'.$entries[$i]['id'].'</div>'.PHP_EOL;
					$return		.= '		<div class="result_field result_iconname">'.$entries[$i]['vc_name'].'</div>'.PHP_EOL;
					$return		.= '		<div class="result_field result_icon"><img src="/gamemaster/Application/View/img/textures/'.$entries[$i]['vc_path'].'" width="20" height="20" /></div>'.PHP_EOL;
					$return		.= '	</div><br />'.PHP_EOL;
				}
				$return			.= '</div><br />'.PHP_EOL;
			}
			return $return;
		}

		public function listDtlTiles($entries = false) {
			$return	= '<br />No icons found'.PHP_EOL;
			if ($entries) {
				$tot_entries	= count($entries);
				$return			= '<div class="details_result_box" id="tiles_result_box">'.PHP_EOL;
				$return			.= '	<span class="title_01">Choose a tile</span><br />'.PHP_EOL;
				for ($i = 0; $i < $tot_entries; $i++) {
					$return		.= '	<div class="dtl_tiles_return_row" key="'.$entries[$i]['id'].'" image="'.$entries[$i]['vc_path'].'">'.PHP_EOL;
					$return		.= '		<div class="result_field result_id">'.$entries[$i]['id'].'</div>'.PHP_EOL;
					$return		.= '		<div class="result_field result_iconname">'.$entries[$i]['vc_name'].'</div>'.PHP_EOL;
					$return		.= '		<div class="result_field result_icon"><img src="/gamemaster/Application/View/img/textures/'.$entries[$i]['vc_path'].'" width="20" height="20" /></div>'.PHP_EOL;
					$return		.= '	</div><br />'.PHP_EOL;
				}
				$return			.= '</div><br />'.PHP_EOL;
			}
			return $return;
		}

		public function listEncounterBkgTiles($entries = false) {
			$return	= '<br />No tiles found'.PHP_EOL;
			if ($entries) {
				$tot_entries	= count($entries);
				$return			= '	<span class="title_01">Choose a tile</span><br />'.PHP_EOL;
				$return			.= '<div class="details_result_box" id="tiles_result_box">'.PHP_EOL;
				for ($i = 0; $i < $tot_entries; $i++) {
					$return		.= '	<div class="encounterbkg_tiles_return_row" key="'.$entries[$i]['id'].'" image="'.$entries[$i]['vc_path'].'">'.PHP_EOL;
					$return		.= '		<div class="result_field result_id">'.$entries[$i]['id'].'</div>'.PHP_EOL;
					$return		.= '		<div class="result_field result_iconname">'.$entries[$i]['vc_name'].'</div>'.PHP_EOL;
					$return		.= '		<div class="result_field result_icon"><img src="/gamemaster/Application/View/img/textures/'.$entries[$i]['vc_path'].'" width="25" height="25" /></div>'.PHP_EOL;
					$return		.= '	</div><br /><br />'.PHP_EOL;
				}
				$return			.= '</div><br />'.PHP_EOL;
			}
			return $return;
		}

		public function listEncounterDtlTiles($entries = false) {
			$return	= '<br />No icons found'.PHP_EOL;
			if ($entries) {
				$tot_entries	= count($entries);
				$return			= '<div class="details_result_box" id="tiles_result_box">'.PHP_EOL;
				$return			.= '	<span class="title_01">Choose a tile</span><br />'.PHP_EOL;
				for ($i = 0; $i < $tot_entries; $i++) {
					$return		.= '	<div class="encounterdtl_tiles_return_row" key="'.$entries[$i]['id'].'" image="'.$entries[$i]['vc_path'].'">'.PHP_EOL;
					$return		.= '		<div class="result_field result_id">'.$entries[$i]['id'].'</div>'.PHP_EOL;
					$return		.= '		<div class="result_field result_iconname">'.$entries[$i]['vc_name'].'</div>'.PHP_EOL;
					$return		.= '		<div class="result_field result_icon"><img src="/gamemaster/Application/View/img/textures/'.$entries[$i]['vc_path'].'" width="20" height="20" /></div>'.PHP_EOL;
					$return		.= '	</div><br />'.PHP_EOL;
				}
				$return			.= '</div><br />'.PHP_EOL;
			}
			return $return;
		}

		public function listMonsters($entries = false) {
			$return	= '<br />No monsters found'.PHP_EOL;
			if ($entries) {
				$tot_entries	= count($entries);
				$return			= '<div class="details_result_box" id="tiles_result_box">'.PHP_EOL;
				for ($i = 0; $i < $tot_entries; $i++) {
					$return		.= '	<div class="monsters_return_row" key="'.$entries[$i]['id'].'">'.PHP_EOL;
					$return		.= '		<div class="result_field result_id">'.$entries[$i]['vc_id'].'</div>'.PHP_EOL;
					$return		.= '		<div class="result_field result_iconname">'.$entries[$i]['vc_name'].'</div>'.PHP_EOL;
					$return		.= '	</div><br />'.PHP_EOL;
				}
				$return			.= '</div><br />'.PHP_EOL;
			}
			return $return;
		}

		public function listAreaOrder($entries = false) {
			$return	= false;
			if ($entries) {
				$tot_entries	= count($entries);
				for ($i = 0; $i < $tot_entries; $i++) {
					$return		.= '	<div class="area_order_row" key="'.$entries[$i]['vc_tiles'].'">'.PHP_EOL;
					$return		.= '		<div class="result_field result_id"># '.$entries[$i]['int_order'].'</div>'.PHP_EOL;
					$return		.= '		<div class="result_field result_iconname">'.$entries[$i]['vc_tiles'].'</div>'.PHP_EOL;
					$return		.= '	</div><br />'.PHP_EOL;
				}
			}
			return $return;
		}

		public function changeTile($tiles = false, $tiletypes = false) {
			$return	= false;
			if ($tiles) {
				$return	.= '<div>'.PHP_EOL;
				if ($tiletypes) {
					$return	.= '	<select id="new_id_tiletype" name="new_id_tiletype">'.PHP_EOL;
					$return	.= '		'.$tiletypes.PHP_EOL;
					$return	.= '	</select>'.PHP_EOL;
					$return	.= '<br /><br />'.PHP_EOL;
				}
				$return	.= $tiles.PHP_EOL;
				$return	.= '</div>'.PHP_EOL;
			}
			return $return;
		}

		public function mapMonsters($monsters = false) {
			$return			= '<span class="title_01">Monsters in this tile</span>'.PHP_EOL;
			if ($monsters) {
				$return		.= '<br /><br />'.PHP_EOL;
				foreach ($monsters as $monster) {

					$return	.= '	<div class="not_shown tilemonster_'.$monster['vc_pos'].'">'.PHP_EOL;
					$return	.= '		<div class="result_monsterid" key="'.$monster['id'].'">(<span class="remove_monster" key="'.$monster['id'].'">X</span>)</div>'.PHP_EOL;
					$return	.= '		<div class="result_monstername">'.$monster['vc_name'].'</div>'.PHP_EOL;
					$return	.= '	</div>'.PHP_EOL;
				}
			}
			return $return;
		}

		public function totals($tot_monsters = false, $tot_treasure_min = false, $tot_treasure_max = false) {
			$return		= false;
			if (($tot_monsters) && ($tot_treasure_max) && ($tot_treasure_min)) {
				$return	.= '<div class="text_01" id="total_treasure">Total Monsters: <span class="text_01 bold" >'.$tot_monsters.'</div><br />'.PHP_EOL;
				$return	.= '<div class="text_01" id="total_treasure">Total Treasure drop: <span class="text_01 bold" >'.$tot_treasure_min.' - '.$tot_treasure_max.'</span> gold</div><br /><br />'.PHP_EOL;
			}
			return $return;
		}

	}